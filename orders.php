<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_customer();

$stmt = $pdo->prepare(
    "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.order_id) AS item_count
     FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC"
);
$stmt->execute([current_user_id()]);
$orders = $stmt->fetchAll();

$page_title = 'My Orders';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="fw-bold mb-0">📦 My Orders</h1>
      <small class="text-muted"><?= count($orders) ?> order<?= count($orders) !== 1 ? 's' : '' ?> placed</small>
    </div>
    <a href="index.php" class="btn btn-orange">Shop More →</a>
  </div>

  <?php if ($orders): ?>
  <div class="d-flex flex-column gap-3">
    <?php foreach ($orders as $o): ?>
    <a href="order_detail.php?id=<?= $o['order_id'] ?>" class="text-decoration-none text-dark">
      <div class="card shadow-sm rounded-xl p-3 hover-card">
        <div class="row align-items-center">
          <div class="col-auto">
            <div style="width:48px;height:48px;background:#fff0e6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📦</div>
          </div>
          <div class="col">
            <div class="fw-bold">Order #<?= $o['order_id'] ?></div>
            <div class="text-muted small"><?= substr($o['created_at'], 0, 10) ?> · <?= $o['item_count'] ?> item<?= $o['item_count'] != 1 ? 's' : '' ?></div>
          </div>
          <div class="col-auto text-end">
            <div class="fw-bold text-orange fs-5">$<?= number_format($o['total_price'], 2) ?></div>
            <span class="status-badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span>
          </div>
          <div class="col-auto text-muted">→</div>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div class="text-center py-5">
    <div class="display-1">📭</div>
    <h3 class="fw-bold mt-3">No orders yet</h3>
    <p class="text-muted">Your order history will appear here after your first purchase.</p>
    <a href="index.php" class="btn btn-orange px-5 mt-2">Start Shopping →</a>
  </div>
  <?php endif; ?>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
