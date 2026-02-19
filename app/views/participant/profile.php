<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>participant/update" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Date of Birth</label>
        <input type="date" name="date_of_birth">

        <label>Location</label>
        <input type="text" placeholder="Homagama" name="location">

        <label>Bio</label>
        <textarea name="bio" placeholder="asdas asdas asdasd asdasd"></textarea>

        <label>Interests</label>
        <input type="text" placeholder="tech" name="interests">

        <label>Occupation</label>
        <input type="text" placeholder="Student" name="occupation">

        <label>Company</label>
        <input type="text" placeholder="none" name="company">

        <label>Education</label>
        <input type="text" placeholder="Undergraduate" name="education">

        <label>Phone</label>
        <input type="text" placeholder="077 80 90 876" name="phone">

        <label>Profile Pic</label>
        <input type="file" name="profile_pic">

        <button type="submit">Update</button>
    </div>
</form>