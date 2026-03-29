<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_customer();

$order_id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, current_user_id()]);
$order = $stmt->fetch();
if (!$order) { header('HTTP/1.0 404 Not Found'); include '404.php'; exit; }

$li = $pdo->prepare(
    "SELECT oi.*, i.name, i.image, i.category FROM order_items oi
     JOIN items i ON oi.item_id = i.item_id WHERE oi.order_id = ?"
);
$li->execute([$order_id]);
$lines = $li->fetchAll();

$page_title = "Order #{$order['order_id']}";
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">

      <div class="mb-4">
        <a href="orders.php" class="text-orange text-decoration-none small">← Back to My Orders</a>
        <h1 class="fw-bold mt-2">Order #<?= $order['order_id'] ?></h1>
        <p class="text-muted small">Placed on <?= substr($order['created_at'], 0, 10) ?></p>
      </div>

      <div class="card shadow-sm rounded-xl overflow-hidden mb-4">
        <div class="card-header bg-dark-brand text-white d-flex justify-content-between align-items-center">
          <span class="fw-bold">Order Status</span>
          <span class="status-badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
        </div>
        <div class="card-body p-0">
          <?php foreach ($lines as $line): ?>
          <div class="d-flex align-items-center gap-3 p-3 border-bottom">
            <?php if ($line['image'] && file_exists(__DIR__.'/assets/uploads/'.$line['image'])): ?>
              <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($line['image']) ?>"
                   style="width:60px;height:60px;object-fit:cover;border-radius:12px" alt="">
            <?php else: ?>
              <div style="width:60px;height:60px;background:#fff0e6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;">🍿</div>
            <?php endif; ?>
            <div class="flex-grow-1">
              <div class="fw-semibold"><?= htmlspecialchars($line['name']) ?></div>
              <div class="text-muted small"><?= htmlspecialchars($line['category']) ?> · Qty: <?= $line['quantity'] ?></div>
            </div>
            <div class="text-end">
              <div class="text-muted small">$<?= number_format($line['unit_price'], 2) ?> each</div>
              <div class="fw-bold">$<?= number_format($line['quantity'] * $line['unit_price'], 2) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
          <div class="d-flex justify-content-between align-items-center px-3 py-3 bg-light">
            <span class="fw-bold">Order Total</span>
            <span class="fw-bold text-orange fs-5">$<?= number_format($order['total_price'], 2) ?></span>
          </div>
        </div>
      </div>

      <div class="card shadow-sm rounded-xl p-4 mb-4">
        <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size:0.78rem">Delivery Details</h6>
        <p class="fw-semibold mb-1"><?= htmlspecialchars($order['customer_name']) ?></p>
        <p class="text-muted small mb-1"><?= htmlspecialchars($order['customer_email']) ?></p>
        <p class="text-muted mb-0" style="white-space:pre-line"><?= htmlspecialchars($order['delivery_address']) ?></p>
      </div>

      <div class="d-flex gap-3">
        <a href="orders.php" class="btn btn-outline-secondary flex-fill">← All Orders</a>
        <a href="index.php"  class="btn btn-orange flex-fill fw-bold">Shop Again</a>
      </div>

    </div>
  </div>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
