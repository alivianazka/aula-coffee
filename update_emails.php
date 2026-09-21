<?php
// Reset users dengan email baru
$conn = new mysqli('localhost', 'root', '', 'aula_coffe_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Delete existing users
$conn->query("DELETE FROM users");

// Insert new users dengan email yang benar
$adminPass = password_hash('password', PASSWORD_BCRYPT);
$ownerPass = password_hash('password', PASSWORD_BCRYPT);
$karyawanPass = password_hash('password', PASSWORD_BCRYPT);

$conn->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES 
('Admin', 'admin@aulacoffee.id', '" . $adminPass . "', 'admin', NOW(), NOW()),
('Owner', 'owner@aulacoffee.id', '" . $ownerPass . "', 'owner', NOW(), NOW()),
('Karyawan 1', 'karyawan1@gmail.com', '" . $karyawanPass . "', 'karyawan', NOW(), NOW())");

echo "✅ Users updated dengan email baru!\n\n";
echo "Login credentials (UPDATED):\n";
echo "═══════════════════════════════\n";
echo "Admin:    admin@aulacoffee.id / password\n";
echo "Owner:    owner@aulacoffee.id / password\n";
echo "Karyawan: karyawan1@gmail.com / password\n";
echo "═══════════════════════════════\n";

$conn->close();
