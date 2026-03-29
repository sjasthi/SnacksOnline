<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$item    = null;
$errors  = [];
$item_id = trim($_GET['id'] ?? '');
$action  = $item_id ? 'edit' : 'create';

if ($action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE item_id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();
    if (!$item) { set_flash("Item not found.", 'error'); redirect('/snacksonline/admin/items.php'); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_item_id = trim($_POST['item_id']     ?? '');
    $name         = trim($_POST['name']        ?? '');
    $description  = trim($_POST['description'] ?? '');
    $price_raw    = trim($_POST['price']       ?? '');
    $category     = trim($_POST['category']    ?? 'General');

    if (!$post_item_id)               $errors[] = "Item ID is required.";
    if (!$name)                       $errors[] = "Name is required.";
    if (!is_numeric($price_raw) || (float)$price_raw < 0) $errors[] = "Price must be a positive number.";

    $image = $item['image'] ?? null;

    // Handle photo upload
    if (!empty($_FILES['image']['name'])) {
        $allowed  = ['jpg','jpeg','png','gif','webp'];
        $ext      = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $mime     = $_FILES['image']['type'];
        $allowed_mime = ['image/jpeg','image/png','image/gif','image/webp'];

        if (!in_array($ext, $allowed) || !in_array($mime, $allowed_mime)) {
            $errors[] = "Photo must be JPG, PNG, GIF, or WEBP.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Photo must be under 2MB.";
        } else {
            $new_name = uniqid('img_', true) . ".$ext";
            $dest     = __DIR__ . '/../assets/uploads/' . $new_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                // Delete old image
                if ($image && file_exists(__DIR__.'/../assets/uploads/'.$image)) {
                    unlink(__DIR__.'/../assets/uploads/'.$image);
                }
                $image = $new_name;
            } else {
                $errors[] = "Failed to upload photo. Check folder permissions.";
            }
        }
    }

    if (!$errors) {
        if ($action === 'create') {
            $chk = $pdo->prepare("SELECT item_id FROM items WHERE item_id = ?");
            $chk->execute([$post_item_id]);
            if ($chk->fetch()) {
                $errors[] = "Item ID '{$post_item_id}' already exists. Use a unique ID.";
            } else {
                $pdo->prepare(
                    "INSERT INTO items (item_id,name,description,price,image,category) VALUES (?,?,?,?,?,?)"
                )->execute([$post_item_id,$name,$description,(float)$price_raw,$image,$category]);
                set_flash("'{$name}' added to the catalogue!");
                redirect('/snacksonline/admin/items.php');
            }
        } else {
            $pdo->prepare(
                "UPDATE items SET name=?,description=?,price=?,image=?,category=? WHERE item_id=?"
            )->execute([$name,$description,(float)$price_raw,$image,$category,$item_id]);
            set_flash("'{$name}' updated successfully!");
            redirect('/snacksonline/admin/items.php');
        }
    }
}

$categories = ['Chips','Nuts','Chocolate','Popcorn','Meat Snacks','Candy','Crackers','General'];
$page_title  = $action === 'edit' ? 'Edit Item' : 'Add New Item';
require_once __DIR__ . '/../includes/admin_navbar.php';
?>

<div class="mb-4">
  <a href="items.php" class="text-orange text-decoration-none small">← Back to Items</a>
  <h2 class="fw-bold mt-2"><?= $action==='edit' ? '✏️ Edit Item' : '➕ Add New Item' ?></h2>
</div>

<?php if ($errors): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card shadow-sm rounded-xl p-4" style="max-width:680px">
  <form method="POST" enctype="multipart/form-data">

    <div class="mb-3">
      <label class="form-label fw-semibold">Item ID <span class="text-danger">*</span></label>
      <input type="text" name="item_id"
             value="<?= htmlspecialchars($item['item_id'] ?? ($_POST['item_id'] ?? '')) ?>"
             class="form-control" placeholder="e.g. SNK-006"
             <?= $action==='edit' ? 'readonly' : '' ?> required>
      <?php if ($action==='edit'): ?><div class="form-text">Item ID cannot be changed.</div><?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
      <input type="text" name="name"
             value="<?= htmlspecialchars($item['name'] ?? ($_POST['name'] ?? '')) ?>"
             class="form-control" placeholder="e.g. Spicy Plantain Chips" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Description</label>
      <textarea name="description" class="form-control" rows="3"
                placeholder="Describe the taste, ingredients..."><?= htmlspecialchars($item['description'] ?? ($_POST['description'] ?? '')) ?></textarea>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-6">
        <label class="form-label fw-semibold">Price (USD) <span class="text-danger">*</span></label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input type="number" name="price" step="0.01" min="0"
                 value="<?= number_format((float)($item['price'] ?? $_POST['price'] ?? 0), 2) ?>"
                 class="form-control" required>
        </div>
      </div>
      <div class="col-6">
        <label class="form-label fw-semibold">Category</label>
        <select name="category" class="form-select">
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c ?>" <?= ($item['category'] ?? 'General') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label fw-semibold">Product Photo <?= $action==='edit' ? '<small class="text-muted">(leave blank to keep current)</small>' : '' ?></label>

      <?php if ($action==='edit' && !empty($item['image']) && file_exists(__DIR__.'/../assets/uploads/'.$item['image'])): ?>
      <div class="mb-2 d-flex align-items-center gap-3">
        <img id="photoPreview" src="/snacksonline/assets/uploads/<?= htmlspecialchars($item['image']) ?>"
             style="width:80px;height:80px;object-fit:cover;border-radius:12px" alt="Current photo">
        <small class="text-muted">Current photo. Upload new to replace.</small>
      </div>
      <?php else: ?>
      <div id="photoPlaceholder" class="mb-2" style="width:80px;height:80px;background:#fff0e6;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:2.5rem;">📷</div>
      <img id="photoPreview" src="" class="d-none mb-2" style="width:80px;height:80px;object-fit:cover;border-radius:12px" alt="">
      <?php endif; ?>

      <input type="file" name="image" id="photoInput" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
      <div class="form-text">JPG, PNG, GIF, WEBP — max 2MB</div>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-orange flex-fill fw-bold">
        <?= $action==='edit' ? '💾 Save Changes' : '✅ Add to Catalogue' ?>
      </button>
      <a href="items.php" class="btn btn-outline-secondary">Cancel</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
