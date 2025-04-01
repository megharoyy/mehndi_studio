<?php

$_servername = "localhost";
$_username = "root";
$_password = "";
$_dbname = "mehndi_studio";

try {
    // Corrected the PDO connection string
    $conn = new PDO("mysql:host=$_servername;dbname=$_dbname", $_username, $_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Corrected 'PDOMODE_EXCEEPTTON' to 'PDO::ERRMODE_EXCEPTION'
   
} catch (PDOException $e) { // Corrected 'PDOMODE_EXCEEPTTON' to 'PDOException'
    http_response_code(500);
  
    exit();
}
?>