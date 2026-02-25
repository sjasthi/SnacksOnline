<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY category, faq_id")->fetchAll();

$page_title = 'Manage FAQs';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="fw-bold mb-0">❓ Manage FAQs</h2>
    <small class="text-muted"><?= count($faqs) ?> FAQ<?= count($faqs)!=1?'s':'' ?> in knowledge base</small>
  </div>
  <a href="faq_form.php" class="btn btn-orange">+ Add New FAQ</a>
</div>

<div class="card shadow-sm rounded-xl overflow-hidden">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead class="bg-dark-brand text-white">
        <tr>
          <th>#</th>
          <th>Category</th>
          <th>Question</th>
          <th>Answer (preview)</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($faqs as $faq): ?>
        <tr>
          <td class="text-muted small"><?= $faq['faq_id'] ?></td>
          <td><span class="category-badge"><?= htmlspecialchars($faq['category']) ?></span></td>
          <td class="fw-semibold"><?= htmlspecialchars($faq['question']) ?></td>
          <td class="text-muted small"><?= htmlspecialchars(substr($faq['answer'], 0, 80)) ?>...</td>
          <td class="text-center">
            <a href="faq_form.php?id=<?= $faq['faq_id'] ?>" class="btn btn-sm btn-outline-primary me-1">✏️ Edit</a>
            <a href="faq_delete.php?id=<?= $faq['faq_id'] ?>" class="btn btn-sm btn-outline-danger btn-delete">🗑️ Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4 p-3 bg-light rounded-xl small text-muted">
  💡 <strong>Chatbot note:</strong> Every FAQ entry you add here becomes part of the chatbot's knowledge base. The chatbot only answers using this data — it cannot hallucinate or use external information.
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
