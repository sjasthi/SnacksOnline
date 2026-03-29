<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$order_id = (int)($_GET['order_id'] ?? 0);
$order = null; $order_lines = [];

if ($order_id) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if ($order) {
        $li = $pdo->prepare(
            "SELECT oi.*, i.name, i.image FROM order_items oi
             JOIN items i ON oi.item_id = i.item_id WHERE oi.order_id = ?"
        );
        $li->execute([$order_id]);
        $order_lines = $li->fetchAll();
    }
}

$page_title = 'Order Confirmed!';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">

      <?php if ($order): ?>

        <div class="text-center mb-5">
          <div class="display-1">✅</div>
          <h1 class="fw-bold mt-3">Order Confirmed!</h1>
          <p class="text-muted">Thank you, <strong><?= htmlspecialchars($order['customer_name']) ?></strong>! A summary has been sent to <strong><?= htmlspecialchars($order['customer_email']) ?></strong>.</p>
        </div>

        <div class="card shadow-sm rounded-xl overflow-hidden mb-4">
          <div class="card-header bg-dark-brand text-white d-flex justify-content-between align-items-center">
            <div>
              <small class="text-muted">Order Number</small>
              <div class="fw-bold text-orange fs-5">#<?= $order['order_id'] ?></div>
            </div>
            <span class="status-badge badge-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
          </div>
          <div class="card-body p-0">
            <?php foreach ($order_lines as $line): ?>
            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
              <?php if ($line['image'] && file_exists(__DIR__.'/assets/uploads/'.$line['image'])): ?>
                <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($line['image']) ?>"
                     style="width:60px;height:60px;object-fit:cover;border-radius:12px" alt="">
              <?php else: ?>
                <div style="width:60px;height:60px;background:#fff0e6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:2rem;">🍿</div>
              <?php endif; ?>
              <div class="flex-grow-1">
                <div class="fw-semibold"><?= htmlspecialchars($line['name']) ?></div>
                <div class="text-muted small">Qty: <?= $line['quantity'] ?> × $<?= number_format($line['unit_price'], 2) ?></div>
              </div>
              <div class="fw-bold">$<?= number_format($line['quantity'] * $line['unit_price'], 2) ?></div>
            </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between align-items-center px-3 py-3 bg-light">
              <span class="fw-bold">Order Total</span>
              <span class="fw-bold text-orange fs-5">$<?= number_format($order['total_price'], 2) ?></span>
            </div>
          </div>
        </div>

        <div class="card shadow-sm rounded-xl p-4 mb-4">
          <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size:0.78rem;letter-spacing:.05em">Delivery To</h6>
          <p class="fw-semibold mb-1"><?= htmlspecialchars($order['customer_name']) ?></p>
          <p class="text-muted mb-0" style="white-space:pre-line"><?= htmlspecialchars($order['delivery_address']) ?></p>
        </div>

        <div class="d-flex gap-3">
          <?php if (is_logged_in()): ?>
            <a href="orders.php" class="btn btn-outline-orange border-orange text-orange flex-fill">View My Orders</a>
          <?php else: ?>
            <a href="register.php" class="btn btn-outline-orange border-orange text-orange flex-fill">Create Account</a>
          <?php endif; ?>
          <a href="index.php" class="btn btn-orange flex-fill fw-bold">Continue Shopping</a>
        </div>

      <?php else: ?>
        <div class="text-center py-5">
          <div class="display-1">🛒</div>
          <h3 class="fw-bold mt-3">No order found</h3>
          <a href="index.php" class="btn btn-orange mt-3">Start Shopping</a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
