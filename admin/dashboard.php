<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$total_items    = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();
$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_customers= $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$total_revenue  = $pdo->query("SELECT COALESCE(SUM(total_price),0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

$recent_orders  = $pdo->query(
    "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id=o.order_id) AS item_count
     FROM orders o ORDER BY o.created_at DESC LIMIT 8"
)->fetchAll();

$page_title = 'Dashboard';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="fw-bold mb-0">📊 Dashboard</h2>
</div>

<!-- Stats -->
<div class="row g-3 mb-5">
  <?php $stats = [
    ['label'=>'Total Items',    'value'=>$total_items,                  'icon'=>'📦', 'color'=>'#fff0e6'],
    ['label'=>'Total Orders',   'value'=>$total_orders,                 'icon'=>'🧾', 'color'=>'#e8f4f8'],
    ['label'=>'Customers',      'value'=>$total_customers,              'icon'=>'👥', 'color'=>'#e8f5e9'],
    ['label'=>'Revenue',        'value'=>'$'.number_format($total_revenue,2),'icon'=>'💰','color'=>'#f3e8ff'],
  ]; foreach ($stats as $s): ?>
  <div class="col-6 col-md-3">
    <div class="card shadow-sm rounded-xl text-center p-4 h-100" style="background:<?= $s['color'] ?>">
      <div class="fs-2 mb-2"><?= $s['icon'] ?></div>
      <div class="fw-bold fs-4"><?= $s['value'] ?></div>
      <div class="text-muted small"><?= $s['label'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Recent Orders -->
<div class="card shadow-sm rounded-xl overflow-hidden">
  <div class="card-header bg-dark-brand text-white d-flex justify-content-between align-items-center">
    <span class="fw-bold">Recent Orders</span>
    <a href="orders.php" class="text-orange text-decoration-none small">View All →</a>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>Customer</th><th>Email</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($recent_orders as $o): ?>
        <tr>
          <td class="fw-bold text-orange">#<?= $o['order_id'] ?></td>
          <td><?= htmlspecialchars($o['customer_name']) ?></td>
          <td class="text-muted small"><?= htmlspecialchars($o['customer_email']) ?></td>
          <td><span class="badge bg-secondary"><?= $o['item_count'] ?></span></td>
          <td class="fw-bold">$<?= number_format($o['total_price'], 2) ?></td>
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
