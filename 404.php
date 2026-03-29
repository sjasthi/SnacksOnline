<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
http_response_code(404);
$page_title = 'Page Not Found';
require_once __DIR__ . '/includes/navbar.php';
?>
<div class="container text-center py-5 my-5">
  <div class="display-1 mb-3">🍿</div>
  <h1 class="display-2 fw-bold text-orange">404</h1>
  <p class="fs-4 text-muted">Oops — that snack doesn't exist.</p>
  <a href="index.php" class="btn btn-orange btn-lg px-5 mt-3">Back to Shop</a>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
