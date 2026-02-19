<form method="POST" action="<?= SITE_URL ?>organizer/editEvent" enctype="multipart/form-data">
<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">
<div class="organizer-details">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
    <img src="<?= htmlspecialchars(SITE_URL.ltrim($event['banner_url'], '/') ?? '/assets/images/default-profile.png') ?>" alt="Profile Picture" class="profile-pic">

    <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>">

    <input type="text" name="location_text" value="<?= htmlspecialchars($event['location_text']) ?>">

    <input type="datetime-local" name="start_at" value="<?= $event['start_at'] ?>">

    <input type="datetime-local" name="end_at" value="<?= $event['end_at'] ?>">

    <input type="number" name="capacity" value="<?= $event['capacity'] ?>">

    <select name="status">
        <option value="draft" <?= $event['status']=="draft"?"selected":"" ?>>Draft</option>
        <option value="published" <?= $event['status']=="published"?"selected":"" ?>>Published</option>
    </select>

    <label>Banner Pic</label>
    <input type="file" name="banner_url">

    <button type="submit">Update Event</button>
</div>

</form>
