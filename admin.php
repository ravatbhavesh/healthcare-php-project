<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Access denied. Admin only.');
}

require __DIR__ . '/config/database.php';

$totalPatients = (int) $pdo->query(
    "SELECT COUNT(*) FROM users WHERE role = 'patient'"
)->fetchColumn();

$totalAppointments = (int) $pdo->query(
    "SELECT COUNT(*) FROM appointments"
)->fetchColumn();

$stmt = $pdo->query(
    'SELECT patient_name, email, doctor_name, appointment_date, status
     FROM appointments ORDER BY appointment_date DESC'
);
$appointments = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard | CarePlus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">CarePlus Admin Dashboard</h1>
            <p class="text-secondary mb-0">
                Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
            </p>
        </div>
        <a href="logout.php" class="btn btn-outline-secondary">Log out</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h2 class="h6 text-secondary">Total Patients</h2>
                <p class="display-5 mb-0"><?= $totalPatients ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h2 class="h6 text-secondary">Total Appointments</h2>
                <p class="display-5 mb-0"><?= $totalAppointments ?></p>
            </div>
        </div>
    </div>

    <h2 class="h5 mb-3">All Appointments</h2>
    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Email</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$appointments): ?>
                <tr>
                    <td colspan="5" class="text-center py-4">No appointments found.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($appointments as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['patient_name']) ?></td>
                    <td><?= htmlspecialchars($a['email']) ?></td>
                    <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($a['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($a['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <p class="small text-secondary mt-3">
        Educational demo only. Do not use real patient data.
    </p>
</div>
</body>
</html>
