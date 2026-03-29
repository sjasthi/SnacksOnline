<?php
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'Admin') ?> — SnacksOnline Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="/snacksonline/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ── Admin Top Navbar ────────────────────────────────────── -->
<nav class="navbar navbar-dark bg-dark-brand shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="/snacksonline/admin/dashboard.php">
      ⚙️ SnacksOnline <span class="text-orange">Admin</span>
    </a>
    <div class="d-flex align-items-center gap-3">
      <a href="/snacksonline/index.php" class="text-white text-decoration-none small">
        <i class="fa fa-store me-1"></i>View Store
      </a>
      <a href="/snacksonline/logout.php" class="text-danger text-decoration-none small">
        <i class="fa fa-sign-out me-1"></i>Logout
      </a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <!-- ── Sidebar ─────────────────────────────────────────── -->
    <nav class="col-md-2 d-md-block bg-white shadow-sm sidebar py-4 min-vh-100">
      <ul class="nav flex-column gap-1">
        <li class="nav-item">
          <a href="/snacksonline/admin/dashboard.php"
             class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'dashboard') ? 'active text-orange fw-bold' : 'text-dark' ?>">
            <i class="fa fa-gauge me-2"></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="/snacksonline/admin/items.php"
             class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'item') ? 'active text-orange fw-bold' : 'text-dark' ?>">
            <i class="fa fa-box-open me-2"></i>Items
          </a>
        </li>
        <li class="nav-item">
          <a href="/snacksonline/admin/orders.php"
             class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'order') ? 'active text-orange fw-bold' : 'text-dark' ?>">
            <i class="fa fa-clipboard-list me-2"></i>Orders
          </a>
        </li>
        <li class="nav-item">
          <a href="/snacksonline/admin/faqs.php"
             class="nav-link <?= str_contains($_SERVER['PHP_SELF'], 'faq') ? 'active text-orange fw-bold' : 'text-dark' ?>">
            <i class="fa fa-circle-question me-2"></i>FAQs
          </a>
        </li>
      </ul>
    </nav>

    <!-- ── Main Content ────────────────────────────────────── -->
    <main class="col-md-10 ms-sm-auto px-4 py-4">

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
