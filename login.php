<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect('/snacksonline/index.php');

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = strtolower(trim($_POST['email']    ?? ''));
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $errors[] = "Email and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Incorrect email or password. Please try again.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            set_flash("Welcome back, {$user['name']}! 👋");

            $next = $_GET['next'] ?? ($_POST['next'] ?? '');
            redirect($next ?: ($user['role'] === 'admin' ? '/snacksonline/admin/dashboard.php' : '/snacksonline/index.php'));
        }
    }
}

$page_title = 'Log In';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="auth-card">
  <div class="text-center mb-4">
    <div class="fs-1">👋</div>
    <h2 class="fw-bold mt-2">Welcome Back</h2>
    <p class="text-muted">Log in to view your order history.</p>
  </div>

  <?php if ($errors): ?>
  <div class="alert alert-danger">
    <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <input type="hidden" name="next" value="<?= htmlspecialchars($_GET['next'] ?? '') ?>">
    <div class="mb-3">
      <label class="form-label fw-semibold">Email Address</label>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
             class="form-control" placeholder="you@example.com" required autofocus>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold">Password</label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn btn-orange w-100 fw-bold">Log In</button>
  </form>

  <hr class="my-4">
  <div class="text-center small text-muted">
    <p>No account? <a href="register.php" class="text-orange fw-semibold text-decoration-none">Create one free</a></p>
    <p>Just browsing? <a href="checkout.php" class="text-orange fw-semibold text-decoration-none">Order as guest</a></p>
  </div>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
