CREATE DATABASE IF NOT EXISTS healthcare_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE healthcare_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('patient','doctor','admin') NOT NULL DEFAULT 'patient',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  doctor_name VARCHAR(120) NOT NULL,
  appointment_date DATE NOT NULL,
  reason VARCHAR(1000) NULL,
  status ENUM('Pending','Approved','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_appointment_email (email),
  INDEX idx_appointment_date_doctor (appointment_date, doctor_name)
);

-- Create a demo login in PHP using password_hash(), then insert its hash:
-- INSERT INTO users (name,email,password_hash,role)
-- VALUES ('Demo Patient','patient@example.com','<PASTE_PASSWORD_HASH_HERE>','patient');
