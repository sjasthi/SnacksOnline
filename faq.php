<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$search = trim($_GET['q'] ?? '');
$sql    = "SELECT * FROM faqs WHERE 1=1";
$params = [];
if ($search) {
    $sql .= " AND (question LIKE :s1 OR answer LIKE :s2)";
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}
$sql .= " ORDER BY category, faq_id";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$all_faqs = $stmt->fetchAll();

// Group by category
$grouped = [];
foreach ($all_faqs as $faq) {
    $grouped[$faq['category']][] = $faq;
}

$page_title = 'FAQs';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="main">

<div class="container py-5">
  <div class="text-center mb-5">
    <h1 class="fw-bold">❓ Frequently Asked Questions</h1>
    <p class="text-muted">Find quick answers to common questions about SnacksOnline.</p>
    <form class="d-flex justify-content-center gap-2 mt-3" method="GET" action="faq.php">
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
             class="form-control w-auto" style="min-width:280px" placeholder="Search FAQs...">
      <button type="submit" class="btn btn-orange px-4">Search</button>
      <?php if ($search): ?><a href="faq.php" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
    </form>
  </div>

  <?php if ($grouped): ?>
  <div class="accordion" id="faqAccordion">
    <?php $i = 0; foreach ($grouped as $category => $faqs): $i++; ?>
    <div class="accordion-item border rounded-xl mb-3 overflow-hidden shadow-sm">
      <h2 class="accordion-header">
        <button class="accordion-button <?= $i > 1 ? 'collapsed' : '' ?>" type="button"
                data-bs-toggle="collapse" data-bs-target="#cat<?= $i ?>">
          <strong><?= htmlspecialchars($category) ?></strong>
          <span class="badge bg-orange ms-2"><?= count($faqs) ?></span>
        </button>
      </h2>
      <div id="cat<?= $i ?>" class="accordion-collapse collapse <?= $i === 1 ? 'show' : '' ?>">
        <div class="accordion-body p-0">
          <?php foreach ($faqs as $j => $faq): ?>
          <div class="p-4 <?= $j > 0 ? 'border-top' : '' ?>">
            <p class="fw-semibold mb-2">Q: <?= htmlspecialchars($faq['question']) ?></p>
            <p class="text-muted mb-0">A: <?= nl2br(htmlspecialchars($faq['answer'])) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php else: ?>
  <div class="text-center py-5">
    <div class="display-1">🔍</div>
    <p class="text-muted fs-5">No FAQs found<?= $search ? " for \"$search\"" : '' ?>.</p>
  </div>
  <?php endif; ?>

  <div class="text-center mt-5 p-4 bg-light rounded-xl">
    <h5 class="fw-bold">💬 Can't find your answer?</h5>
    <p class="text-muted">Our AI assistant is trained on all our FAQs and can help you instantly.</p>
    <button id="chatToggle" class="btn btn-orange px-5">Open Chatbot</button>
  </div>
</div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
