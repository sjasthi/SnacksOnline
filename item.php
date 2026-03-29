<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$item_id = trim($_GET['id'] ?? '');
if (!$item_id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();
if (!$item) { header('HTTP/1.0 404 Not Found'); include '404.php'; exit; }

// Related items (same category, exclude current)
$rel = $pdo->prepare("SELECT * FROM items WHERE category = ? AND item_id != ? LIMIT 4");
$rel->execute([$item['category'], $item_id]);
$related = $rel->fetchAll();

$page_title = $item['name'];
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php" class="text-orange text-decoration-none">Shop</a></li>
      <li class="breadcrumb-item"><a href="index.php?category=<?= urlencode($item['category']) ?>" class="text-orange text-decoration-none"><?= htmlspecialchars($item['category']) ?></a></li>
      <li class="breadcrumb-item active"><?= htmlspecialchars($item['name']) ?></li>
    </ol>
  </nav>

  <div class="row g-5 align-items-center">

    <!-- Photo -->
    <div class="col-md-5">
      <div class="bg-light rounded-xl overflow-hidden text-center p-4">
        <?php if ($item['image'] && file_exists(__DIR__.'/assets/uploads/'.$item['image'])): ?>
          <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($item['image']) ?>"
               class="img-fluid rounded-xl shadow" alt="<?= htmlspecialchars($item['name']) ?>" style="max-height:380px">
        <?php else: ?>
          <div class="placeholder-image rounded-xl" style="height:320px;font-size:6rem;">🍿</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Details -->
    <div class="col-md-7">
      <span class="category-badge mb-3 d-inline-block"><?= htmlspecialchars($item['category']) ?></span>
      <h1 class="fw-bold mb-2"><?= htmlspecialchars($item['name']) ?></h1>
      <p class="text-muted small mb-3">Item ID: <code><?= htmlspecialchars($item['item_id']) ?></code></p>
      <p class="text-secondary mb-4"><?= nl2br(htmlspecialchars($item['description'] ?? '')) ?></p>
      <div class="display-6 fw-bold text-orange mb-4">$<?= number_format($item['price'], 2) ?></div>

      <form method="POST" action="cart.php" class="d-flex flex-column gap-3">
        <input type="hidden" name="action"  value="add">
        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['item_id']) ?>">
        <button type="submit" class="btn btn-orange btn-lg fw-bold">🛒 Add to Cart</button>
        <a href="index.php" class="text-muted text-decoration-none text-center">← Continue Shopping</a>
      </form>
    </div>
  </div>

  <!-- Related Items -->
  <?php if ($related): ?>
  <div class="mt-5 pt-4 border-top">
    <h4 class="fw-bold mb-4">More <?= htmlspecialchars($item['category']) ?></h4>
    <div class="row g-3">
      <?php foreach ($related as $r): ?>
      <div class="col-6 col-md-3">
        <a href="item.php?id=<?= urlencode($r['item_id']) ?>" class="text-decoration-none text-dark">
          <div class="card item-card shadow-sm">
            <?php if ($r['image'] && file_exists(__DIR__.'/assets/uploads/'.$r['image'])): ?>
              <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($r['image']) ?>"
                   style="height:140px;object-fit:cover" class="card-img-top" alt="">
            <?php else: ?>
              <div class="placeholder-image" style="height:140px;font-size:2.5rem;">🍿</div>
            <?php endif; ?>
            <div class="card-body p-3">
              <p class="fw-semibold small mb-1"><?= htmlspecialchars($r['name']) ?></p>
              <p class="text-orange fw-bold small mb-0">$<?= number_format($r['price'], 2) ?></p>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
