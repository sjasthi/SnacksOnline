<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Shop All Snacks';

$search   = trim($_GET['q']        ?? '');
$category = trim($_GET['category'] ?? '');

// ── Build query ───────────────────────────────────────────────
$sql    = "SELECT * FROM items WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql     .= " AND (name LIKE :s1 OR description LIKE :s2)";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}
if ($category !== '') {
    $sql     .= " AND category = :cat";
    $params[':cat'] = $category;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

// ── Distinct categories for filter bar ───────────────────────
$cats = $pdo->query("SELECT DISTINCT category FROM items ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);

require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<!-- ── Hero ──────────────────────────────────────────────────── -->
<section id="hero" class="hero section dark-background">
  <div class="container text-center" data-aos="fade-up" data-aos-delay="100">
    <div class="display-3 mb-3">🛒</div>
    <h2 class="mb-3">Premium Snacks, <span class="text-orange">Delivered Fast.</span></h2>
    <p class="mb-4">Hand-picked snacks from around the world. Order as a guest — no account needed.</p>
    <form class="d-flex justify-content-center gap-2" method="GET" action="index.php">
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
             class="form-control w-auto" placeholder="Search snacks..." style="min-width:280px; max-width: 400px;">
      <button type="submit" class="btn btn-orange px-4">Search</button>
    </form>
  </div>
</section>

<!-- ── Category Filter ───────────────────────────────────────── -->
<?php if ($cats): ?>
<div class="filter-bar py-3 sticky-top shadow-sm" style="top:56px;z-index:100">
  <div class="container d-flex gap-2 flex-wrap">
    <a href="index.php<?= $search ? '?q='.urlencode($search) : '' ?>"
       class="filter-pill <?= $category === '' ? 'active' : '' ?>">All</a>
    <?php foreach ($cats as $cat): ?>
      <a href="index.php?category=<?= urlencode($cat) ?><?= $search ? '&q='.urlencode($search) : '' ?>"
         class="filter-pill <?= $category === $cat ? 'active' : '' ?>">
        <?= htmlspecialchars($cat) ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- ── Items Grid ─────────────────────────────────────────────── -->
<div class="container py-5">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-0">
        <?php if ($search): ?>Results for "<?= htmlspecialchars($search) ?>"
        <?php elseif ($category): ?><?= htmlspecialchars($category) ?>
        <?php else: ?>All Snacks<?php endif; ?>
      </h2>
      <small class="text-muted"><?= count($items) ?> product<?= count($items) !== 1 ? 's' : '' ?></small>
    </div>
    <?php if ($search || $category): ?>
      <a href="index.php" class="text-orange text-decoration-none fw-semibold">Clear filters ×</a>
    <?php endif; ?>
  </div>

  <?php if ($items): ?>
  <div class="row g-4">
    <?php foreach ($items as $item): ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
      <div class="card item-card h-100 shadow-sm">

        <!-- Photo -->
        <a href="item.php?id=<?= urlencode($item['item_id']) ?>">
          <?php if ($item['image'] && file_exists(__DIR__.'/assets/uploads/'.$item['image'])): ?>
            <img src="/snacksonline/assets/uploads/<?= htmlspecialchars($item['image']) ?>"
                 alt="<?= htmlspecialchars($item['name']) ?>">
          <?php else: ?>
            <div class="placeholder-image">🍿</div>
          <?php endif; ?>
        </a>

        <div class="card-body d-flex flex-column">
          <span class="category-badge mb-2 d-inline-block"><?= htmlspecialchars($item['category']) ?></span>
          <h5 class="card-title fw-bold mb-1">
            <a href="item.php?id=<?= urlencode($item['item_id']) ?>" class="text-dark text-decoration-none">
              <?= htmlspecialchars($item['name']) ?>
            </a>
          </h5>
          <p class="card-text text-muted small flex-grow-1">
            <?= htmlspecialchars(substr($item['description'] ?? '', 0, 80)) ?>...
          </p>
          <div class="d-flex justify-content-between align-items-center mt-2">
            <span class="fw-bold text-orange fs-5">$<?= number_format($item['price'], 2) ?></span>
            <form method="POST" action="cart.php">
              <input type="hidden" name="action"  value="add">
              <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['item_id']) ?>">
              <button type="submit" class="btn btn-orange btn-sm">+ Cart</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div class="text-center py-5">
    <div class="display-1">🔍</div>
    <p class="text-muted fs-5">No snacks found<?= $search ? " for \"$search\"" : '' ?>.</p>
    <a href="index.php" class="text-orange fw-semibold text-decoration-none">Browse all snacks →</a>
  </div>
  <?php endif; ?>

</div>

<!-- ── Trust Strip ────────────────────────────────────────────── -->
<section class="bg-light py-5 border-top">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-6 col-md-3"><div class="fs-2 mb-2">🚚</div><h6 class="fw-bold">Fast Delivery</h6><p class="text-muted small mb-0">Orders dispatched within 24h</p></div>
      <div class="col-6 col-md-3"><div class="fs-2 mb-2">🛡️</div><h6 class="fw-bold">Safe Checkout</h6><p class="text-muted small mb-0">No account required</p></div>
      <div class="col-6 col-md-3"><div class="fs-2 mb-2">🌍</div><h6 class="fw-bold">Global Flavours</h6><p class="text-muted small mb-0">Snacks from around the world</p></div>
      <div class="col-6 col-md-3"><div class="fs-2 mb-2">⭐</div><h6 class="fw-bold">Hand-Picked</h6><p class="text-muted small mb-0">Quality-checked every batch</p></div>
    </div>
  </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
