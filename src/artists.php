<?php
include'config.php';

$method = $_SERVER[REQUEST_METHOD];


switch ($method) {
    case 'GET':
        $stmt=$conn->prepare("SELECT*FROM artists");
        $stmt->execute();
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($artists);
        break;
    
    case 'POST':
        $stmt=$conn->prepare("SELECT*FROM artists");
        $stmt->execute();
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($artists);
        
        break;
}
?>