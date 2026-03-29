<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';
$page_title = 'About Us';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<section id="about-hero" class="hero section dark-background">
  <div class="container">
    <h1 class="display-5 fw-bold mb-3">About <span class="text-orange">SnacksOnline</span></h1>
    <p class="lead text-white-50">Bringing the world's best snacks straight to your door.</p>
  </div>
</section>

<div class="container py-5">

  <div class="row align-items-center g-5 mb-5">
    <div class="col-md-6">
      <h2 class="fw-bold mb-3">Our Story</h2>
      <p class="text-muted">SnacksOnline was founded with a simple mission: make premium, globally-sourced snacks accessible to everyone in United States. Whether you're looking for spicy plantain chips, honey-roasted nuts, or dark chocolate almonds, we hand-pick every item for quality and flavour.</p>
      <p class="text-muted">We support both guest and registered ordering — because buying a great snack shouldn't require signing up for anything.</p>
    </div>
    <div class="col-md-6 text-center">
      <div style="font-size:8rem;">🌍🍿</div>
    </div>
  </div>

  <div class="row g-4 text-center mb-5">
    <div class="col-md-4">
      <div class="card p-4 shadow-sm rounded-xl h-100">
        <div class="fs-2 mb-3">🚚</div>
        <h5 class="fw-bold">Fast Delivery</h5>
        <p class="text-muted small">Orders dispatched within 24 hours. Delivered within 3–5 business days.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 shadow-sm rounded-xl h-100">
        <div class="fs-2 mb-3">🛡️</div>
        <h5 class="fw-bold">Safe & Simple Checkout</h5>
        <p class="text-muted small">No account required for guests. Your data is handled securely.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 shadow-sm rounded-xl h-100">
        <div class="fs-2 mb-3">⭐</div>
        <h5 class="fw-bold">Hand-Picked Quality</h5>
        <p class="text-muted small">Every item is selected and checked before it reaches our catalogue.</p>
      </div>
    </div>
  </div>

  <div class="bg-light rounded-xl p-5 text-center">
    <h3 class="fw-bold mb-4">Contact Us</h3>
    <div class="row justify-content-center g-4">
      <div class="col-md-3">
        <div class="fs-3 mb-2">📧</div>
        <p class="fw-semibold mb-1">Email</p>
        <p class="text-muted small">support@snacksonline.com</p>
      </div>
      <div class="col-md-3">
        <div class="fs-3 mb-2">📱</div>
        <p class="fw-semibold mb-1">Phone</p>
        <p class="text-muted small">+1(612) 639-4867</p>
      </div>
      <div class="col-md-3">
        <div class="fs-3 mb-2">📍</div>
        <p class="fw-semibold mb-1">Location</p>
        <p class="text-muted small">Minneapolis, USA</p>
      </div>
    </div>
  </div>

  <div class="mt-5 pt-4 border-top text-center text-muted small">
    <p>SnacksOnline is an ICS499 Capstone Project — Metro State University — Instructor: Siva Jasthi</p>
    <p>Built with PHP · MySQL · Bootstrap 5 · jQuery</p>
  </div>

</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
