<?php 
?>

<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">
<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/eventForm.css">

<form method="POST" action="<?=SITE_URL ?>organizer/createEvent" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Title</label>
        <input type="text" placeholder="Josh eventz" name="title" required >

        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd" required ></textarea>

        <label>Location</label>
        <input type="text" placeholder="Homagama" name="location_text" required >

        <label>Location Link</label>
        <input type="text" placeholder="Paste google link" name="location_link" required >

        <label for="category">Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php foreach($categories as $category): ?>
                <option value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Start Date</label>
        <input type="datetime-local" name="start_at" required >

        <label>End Date</label>
        <input type="datetime-local" name="end_at" required >

        <label>Capacity</label>
        <input type="text" placeholder="300" name="capacity">

        <label>Status</label>
        <input type="text" placeholder="Published" name="status">

        <label>Banner Pic</label>
        <input type="file" name="banner_url">

        <button type="submit">Create Event</button>
    </div>
</form>