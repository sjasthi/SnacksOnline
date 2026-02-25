<?php
// Shared navbar — included at the top of every public page
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title ?? 'SnacksOnline') ?> 🛒</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Satisfy:wght@400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/snacksonline/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/snacksonline/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/snacksonline/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/snacksonline/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Delicious Template CSS -->
  <link href="/snacksonline/assets/css/delicious.css" rel="stylesheet">
  
  <!-- Custom CSS (loads last to override) -->
  <link href="/snacksonline/assets/css/style.css" rel="stylesheet">
</head>
<body class="index-page">

<!-- ── Navigation ─────────────────────────────────────────── -->
<header id="header" class="header fixed-top">
  <div class="topbar d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:support@snacksonline.com">support@snacksonline.com</a></i>
        <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 555 SNACKS</span></i>
      </div>
      <div class="languages d-none d-md-flex align-items-center">
        <ul>
          <?php if (is_logged_in()): ?>
            <li>Welcome, <?= htmlspecialchars(current_user_name()) ?>!</li>
          <?php else: ?>
            <li><a href="/snacksonline/login.php">Log In</a></li>
            <li><a href="/snacksonline/register.php">Sign Up</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="branding d-flex align-items-center">
    <div class="container position-relative d-flex align-items-center justify-content-between">
      <a href="/snacksonline/index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">🛒 Snacks<span class="text-orange">Online</span></h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/snacksonline/index.php" class="active"><i class="bi bi-shop me-1"></i>Shop<br></a></li>
          <li><a href="/snacksonline/faq.php"><i class="bi bi-question-circle me-1"></i>FAQs</a></li>
          <li><a href="/snacksonline/about.php"><i class="bi bi-info-circle me-1"></i>About</a></li>
          
          <?php if (is_logged_in()): ?>
            <?php if (is_admin()): ?>
              <li><a href="/snacksonline/admin/dashboard.php"><i class="bi bi-gear me-1"></i>Admin Panel</a></li>
            <?php else: ?>
              <li><a href="/snacksonline/orders.php"><i class="bi bi-box me-1"></i>My Orders</a></li>
            <?php endif; ?>
            <li><a href="/snacksonline/logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
          <?php endif; ?>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="/snacksonline/cart.php">
        <i class="bi bi-cart3"></i> Cart
        <?php if (cart_count() > 0): ?>
          <span class="badge bg-danger ms-1"><?= cart_count() ?></span>
        <?php endif; ?>
      </a>

    </div>
  </div>

</header>

<!-- ── Flash Message ───────────────────────────────────────── -->
<?php if ($flash): ?>
<div class="container mt-3">
  <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($flash['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php endif; ?>
