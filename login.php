<?php
session_start();
require __DIR__ . '/config/database.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid email or password.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login | CarePlus</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head>
<body><div class="container py-5"><a href="index.php" class="text-decoration-none">← CarePlus Home</a><div class="form-card mx-auto mt-4"><h1 class="h3 mb-3">Sign in</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post"><label class="form-label" for="email">Email</label><input class="form-control mb-3" id="email" name="email" type="email" required>
<label class="form-label" for="password">Password</label><input class="form-control mb-3" id="password" name="password" type="password" required><button class="btn btn-primary w-100">Login</button></form>
<p class="small text-secondary mt-3">Create demo accounts by inserting users with password hashes into the database. See README.md.</p></div></div></body></html>
