<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CarePlus Healthcare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
 <div class="container"><a class="navbar-brand fw-bold text-primary" href="index.php">CarePlus</a>
 <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
 <div id="nav" class="collapse navbar-collapse"><div class="navbar-nav ms-auto">
 <a class="nav-link" href="#services">Services</a><a class="nav-link" href="#about">About</a>
 <a class="nav-link" href="appointment.php">Book Appointment</a><a class="nav-link" href="login.php">Login</a><a class="nav-link" href="register.php">Register</a>
 </div></div></div>
</nav>
<header class="hero py-5"><div class="container py-5"><div class="row align-items-center g-4">
 <div class="col-lg-7"><span class="badge text-bg-success mb-3">Healthcare made simpler</span>
 <h1 class="display-4 fw-bold">Your health, <span class="text-primary">our priority.</span></h1>
 <p class="lead text-secondary">Find care, request an appointment, and keep your healthcare journey organized.</p>
 <a class="btn btn-primary btn-lg me-2" href="appointment.php">Book an appointment</a><a class="btn btn-outline-primary btn-lg" href="#services">Our services</a></div>
 <div class="col-lg-5"><div class="hero-card"><div class="medical-cross">+</div><h3>Care you can trust</h3><p class="mb-0">A demo healthcare portal for learning and project development.</p></div></div>
</div></div></header>
<section id="services" class="py-5"><div class="container"><div class="text-center mb-4"><h2>Our Services</h2><p class="text-secondary">Support for every step of your care.</p></div>
 <div class="row g-4">
 <?php foreach ([['🩺','General Consultation','Request an appointment with a doctor.'],['📅','Appointment Booking','Submit and track appointment requests.'],['📋','Patient Records','Keep authorized healthcare information organized.']] as $s): ?>
 <div class="col-md-4"><div class="service-card h-100"><div class="service-icon"><?= $s[0] ?></div><h4><?= htmlspecialchars($s[1]) ?></h4><p class="text-secondary mb-0"><?= htmlspecialchars($s[2]) ?></p></div></div>
 <?php endforeach; ?>
 </div></div></section>
<section id="about" class="py-5 bg-light"><div class="container text-center"><h2>About CarePlus</h2><p class="text-secondary mx-auto max-text">CarePlus is a sample healthcare management website built with PHP and MySQL. It is intended for educational use and is not a real medical service.</p></div></section>
<footer class="py-4 text-center text-secondary"><div class="container">© <?= date('Y') ?> CarePlus Healthcare · Educational demo</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
