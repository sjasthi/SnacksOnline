<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect('/snacksonline/index.php');

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']             ?? '');
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password']         ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if (!$name)                               $errors[] = "Full name is required.";
    if (!$email || !str_contains($email,'@')) $errors[] = "A valid email address is required.";
    if (strlen($password) < 8)                $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm)               $errors[] = "Passwords do not match.";

    if (!$errors) {
        $chk = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $errors[] = "An account with that email already exists. Please log in.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins  = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $ins->execute([$name, $email, $hash]);
            $user_id = $pdo->lastInsertId();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['name']    = $name;
            $_SESSION['email']   = $email;
            $_SESSION['role']    = 'customer';
            set_flash("Account created! Welcome to SnacksOnline 🎉");
            redirect('/snacksonline/index.php');
        }
    }
}

$page_title = 'Create Account';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="auth-card">
  <div class="text-center mb-4">
    <div class="fs-1">🎉</div>
    <h2 class="fw-bold mt-2">Create Your Account</h2>
    <p class="text-muted">Track orders and check out faster every time.</p>
  </div>

  <?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
  <?php endif; ?>

  <form method="POST" action="register.php">
    <div class="mb-3">
      <label class="form-label fw-semibold">Full Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($name) ?>"
             class="form-control" placeholder="Jane Smith" required autofocus>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Email Address</label>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"
             class="form-control" placeholder="jane@example.com" required>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Password <small class="text-muted">(min 8 characters)</small></label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="8">
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold">Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn btn-orange w-100 fw-bold">Create Account</button>
  </form>

  <hr class="my-4">
  <p class="text-center small text-muted">
    Already have an account? <a href="login.php" class="text-orange fw-semibold text-decoration-none">Log in</a>
  </p>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
