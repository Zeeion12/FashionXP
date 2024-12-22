<?php
session_start();
include "../Authentication/db_connect.php"; // Pastikan file koneksi database sudah benar

// Cek apakah form login sudah disubmit
if (isset($_POST['login'])) {
    // Ambil data username dan password dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data pengguna berdasarkan username
    $query = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Jika username ditemukan
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $dbUsername, $dbPassword, $role);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $dbPassword)) {
            // Password valid, simpan informasi pengguna dalam session
            $_SESSION['username'] = $dbUsername;
            $_SESSION['role'] = $role; // Simpan role pengguna (admin atau user)
            $_SESSION['user_id'] = $id;

            // Redirect ke halaman sesuai dengan role
            if ($role == 'admin') {
                header("Location: ../Admin Dashboard/admin-users.php"); // Halaman dashboard admin
            } else {
                header("Location: ../Dashboard/Home/home.php"); // Halaman dashboard user
            }
            exit();
        } else {
            // Jika password salah
            $loginMessage = "Username atau password salah.";
        }
    } else {
        // Jika username tidak ditemukan
        $loginMessage = "Username tidak ditemukan.";
    }
    $stmt->close();
    $db->close();
}
?>