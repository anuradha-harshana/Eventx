<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>supplier/update" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Company Name</label>
        <input type="text" placeholder="Jason Goods" name="company_name">

        <label>Business Type</label>
        <input type="text" placeholder="tech" name="business_type">

        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd"></textarea>

        <label>Website Url</label>
        <input type="text" placeholder="google.com" name="website">

        <label>Phone</label>
        <input type="text" placeholder="077 80 90 876" name="contact_phone">

        <label>Email</label>
        <input type="email" placeholder="Jason@Goods.com" name="contact_email">

        <label>Profile Pic</label>
        <input type="file" name="profile_pic">

        <button type="submit">Update</button>
    </div>
</form>