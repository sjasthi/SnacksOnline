<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$faq    = null;
$errors = [];
$faq_id = (int)($_GET['id'] ?? 0);
$action = $faq_id ? 'edit' : 'create';

if ($action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE faq_id = ?");
    $stmt->execute([$faq_id]);
    $faq  = $stmt->fetch();
    if (!$faq) { set_flash("FAQ not found.", 'error'); redirect('/snacksonline/admin/faqs.php'); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $question = trim($_POST['question'] ?? '');
    $answer   = trim($_POST['answer']   ?? '');

    if (!$category) $errors[] = "Category is required.";
    if (!$question) $errors[] = "Question is required.";
    if (!$answer)   $errors[] = "Answer is required.";

    if (!$errors) {
        if ($action === 'create') {
            $pdo->prepare("INSERT INTO faqs (category, question, answer) VALUES (?,?,?)")
                ->execute([$category, $question, $answer]);
            set_flash("FAQ added to knowledge base!");
        } else {
            $pdo->prepare("UPDATE faqs SET category=?, question=?, answer=? WHERE faq_id=?")
                ->execute([$category, $question, $answer, $faq_id]);
            set_flash("FAQ updated successfully!");
        }
        redirect('/snacksonline/admin/faqs.php');
    }
}

$categories = ['Ordering', 'Payments', 'Shipping', 'Returns', 'Technical'];
$page_title  = $action === 'edit' ? 'Edit FAQ' : 'Add New FAQ';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="mb-4">
  <a href="faqs.php" class="text-orange text-decoration-none small">← Back to FAQs</a>
  <h2 class="fw-bold mt-2"><?= $action==='edit' ? '✏️ Edit FAQ' : '➕ Add New FAQ' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger">
  <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<div class="card shadow-sm rounded-xl p-4" style="max-width:640px">
  <form method="POST">

    <div class="mb-3">
      <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
      <select name="category" class="form-select" required>
        <option value="">— Select category —</option>
        <?php foreach ($categories as $c): ?>
          <option value="<?= $c ?>" <?= ($faq['category'] ?? $_POST['category'] ?? '') === $c ? 'selected' : '' ?>>
            <?= $c ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Question <span class="text-danger">*</span></label>
      <input type="text" name="question" class="form-control"
             value="<?= htmlspecialchars($faq['question'] ?? ($_POST['question'] ?? '')) ?>"
             placeholder="e.g. How do I place an order?" required>
    </div>

    <div class="mb-4">
      <label class="form-label fw-semibold">Answer <span class="text-danger">*</span></label>
      <textarea name="answer" class="form-control" rows="5"
                placeholder="Provide a clear, complete answer..."
                required><?= htmlspecialchars($faq['answer'] ?? ($_POST['answer'] ?? '')) ?></textarea>
      <div class="form-text">💡 This answer will be used by the chatbot to respond to customer questions.</div>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-orange flex-fill fw-bold">
        <?= $action==='edit' ? '💾 Save Changes' : '✅ Add FAQ' ?>
      </button>
      <a href="faqs.php" class="btn btn-outline-secondary">Cancel</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
