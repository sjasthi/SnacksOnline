<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// ── Handle POST actions ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['action']  ?? '';
    $item_id = $_POST['item_id'] ?? '';

    if ($action === 'add' && $item_id) {
        $stmt = $pdo->prepare("SELECT item_id, name FROM items WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $it = $stmt->fetch();
        if ($it) {
            $_SESSION['cart'][$item_id] = ($_SESSION['cart'][$item_id] ?? 0) + 1;
            set_flash("'{$it['name']}' added to your cart! 🛒");
        }
        $back = $_POST['back'] ?? 'index.php';
        redirect("/snacksonline/$back");
    }

    if ($action === 'update') {
        foreach ($_POST['qty'] ?? [] as $id => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) unset($_SESSION['cart'][$id]);
            else           $_SESSION['cart'][$id] = $qty;
        }
        set_flash("Cart updated.");
        redirect('/snacksonline/cart.php');
    }

    if ($action === 'remove' && $item_id) {
        unset($_SESSION['cart'][$item_id]);
        set_flash("Item removed from cart.");
        redirect('/snacksonline/cart.php');
    }
}

// ── Build cart items array ─────────────────────────────────────
$cart_items = [];
$total      = 0.0;

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

$page_title = 'Your Cart';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <h1 class="fw-bold mb-4">🛍️ Your Cart</h1>

  <?php if ($cart_items): ?>
  <form method="POST" action="cart.php">
    <input type="hidden" name="action" value="update">
    <div class="row g-4">

      <!-- Cart table -->
      <div class="col-lg-8">
        <div class="card shadow-sm rounded-xl overflow-hidden">
          <table class="table cart-table mb-0">
            <thead>
              <tr>
                <th style="width:80px">Photo</th>
                <th>Item</th>
                <th class="text-center" style="width:120px">Qty</th>
                <th class="text-end" style="width:100px">Price</th>
                <th class="text-end" style="width:110px">Subtotal</th>
                <th style="width:50px"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart_items as $ci): ?>
              <tr>
                <td>
                  <?php if ($ci['image'] && file_exists(__DIR__.'/assets/uploads/'.$ci['image'])): ?>
                    <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($ci['image']) ?>"
                         class="cart-img" alt="">
                  <?php else: ?>
                    <div class="cart-img d-flex align-items-center justify-content-center bg-light rounded-3 fs-4">🍿</div>
                  <?php endif; ?>
                </td>
                <td class="align-middle">
                  <a href="item.php?id=<?= urlencode($ci['item_id']) ?>" class="fw-semibold text-dark text-decoration-none">
                    <?= htmlspecialchars($ci['name']) ?>
                  </a>
                  <div class="text-muted small"><?= htmlspecialchars($ci['category']) ?></div>
                </td>
                <td class="align-middle text-center">
                  <div class="d-flex align-items-center justify-content-center gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary qty-minus">−</button>
                    <input type="number" name="qty[<?= htmlspecialchars($ci['item_id']) ?>]"
                           value="<?= $ci['quantity'] ?>" min="0" max="99" class="form-control qty-input">
                    <button type="button" class="btn btn-sm btn-outline-secondary qty-plus">+</button>
                  </div>
                </td>
                <td class="align-middle text-end text-muted">$<?= number_format($ci['price'], 2) ?></td>
                <td class="align-middle text-end fw-bold">$<?= number_format($ci['subtotal'], 2) ?></td>
                <td class="align-middle text-center">
                  <button type="submit" name="action" value="remove"
                          onclick="this.form.item_id.value='<?= htmlspecialchars($ci['item_id']) ?>'"
                          class="btn btn-sm text-danger p-0 border-0 bg-transparent">✕</button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <input type="hidden" name="item_id" value="">
        </div>

        <div class="d-flex justify-content-between mt-3">
          <a href="index.php" class="btn btn-outline-secondary">← Keep Shopping</a>
          <button type="submit" class="btn btn-outline-orange border-orange text-orange">Update Cart</button>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="col-lg-4">
        <div class="order-summary p-4">
          <h5 class="fw-bold mb-3">Order Summary</h5>
          <?php foreach ($cart_items as $ci): ?>
          <div class="d-flex justify-content-between small mb-2">
            <span class="text-muted"><?= htmlspecialchars($ci['name']) ?> ×<?= $ci['quantity'] ?></span>
            <span>$<?= number_format($ci['subtotal'], 2) ?></span>
          </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
            <span>Total</span>
            <span class="text-orange">$<?= number_format($total, 2) ?></span>
          </div>
          <a href="checkout.php" class="btn btn-orange w-100 fw-bold">Proceed to Checkout →</a>
          <p class="text-muted small text-center mt-2 mb-0">Guest checkout available — no account needed</p>
        </div>
      </div>

    </div>
  </form>

  <?php else: ?>
  <div class="text-center py-5">
    <div class="display-1">🛒</div>
    <h3 class="fw-bold mt-3">Your cart is empty</h3>
    <p class="text-muted">Add some snacks to get started.</p>
    <a href="index.php" class="btn btn-orange px-5 mt-2">Browse Snacks →</a>
  </div>
  <?php endif; ?>

</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
