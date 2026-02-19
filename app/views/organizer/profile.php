<?php 
?>

<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>organizer/update" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Organization Name</label>
        <input type="text" placeholder="Josh eventz" name="organization_name">

        <label>Organization Type</label>
        <input type="text" placeholder="Non-Profit" name="organization_type">

        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd"></textarea>

        <label>Website Url</label>
        <input type="text" placeholder="Josh.com" name="website">

        <label>Contact Email</label>
        <input type="email" placeholder="Josh@eventz.com" name="contact_email">

        <label>Phone</label>
        <input type="text" placeholder="077 807 9685" name="contact_phone">

        <label>Logo Pic</label>
        <input type="file" name="profile_pic">

        <button type="submit">Update</button>
    </div>
</form>