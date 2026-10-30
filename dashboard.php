<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require __DIR__ . '/config/database.php';
$stmt = $pdo->prepare('SELECT patient_name, doctor_name, appointment_date, status FROM appointments WHERE email = (SELECT email FROM users WHERE id = ?) ORDER BY appointment_date DESC');
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Dashboard | CarePlus</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head>
<body><div class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1><p class="text-secondary mb-0">Role: <?= htmlspecialchars($_SESSION['role']) ?></p></div><a href="logout.php" class="btn btn-outline-secondary">Log out</a></div>
<div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5">Appointments</h2><a href="appointment.php" class="btn btn-primary">New appointment</a></div>
<div class="table-responsive bg-white rounded shadow-sm"><table class="table mb-0"><thead><tr><th>Patient</th><th>Doctor / Department</th><th>Date</th><th>Status</th></tr></thead><tbody>
<?php if (!$appointments): ?><tr><td colspan="4" class="text-center text-secondary py-4">No appointments found for this account.</td></tr><?php endif; ?>
<?php foreach ($appointments as $a): ?><tr><td><?= htmlspecialchars($a['patient_name']) ?></td><td><?= htmlspecialchars($a['doctor_name']) ?></td><td><?= htmlspecialchars($a['appointment_date']) ?></td><td><?= htmlspecialchars($a['status']) ?></td></tr><?php endforeach; ?>
</tbody></table></div><p class="small text-secondary mt-3">Educational starter project. This dashboard is not a complete clinical system.</p></div></body></html>
