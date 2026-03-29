<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$orders = $pdo->query(
    "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id=o.order_id) AS item_count
     FROM orders o ORDER BY o.created_at DESC"
)->fetchAll();

$page_title = 'All Orders';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="fw-bold mb-0">📦 All Orders</h2>
    <small class="text-muted"><?= count($orders) ?> total order<?= count($orders)!=1?'s':'' ?></small>
  </div>
</div>

<div class="card shadow-sm rounded-xl overflow-hidden">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="bg-dark-brand text-white">
        <tr><th>#</th><th>Customer</th><th>Email</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td class="fw-bold text-orange">#<?= $o['order_id'] ?></td>
          <td class="fw-semibold"><?= htmlspecialchars($o['customer_name']) ?></td>
          <td class="text-muted small"><?= htmlspecialchars($o['customer_email']) ?></td>
          <td><span class="badge bg-secondary"><?= $o['item_count'] ?></span></td>
          <td class="fw-bold">$<?= number_format($o['total_price'],2) ?></td>
          <td><span class="status-badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
          <td class="text-muted small"><?= substr($o['created_at'],0,10) ?></td>
          <td><a href="order_detail.php?id=<?= $o['order_id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
