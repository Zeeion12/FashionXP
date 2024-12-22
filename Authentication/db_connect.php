<?php
   // db_connect.php
   $hostname = "localhost";
   $username = "root";  // sesuaikan dengan username database Anda
   $password = "";      // sesuaikan dengan password database Anda
   $database_name = "fashionxp"; // nama database Anda 

   $db = mysqli_connect($hostname, $username, $password, $database_name);

   // check connection
   if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
   }

   try {
      $pdo = new PDO("mysql:host=$hostname;dbname=$database_name", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
         die("Database connection failed: " . $e->getMessage());
   }
?>