<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// Build cart
$cart_items = [];
$total = 0.0;
foreach ($_SESSION['cart'] ?? [] as $item_id => $qty) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->execute([$item_id]);
    $it = $stmt->fetch();
    if ($it) {
        $subtotal     = round($it['price'] * $qty, 2);
        $total       += $subtotal;
        $cart_items[] = array_merge($it, ['quantity' => $qty, 'subtotal' => $subtotal]);
    }
}
$total = round($total, 2);

if (!$cart_items) {
    set_flash("Your cart is empty. Add some snacks first!", 'error');
    redirect('/snacksonline/index.php');
}

$errors = [];
$name    = $_POST['customer_name']    ?? (is_logged_in() ? current_user_name() : '');
$email   = $_POST['customer_email']   ?? current_user_email();
$address = $_POST['delivery_address'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['customer_name']    ?? '');
    $email   = strtolower(trim($_POST['customer_email']   ?? ''));
    $address = trim($_POST['delivery_address'] ?? '');

    if (!$name)                     $errors[] = "Full name is required.";
    if (!$email || !str_contains($email, '@')) $errors[] = "A valid email address is required.";
    if (!$address)                  $errors[] = "Delivery address is required.";

    if (!$errors) {
        // Insert order
        $stmt = $pdo->prepare(
            "INSERT INTO orders (user_id, customer_name, customer_email, delivery_address, total_price, status)
             VALUES (?, ?, ?, ?, ?, 'pending')"
        );
        $stmt->execute([current_user_id(), $name, $email, $address, $total]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $li = $pdo->prepare(
            "INSERT INTO order_items (order_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)"
        );
        foreach ($cart_items as $ci) {
            $li->execute([$order_id, $ci['item_id'], $ci['quantity'], $ci['price']]);
        }

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect to confirmation
        redirect("/snacksonline/confirmation.php?order_id=$order_id");
    }
}

$page_title = 'Checkout';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <h1 class="fw-bold mb-4">📋 Checkout</h1>

  <?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- Checkout Form -->
    <div class="col-lg-7">
      <div class="card shadow-sm rounded-xl p-4">

        <?php if (is_logged_in()): ?>
          <div class="alert alert-success py-2 mb-4">
            ✅ Ordering as <strong><?= htmlspecialchars(current_user_email()) ?></strong> — order will be saved to your history.
          </div>
        <?php else: ?>
          <div class="alert alert-info py-2 mb-4">
            Ordering as guest.
            <a href="login.php" class="fw-semibold">Log in</a> to save your order history.
          </div>
        <?php endif; ?>

        <form method="POST" action="checkout.php">
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="customer_name" value="<?= htmlspecialchars($name) ?>"
                   class="form-control" placeholder="Jane Smith" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="customer_email" value="<?= htmlspecialchars($email) ?>"
                   class="form-control" placeholder="jane@example.com" required>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Delivery Address <span class="text-danger">*</span></label>
            <textarea name="delivery_address" class="form-control" rows="3"
                      placeholder="123 Main St, Nairobi, Kenya" required><?= htmlspecialchars($address) ?></textarea>
          </div>
          <button type="submit" class="btn btn-orange btn-lg w-100 fw-bold">
            ✅ Place Order — $<?= number_format($total, 2) ?>
          </button>
        </form>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-5">
      <div class="order-summary p-4">
        <h5 class="fw-bold mb-3">Order Summary</h5>
        <?php foreach ($cart_items as $ci): ?>
        <div class="d-flex align-items-center gap-3 mb-3">
          <?php if ($ci['image'] && file_exists(__DIR__.'/assets/uploads/'.$ci['image'])): ?>
            <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($ci['image']) ?>"
                 style="width:50px;height:50px;object-fit:cover;border-radius:10px" alt="">
          <?php else: ?>
            <div style="width:50px;height:50px;background:#fff0e6;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🍿</div>
          <?php endif; ?>
          <div class="flex-grow-1">
            <div class="fw-semibold small"><?= htmlspecialchars($ci['name']) ?></div>
            <div class="text-muted small">Qty: <?= $ci['quantity'] ?></div>
          </div>
          <div class="fw-bold small">$<?= number_format($ci['subtotal'], 2) ?></div>
        </div>
        <?php endforeach; ?>
        <hr>
        <div class="d-flex justify-content-between fw-bold fs-5">
          <span>Total</span>
          <span class="text-orange">$<?= number_format($total, 2) ?></span>
        </div>
        <a href="cart.php" class="text-muted small d-block text-center mt-3">← Edit cart</a>
      </div>
    </div>

  </div>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
