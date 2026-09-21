<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'aula_coffe_db');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Insert Kategori
$conn->query("INSERT INTO barang_kategori (nama, deskripsi, created_at, updated_at) VALUES 
('Bar', 'Minuman Bar - Alcohol & Non-Alcohol Beverages', NOW(), NOW()),
('Kitchen', 'Makanan & Persiapan - Food Items & Ingredients', NOW(), NOW()),
('Peralatan', 'Peralatan & Supplies - Equipment & Supplies', NOW(), NOW())");

echo "✓ Kategori created\n";

// Get kategori IDs
$result = $conn->query("SELECT id, nama FROM barang_kategori");
$kategoris = [];
while ($row = $result->fetch_assoc()) {
    $kategoris[$row['nama']] = $row['id'];
}

// Insert Barang
$barangData = [
    // Bar
    ['kategori_id' => $kategoris['Bar'], 'nama' => 'Kopi Arabika', 'stok_awal' => 50, 'qty' => 50, 'unit' => 'pack'],
    ['kategori_id' => $kategoris['Bar'], 'nama' => 'Teh Premium', 'stok_awal' => 30, 'qty' => 30, 'unit' => 'pack'],
    ['kategori_id' => $kategoris['Bar'], 'nama' => 'Gula Pasir', 'stok_awal' => 25, 'qty' => 25, 'unit' => 'kg'],
    // Kitchen
    ['kategori_id' => $kategoris['Kitchen'], 'nama' => 'Roti Tawar', 'stok_awal' => 20, 'qty' => 20, 'unit' => 'loaf'],
    ['kategori_id' => $kategoris['Kitchen'], 'nama' => 'Butter', 'stok_awal' => 15, 'qty' => 15, 'unit' => 'pack'],
    ['kategori_id' => $kategoris['Kitchen'], 'nama' => 'Susu Cair', 'stok_awal' => 18, 'qty' => 18, 'unit' => 'liter'],
    // Peralatan
    ['kategori_id' => $kategoris['Peralatan'], 'nama' => 'Gelas Kopi', 'stok_awal' => 100, 'qty' => 100, 'unit' => 'pcs'],
    ['kategori_id' => $kategoris['Peralatan'], 'nama' => 'Sendok Kopi', 'stok_awal' => 80, 'qty' => 80, 'unit' => 'pcs'],
    ['kategori_id' => $kategoris['Peralatan'], 'nama' => 'Serbet', 'stok_awal' => 500, 'qty' => 500, 'unit' => 'pcs'],
];

foreach ($barangData as $barang) {
    $sql = "INSERT INTO barang (kategori_id, nama, stok_awal, stok_akhir, qty, stok_opname, unit, created_at, updated_at) VALUES 
    (" . $barang['kategori_id'] . ", '" . $conn->real_escape_string($barang['nama']) . "', " . $barang['stok_awal'] . ", " . $barang['stok_awal'] . ", " . $barang['qty'] . ", 100, '" . $barang['unit'] . "', NOW(), NOW())";
    $conn->query($sql);
}

echo "✓ Barang created\n";

// Insert Users
$adminPass = password_hash('password', PASSWORD_BCRYPT);
$ownerPass = password_hash('password', PASSWORD_BCRYPT);
$karyawanPass = password_hash('password', PASSWORD_BCRYPT);

$conn->query("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES 
('Admin', 'admin@aulacoffee.id', '" . $adminPass . "', 'admin', NOW(), NOW()),
('Owner', 'owner@aulacoffee.id', '" . $ownerPass . "', 'owner', NOW(), NOW()),
('Karyawan 1', 'karyawan1@gmail.com', '" . $karyawanPass . "', 'karyawan', NOW(), NOW())");

echo "✓ Users created\n";
echo "\n✅ Database seeding completed!\n";
echo "\nLogin credentials:\n";
echo "Admin: admin@aulacoffee.id / password\n";
echo "Owner: owner@aulacoffee.id / password\n";
echo "Karyawan: karyawan1@gmail.com / password\n";

$conn->close();
