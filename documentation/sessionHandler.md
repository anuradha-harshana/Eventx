# Session Handling — Security Audit & Fixes

## How Sessions Work in Eventx

1. **Initialization** — `session_start()` is called in `config/config.php` (guarded by `session_status() === PHP_SESSION_NONE`). This fires on every request via `index.php`.
2. **Login** — `AuthController@login` authenticates the user and stores `user_id`, `username`, and `role` in `$_SESSION`.
3. **Route protection** — `Middleware::auth()` verifies `$_SESSION['user_id']` exists; `Middleware::role($roles)` verifies the user's role. These are called in controller constructors.
4. **Logout** — `AuthController@logout` calls `session_unset()` + `session_destroy()` and redirects.

---

## Security Issues Found

### 1. Session Fixation Not Prevented

**File:** `app/controllers/AuthController.php` (login method)

**Problem:** After a successful login the session ID was never regenerated. The same session ID that existed before authentication persisted after it. An attacker who plants a known session ID (via a URL or cookie) could hijack the session once the victim logs in.

**Risk:** Session fixation / session hijacking.

**Fix applied:** Added `session_regenerate_id(true)` immediately after verifying credentials and before populating session variables.

```php
// AuthController@login — AFTER
$user = $this->userModel->login($email, $password);
if ($user) {
    session_regenerate_id(true);          // ← added
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
    ...
}
```

---

### 2. `Middleware::role()` Did Not Verify Authentication First

**File:** `app/core/Middleware.php` — `role()` method

**Problem:** `role()` checked `$_SESSION['role']` but never confirmed the user was actually logged in (`$_SESSION['user_id']`). If `$_SESSION['role']` was somehow set without a valid `user_id`, the check would pass, and downstream code that reads `$_SESSION['user_id']` would break or operate on invalid data.

**Risk:** Authorization bypass, undefined-index errors, potential data corruption.

**Fix applied:** `role()` now calls `self::auth()` as its first step, which ensures the user is logged in before the role is checked. The redundant `session_start()` guard inside `role()` was removed since `auth()` already handles it.

```php
// Middleware::role() — AFTER
public static function role($roles = []) {
    self::auth();                         // ← added

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        echo "Access Denied";
        exit;
    }
}
```

---

### 3. `RegistrationController` — No Middleware Protection

**File:** `app/controllers/RegistrationController.php`

**Problem:** The constructor had no `Middleware::auth()` or `Middleware::role()` call. Each method (`register()`, `cancel()`) manually called `session_start()` and immediately trusted `$_SESSION['user_id']` without checking it existed. An unauthenticated request to `POST /register` or `POST /cancel` would trigger undefined-index errors or allow unintended data writes.

**Risk:** Unauthenticated access to registration/cancellation endpoints, undefined-index errors, data corruption.

**Fix applied:** Added `Middleware::role('participant')` in the constructor (which also calls `auth()` internally). Removed the now-redundant `session_start()` blocks from `register()` and `cancel()`.

```php
// RegistrationController — AFTER
public function __construct() {
    Middleware::role('participant');       // ← added
    $this->registrationModel = $this->model('RegistrationModel');
    $this->participantModel  = $this->model('ParticipantModel');
}
```

---

### 4. `PaymentController` — No Middleware Protection, Inconsistent Inline Checks

**File:** `app/controllers/PaymentController.php`

**Problem:** The constructor had no middleware. Each method performed its own inline `$_SESSION` check: `initiate()` returned a JSON error (no `exit`), while `checkout()` and `complete()` used `header()` + `exit`. This inconsistency meant auth handling was fragile and duplicated — any new method added to this controller could easily miss the check.

**Risk:** Inconsistent authorization enforcement, fragile auth pattern, potential bypass if a new method is added without inline checks.

**Fix applied:** Added `Middleware::role('participant')` in the constructor. Removed all per-method `$_SESSION` auth/role checks since the middleware now guarantees the user is an authenticated participant before any method runs.

```php
// PaymentController — AFTER
public function __construct() {
    Middleware::role('participant');       // ← added
    $this->eventModel        = $this->model('EventModel');
    $this->registrationModel = $this->model('RegistrationModel');
    $this->participantModel  = $this->model('ParticipantModel');
}
```

---

## Remaining Recommendations (Not Yet Fixed)

| # | Issue | Severity | Notes |
|---|-------|----------|-------|
| 1 | **No CSRF token protection** on any POST form | High | All state-changing POST routes are vulnerable to cross-site request forgery |
| 2 | **No secure session cookie configuration** (`httponly`, `secure`, `samesite`) | Medium | Session cookies can be read by JavaScript and sent over plain HTTP |
| 3 | **Missing `exit`/`die()` after `header('Location: …')` redirects** in `AuthController@login` and `AuthController@register` | Medium | PHP continues executing after a redirect header unless explicitly stopped |
| 4 | **No application-level session timeout** | Low | Sessions rely on PHP's default `gc_maxlifetime` (24 min); no custom idle-timeout is enforced |
| 5 | **Redundant `session_start()` calls** scattered across controllers | Low | Only the call in `config/config.php` ever fires; the rest are dead code |
