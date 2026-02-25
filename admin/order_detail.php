<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$order_id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) { set_flash("Order not found.", 'error'); redirect('/snacksonline/admin/orders.php'); }

$lines = $pdo->prepare(
    "SELECT oi.*, i.name, i.image FROM order_items oi
     JOIN items i ON oi.item_id=i.item_id WHERE oi.order_id=?"
);
$lines->execute([$order_id]);
$order_lines = $lines->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    $status  = $_POST['status'] ?? 'pending';
    if (in_array($status, $allowed)) {
        $pdo->prepare("UPDATE orders SET status=? WHERE order_id=?")->execute([$status, $order_id]);
        set_flash("Order #$order_id status updated to '$status'.");
        redirect("/snacksonline/admin/order_detail.php?id=$order_id");
    }
}

$page_title = "Order #{$order['order_id']}";
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="mb-4">
  <a href="orders.php" class="text-orange text-decoration-none small">← All Orders</a>
  <h2 class="fw-bold mt-2">Order #<?= $order['order_id'] ?></h2>
  <p class="text-muted small">Placed on <?= substr($order['created_at'],0,10) ?> · <?= htmlspecialchars($order['customer_email']) ?></p>
</div>

<!-- Status Update -->
<div class="card shadow-sm rounded-xl p-4 mb-4" style="max-width:480px">
  <h5 class="fw-bold mb-3">Update Status</h5>
  <form method="POST" class="d-flex gap-2">
    <select name="status" class="form-select">
      <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
        <option value="<?= $s ?>" <?= $order['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-orange px-4 fw-bold">Save</button>
  </form>
</div>

<!-- Line items -->
<div class="card shadow-sm rounded-xl overflow-hidden mb-4">
  <div class="card-header bg-dark-brand text-white fw-bold">Items Ordered</div>
  <div class="card-body p-0">
    <?php foreach ($order_lines as $line): ?>
    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
      <?php if ($line['image'] && file_exists(__DIR__.'/../assets/uploads/'.$line['image'])): ?>
        <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($line['image']) ?>"
             style="width:60px;height:60px;object-fit:cover;border-radius:12px" alt="">
      <?php else: ?>
        <div style="width:60px;height:60px;background:#fff0e6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.8rem;">🍿</div>
      <?php endif; ?>
      <div class="flex-grow-1">
        <div class="fw-semibold"><?= htmlspecialchars($line['name']) ?></div>
        <div class="text-muted small">Qty: <?= $line['quantity'] ?> × $<?= number_format($line['unit_price'],2) ?></div>
      </div>
      <div class="fw-bold">$<?= number_format($line['quantity']*$line['unit_price'],2) ?></div>
    </div>
    <?php endforeach; ?>
    <div class="d-flex justify-content-between px-3 py-3 bg-light">
      <span class="fw-bold">Total</span>
      <span class="fw-bold text-orange fs-5">$<?= number_format($order['total_price'],2) ?></span>
    </div>
  </div>
</div>

<!-- Customer Info -->
<div class="card shadow-sm rounded-xl p-4">
  <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size:.78rem">Customer Details</h6>
  <p class="fw-semibold mb-1"><?= htmlspecialchars($order['customer_name']) ?></p>
  <p class="text-muted small mb-1"><?= htmlspecialchars($order['customer_email']) ?></p>
  <p class="text-muted mb-1" style="white-space:pre-line"><?= htmlspecialchars($order['delivery_address']) ?></p>
  <?php if ($order['user_id']): ?>
    <span class="badge bg-success mt-2">✅ Registered customer</span>
  <?php else: ?>
    <span class="badge bg-secondary mt-2">👤 Guest order</span>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
