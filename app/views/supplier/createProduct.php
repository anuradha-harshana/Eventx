<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>supplier/createProduct" enctype="multipart/form-data">
    <div class="organizer-details">

        <label>Product Name</label>
        <input type="text" placeholder="Jason Goods" name="name">

        <label>Product Price</label>
        <input type="text" placeholder="500" name="price">

        <label>Stock</label>
        <input type="text" placeholder="50" name="stock">
        
        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd"></textarea>
        
        <label>Status</label>
        <input type="text" placeholder="available" name="status">

        <label for="category">Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>

            <?php foreach($categories as $category): ?>
                <option value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Product Pic</label>
        <input type="file" name="image_url"> 

        <button type="submit">Create</button>
    </div>
</form>