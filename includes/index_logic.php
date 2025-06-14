<?php
include "config.php";

$artists = [];
$query = "SELECT * FROM artists";
$result = $conn->query($query);
if ($result) {
    $artists = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $artistMessage = "Error fetching artists: " . $conn->error;
}

$designs = [];
$query = "SELECT * FROM designs";
$result = $conn->query($query);

if ($result) {
    $designs = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $designMessage = "Error fetching designs: " . $conn->error;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];
    $artist_id = (int)$_POST['artist'];
    $design_type = $_POST['design_type'];

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        die("Appointment date cannot be in the past.");
    }

    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO bookings (full_name, email, appointment_date, appointment_time, artist_id, design_type)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssis",
        $full_name,
        $email,
        $appointment_date,
        $appointment_time,
        $artist_id,
        $design_type
    );

    
    // Delete artist
    if (isset($_POST['deleteArtist'])) {
        $artistId = (int)$_POST['artist_id'];
        $stmt = $conn->prepare("DELETE FROM artists WHERE id = ?");
        $stmt->bind_param("i", $artistId);
        if ($stmt->execute()) {
            $artistMessage = "Artist deleted successfully.";
        } else {
            $artistMessage = "Error deleting artist: " . $stmt->error;
        }
    }

    // Delete design
    if (isset($_POST['deleteDesign'])) {
        $designId = (int)$_POST['design_id'];
        $stmt = $conn->prepare("DELETE FROM designs WHERE id = ?");
        $stmt->bind_param("i", $designId);
        if ($stmt->execute()) {
            $designMessage = "Design deleted successfully.";
        } else {
            $designMessage = "Error deleting design: " . $stmt->error;
        }
    }


    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
