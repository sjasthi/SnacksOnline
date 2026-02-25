<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$items = $pdo->query("SELECT * FROM items ORDER BY created_at DESC")->fetchAll();
$page_title = 'Manage Items';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="fw-bold mb-0">📦 Manage Items</h2>
    <small class="text-muted"><?= count($items) ?> item<?= count($items)!=1?'s':'' ?> in catalogue</small>
  </div>
  <a href="item_form.php" class="btn btn-orange">+ Add New Item</a>
</div>

<div class="card shadow-sm rounded-xl overflow-hidden">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="bg-dark-brand text-white">
        <tr>
          <th style="width:80px">Photo</th>
          <th>Item ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Added</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
          <td>
            <?php if ($item['image'] && file_exists(__DIR__.'/../assets/uploads/'.$item['image'])): ?>
              <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($item['image']) ?>"
                   style="width:56px;height:56px;object-fit:cover;border-radius:10px" alt="">
            <?php else: ?>
              <div style="width:56px;height:56px;background:#fff0e6;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🍿</div>
            <?php endif; ?>
          </td>
          <td><code><?= htmlspecialchars($item['item_id']) ?></code></td>
          <td class="fw-semibold"><?= htmlspecialchars($item['name']) ?></td>
          <td><span class="category-badge"><?= htmlspecialchars($item['category']) ?></span></td>
          <td class="fw-bold text-orange">$<?= number_format($item['price'],2) ?></td>
          <td class="text-muted small"><?= substr($item['created_at'],0,10) ?></td>
          <td class="text-center">
            <a href="item_form.php?id=<?= urlencode($item['item_id']) ?>" class="btn btn-sm btn-outline-primary me-1">✏️ Edit</a>
            <a href="item_delete.php?id=<?= urlencode($item['item_id']) ?>" class="btn btn-sm btn-outline-danger btn-delete">🗑️ Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
