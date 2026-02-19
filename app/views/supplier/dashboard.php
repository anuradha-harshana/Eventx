<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/dashboard.css">

<div class="dashboard">

    <header class="dashboard-top">
        <div>
            <h1>Supplier Dashboard</h1>
            <p class="subtitle">Manage your products and orders</p>
        </div>

        <a href="<?= SITE_URL ?>addProduct" class="btn-primary">
            + Add Product
        </a>
    </header>

    <section class="stats-grid">
        <div class="stat-card">
            <h3><?= $stats['total_products'] ?? 0 ?></h3>
            <p>Total Products</p>
        </div>

        <div class="stat-card">
            <h3><?= $stats['active_products'] ?? 0 ?></h3>
            <p>Active</p>
        </div>

        <div class="stat-card">
            <h3>$<?= number_format($stats['revenue'] ?? 0, 2) ?></h3>
            <p>Total Revenue</p>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2>My Products</h2>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($products)): ?>
                    <?php foreach($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['stock']) ?></td>
                            <td>
                                <span class="badge <?= $product['status'] ?>">
                                    <?= htmlspecialchars($product['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="<?= SITE_URL ?>editProduct/<?= $product['id'] ?>" class="btn-small">
                                    Edit
                                </a>
                                <button class="btn-small danger"
                                    onclick="confirmDelete(<?= $product['id'] ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">
                            No products yet.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</form>
<form id="deleteProductForm" method="POST"
      action="<?= SITE_URL ?>supplier/deleteProduct"
      style="display:none;">
    <input type="hidden" name="product_id" id="deleteProductId">
</form>

<script src="<?= SITE_URL ?>/assets/js/deleteProduct.js"></script>
</div>
