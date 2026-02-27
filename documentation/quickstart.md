# EventX — Quick Start Guide

## Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- Project cloned from GitHub

---

## 1. Start the System

```bash
cd /path/to/Eventx

docker compose up -d
```

> First time only (or after a `Dockerfile` change):
> ```bash
> docker compose up -d --build
> ```

Wait ~15 seconds for MySQL to finish importing the schema, then open the app.

---

## 2. Stop the System

```bash
docker compose down
```

> Your database data is **preserved**. Use `docker compose down -v` only if you want to wipe the database.

---

## 3. View the Site

| | URL |
|---|---|
| **App** | http://localhost:8080/Eventx/ |

---

## 4. View the Database (phpMyAdmin)

| | |
|---|---|
| **URL** | http://localhost:8081 |
| **Username** | `root` |
| **Password** | `rootpassword` |

Or log in as the app user (access to `eventx_db` only):

| **Username** | `eventx_user` |
|---|---|
| **Password** | `eventx_pass` |

---

## 5. Database Passwords

| Purpose | Host | Port | Username | Password |
|---|---|---|---|---|
| Root access | `localhost` | `3307` | `root` | `rootpassword` |
| App user | `localhost` | `3307` | `eventx_user` | `eventx_pass` |
| Inside Docker (app → db) | `db` | `3306` | `eventx_user` | `eventx_pass` |

---

## 6. Test Account Passwords

All seed accounts use the password: **`password123`**

| Role | Username | Email |
|---|---|---|
| Admin | `admin` | admin@eventx.com |
| Organizer | `techevents` | tech@eventz.com |
| Organizer | `artsculture` | arts@eventz.com |
| Participant | `alice_j` | alice@example.com |
| Participant | `bob_smith` | bob@example.com |
| Participant | `carol_w` | carol@example.com |
| Sponsor | `techcorp` | sponsorship@techcorp.com |
| Sponsor | `globalbrand` | events@globalbrand.com |
| Supplier | `cateringpro` | hello@cateringpro.com |
| Supplier | `avsolutions` | info@avsolutions.com |

---

## 7. Check Container Status

```bash
docker compose ps
```

Expected output:
```
NAME                 STATUS          PORTS
eventx_db            healthy         3307->3306
eventx_web           running         8080->80
eventx_phpmyadmin    running         8081->80
```

---

## 8. Reset the Database

Wipes all data and re-imports the schema + seed data from scratch:

```bash
docker compose down -v
docker compose up -d
```

---

## 9. Troubleshooting

**Docker daemon not running:**
```bash
open -a Docker   # then wait for the whale icon to stop animating
```

**Port conflict with XAMPP:**
```bash
sudo /Applications/XAMPP/xamppfiles/xampp stop
```

**Check MySQL logs:**
```bash
docker compose logs db
```

**Check Apache logs:**
```bash
docker compose logs web
```
