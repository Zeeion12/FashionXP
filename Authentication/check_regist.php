<?php
session_start();
include "db_connect.php";

$registerMessage = '';

// Proses Registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    if ($password !== $confirmPassword) {
        $registerMessage = "Password tidak cocok.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $registerMessage = "Username atau email sudah terdaftar.";
        } else {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Registrasi berhasil, arahkan ke halaman sign-in.php
                header("Location: sign-in.php");
                exit(); // Pastikan script berhenti setelah redirect
            } else {
                $registerMessage = "Registrasi gagal. Silakan coba lagi.";
            }
        }

        $stmt->close();
    }
}
?>