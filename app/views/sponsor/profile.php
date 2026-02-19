<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>sponsor/update" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Brand Name</label>
        <input type="text" placeholder="Josh eventz" name="brand_name">

        <label>Industry</label>
        <input type="text" placeholder="Tech" name="industry">

        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd"></textarea>

        <label>Website Url</label>
        <input type="text" placeholder="Josh.com" name="website">

        <label>Contact Email</label>
        <input type="email" placeholder="Josh@eventz.com" name="contact_email">

        <label>Phone</label>
        <input type="text" placeholder="077 807 9685" name="contact_phone">

        <label>Budget Range</label>
        <input type="text" placeholder="10K-60K" name="budget_range">

        <label>Logo Pic</label>
        <input type="file" name="profile_pic">

        <button type="submit">Update</button>
    </div>
</form>