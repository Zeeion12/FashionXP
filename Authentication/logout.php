<?php
session_start();
session_unset(); // Menghapus semua sesi
session_destroy(); // Menghancurkan sesi
header("Location: sign-in.php"); // Arahkan kembali ke halaman login
exit();
?>