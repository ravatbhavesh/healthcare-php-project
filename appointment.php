<?php
session_start();
require __DIR__ . '/config/database.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $doctor = trim($_POST['doctor'] ?? '');
    $date = $_POST['appointment_date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $doctor === '' || $date === '') {
        $message = 'Please complete all required fields with a valid email.';
    } elseif ($date < date('Y-m-d')) {
        $message = 'Please select today or a future date.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO appointments (patient_name, email, doctor_name, appointment_date, reason) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $doctor, $date, $reason]);
        $message = 'Appointment request submitted. Status: Pending.';
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Book Appointment | CarePlus</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head>
<body><div class="container py-5"><a href="index.php" class="text-decoration-none">← CarePlus Home</a><div class="form-card mx-auto mt-4"><h1 class="h3 mb-3">Book an appointment</h1>
<?php if ($message): ?><div class="alert alert-info"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<form method="post">
<label class="form-label" for="name">Full name *</label><input class="form-control mb-3" id="name" name="name" required maxlength="120">
<label class="form-label" for="email">Email *</label><input class="form-control mb-3" id="email" type="email" name="email" required maxlength="190">
<label class="form-label" for="doctor">Doctor / department *</label><select class="form-select mb-3" id="doctor" name="doctor" required><option value="">Choose one</option><option>General Physician</option><option>Cardiology</option><option>Dermatology</option><option>Pediatrics</option></select>
<label class="form-label" for="date">Preferred date *</label><input class="form-control mb-3" id="date" type="date" name="appointment_date" min="<?= date('Y-m-d') ?>" required>
<label class="form-label" for="reason">Reason (optional)</label><textarea class="form-control mb-3" id="reason" name="reason" rows="3" maxlength="1000"></textarea>
<button class="btn btn-primary w-100" type="submit">Submit request</button></form><p class="small text-secondary mt-3 mb-0">Demo only. Do not enter sensitive or real medical information.</p></div></div></body></html>
