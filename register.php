<?php
session_start();
require __DIR__ . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter a valid name and email. Password must be at least 8 characters.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'This email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'patient')");
            $stmt->execute([$name, $email, $hash]);
            $success = 'Registration successful! You can now log in.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register | CarePlus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
<a href="index.php" class="text-decoration-none">← CarePlus Home</a>
<div class="form-card mx-auto mt-4">
<h1 class="h3 mb-3">Create Account</h1>
<?php if ($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<form method="post">
<label class="form-label" for="name">Full Name</label>
<input class="form-control mb-3" id="name" name="name" required>
<label class="form-label" for="email">Email</label>
<input class="form-control mb-3" id="email" name="email" type="email" required>
<label class="form-label" for="password">Password (at least 8 characters)</label>
<input class="form-control mb-3" id="password" name="password" type="password" minlength="8" required>
<button class="btn btn-primary w-100" type="submit">Register</button>
</form>
<p class="mt-3 mb-0">Already registered? <a href="login.php">Login here</a></p>
</div>
</div>
</body>
</html>
