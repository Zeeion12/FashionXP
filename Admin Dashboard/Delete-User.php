<?php
session_start();
include '../Authentication/db_connect.php';

// Pastikan pengguna memiliki role admin
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== 'admin') {
    header("Location: ../Authentication/sign-in.php");
    exit();
}

// Hapus user berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Pastikan ID pengguna valid
    if ($user_id > 0) {
        // Query untuk menghapus user
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete user.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid user ID.";
    }

    header("Location: Admin-Users.php");
    exit();
}