<?php
include "db_connect.php"; // Pastikan koneksi database sudah benar

// Tentukan username dan password admin
$username = 'admin'; // Username admin
$password = 'admin123'; // Password admin
$role = 'admin'; // Menentukan role sebagai admin

// Mengenkripsi password menggunakan password_hash() dengan algoritma default (BCRYPT)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Query untuk memasukkan data admin ke tabel users
$query = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
$stmt = $db->prepare($query);
$email = 'admin@example.com'; // Email admin yang sesuai

// Bind parameter dan eksekusi query
$stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);
if ($stmt->execute()) {
    echo "Admin berhasil ditambahkan.";
} else {
    echo "Gagal menambahkan admin: " . $stmt->error;
}

$stmt->close();
$db->close();
?>