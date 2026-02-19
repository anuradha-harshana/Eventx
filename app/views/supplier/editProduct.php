<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<form method="POST" action="<?=SITE_URL ?>supplier/updateProduct" enctype="multipart/form-data">
    <div class="organizer-details">
        <img src="<?= htmlspecialchars(SITE_URL.ltrim($product['image_url'], '/') ?? '/assets/images/default-profile.png') ?>" alt="Profile Picture" class="profile-pic">
         <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        
         <label>Product Name</label>
        <input type="text" placeholder="Jason Goods" name="name" value="<?= htmlspecialchars($product['name']) ?>">

        <label>Product Price</label>
        <input type="text" placeholder="500" name="price" value="<?= htmlspecialchars($product['price']) ?>">

        <label>Stock</label>
        <input type="text" placeholder="50" name="stock" value="<?= htmlspecialchars($product['stock']) ?>">
        
        <label>Description</label>
        <textarea name="description" placeholder="asdas asdas asdasd asdasd" value="<?= htmlspecialchars($product['description']) ?>"></textarea>
        
        <label>Status</label>
        <input type="text" placeholder="available" name="status" value="<?= htmlspecialchars($product['status']) ?>">

        <label for="category">Category</label>
        <select name="category_id" required>
            <option value="<?= htmlspecialchars($product['category_id']) ?>">Select Category</option>

            <?php foreach($categories as $category): ?>
                <option value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <label>Product Pic</label>
        <input type="file" name="image_url"> 

        <button type="submit">Update</button>
    </div>
</form>