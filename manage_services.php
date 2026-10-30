<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Access denied. Admin account required.');
}

require __DIR__ . '/config/database.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$editService = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        exit('Invalid request. Please reload the page.');
    }

    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM services WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: manage_services.php');
        exit;
    }

    $icon = trim($_POST['icon'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($icon === '' || $title === '' || $description === '') {
        $error = 'Please fill in all fields.';
    } elseif (mb_strlen($icon) > 30 || mb_strlen($title) > 100 || mb_strlen($description) > 255) {
        $error = 'One or more fields are too long.';
    } elseif ($action === 'add') {
        $stmt = $pdo->prepare(
            'INSERT INTO services (icon, title, description) VALUES (?, ?, ?)'
        );
        $stmt->execute([$icon, $title, $description]);
        header('Location: manage_services.php');
        exit;
    } elseif ($action === 'edit' && $id > 0) {
        $stmt = $pdo->prepare(
            'UPDATE services SET icon = ?, title = ?, description = ? WHERE id = ?'
        );
        $stmt->execute([$icon, $title, $description, $id]);
        header('Location: manage_services.php');
        exit;
    }
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $editService = $stmt->fetch();
}

$services = $pdo->query('SELECT * FROM services ORDER BY id DESC')->fetchAll();

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Services | CarePlus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="page-bg">
<nav class="navbar navbar-dark bg-primary">
<div class="container">
<a class="navbar-brand" href="admin.php">CarePlus Admin</a>
<a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
</div>
</nav>

<main class="container py-4">
<h1 class="h3 mb-3">Manage Our Services</h1>
<a href="admin.php" class="btn btn-outline-secondary btn-sm mb-4">Back to Admin Dashboard</a>

<?php if ($error): ?>
<div class="alert alert-danger"><?= h($error) ?></div>
<?php endif; ?>

<div class="form-card wide-card mx-auto mb-4">
<h2 class="h5"><?= $editService ? 'Edit Service' : 'Add New Service' ?></h2>
<form method="post">
<input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
<input type="hidden" name="action" value="<?= $editService ? 'edit' : 'add' ?>">
<?php if ($editService): ?>
<input type="hidden" name="id" value="<?= (int)$editService['id'] ?>">
<?php endif; ?>

<label class="form-label" for="icon">Icon (emoji)</label>
<input class="form-control mb-3" id="icon" name="icon" maxlength="30"
value="<?= h($editService['icon'] ?? '') ?>" placeholder="🩺" required>

<label class="form-label" for="title">Service Name</label>
<input class="form-control mb-3" id="title" name="title" maxlength="100"
value="<?= h($editService['title'] ?? '') ?>" required>

<label class="form-label" for="description">Description</label>
<textarea class="form-control mb-3" id="description" name="description"
maxlength="255" rows="3" required><?= h($editService['description'] ?? '') ?></textarea>

<button class="btn btn-primary"><?= $editService ? 'Save Changes' : 'Add Service' ?></button>
<?php if ($editService): ?>
<a href="manage_services.php" class="btn btn-outline-secondary">Cancel</a>
<?php endif; ?>
</form>
</div>

<h2 class="h5 mb-3">Existing Services</h2>
<div class="row g-3">
<?php foreach ($services as $service): ?>
<div class="col-md-6 col-lg-4">
<div class="service-card h-100">
<div class="service-icon"><?= h($service['icon']) ?></div>
<h4><?= h($service['title']) ?></h4>
<p class="text-secondary"><?= h($service['description']) ?></p>
<a class="btn btn-sm btn-outline-primary"
href="manage_services.php?edit=<?= (int)$service['id'] ?>">Edit</a>

<form method="post" class="d-inline"
onsubmit="return confirm('Delete this service?');">
<input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int)$service['id'] ?>">
<button class="btn btn-sm btn-outline-danger">Delete</button>
</form>
</div>
</div>
<?php endforeach; ?>
</div>
</main>
</body>
</html>
