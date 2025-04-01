<?php
include 'config.php';
$artistMessage = '';
$designMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add Artist Form Submission
    if (isset($_POST['addArtist'])) {
        // Sanitize inputs
        $artistName = htmlspecialchars($_POST['artistName']);
        $artistSpecialty = htmlspecialchars($_POST['artistSpecialty']);
        $artistExperience = (int)$_POST['artistExperience']; // Cast to integer
        $artistContact = htmlspecialchars($_POST['artistContact']);

        // Validate contact number (must be exactly 10 digits)
        if (!preg_match('/^\d{10}$/', $artistContact)) {
            $artistMessage = "Contact number must be exactly 10 digits.";
        } elseif (!is_numeric($artistExperience)) { // Validate experience as numeric
            $artistMessage = "Experience must be a number.";
        } else {
            // Handle image upload
            if (isset($_FILES['artistImage']) && $_FILES['artistImage']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/artists/';
                $originalName = basename($_FILES['artistImage']['name']);
                $uniqueName = uniqid() . '_' . $originalName;
                $targetPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($_FILES['artistImage']['tmp_name'], $targetPath)) {
                    // Prepare SQL statement
                    $stmt = $conn->prepare("INSERT INTO artists 
                        (name, specialty, experience, contact, image_url) 
                        VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param(
                        "ssiis",
                        $artistName,
                        $artistSpecialty,
                        $artistExperience,
                        $artistContact,
                        $targetPath
                    );

                    if ($stmt->execute()) {
                        $artistMessage = $artistName . " Artist added successfully!";
                    } else {
                        $artistMessage = "Database error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $artistMessage = "Failed to move uploaded file.";
                }
            } else {
                $artistMessage = "Image upload error: " . $_FILES['artistImage']['error'];
            }
        }
    }

    // Add Design Form Submission
    elseif (isset($_POST['addDesign'])) {
        $designName = htmlspecialchars($_POST['designName']);
        $designType = htmlspecialchars($_POST['designName']);
        $designDescription = htmlspecialchars($_POST['designDescription']);

        if (isset($_FILES['designImage']) && $_FILES['designImage']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/designs/';
            $originalName = basename($_FILES['designImage']['name']);
            $uniqueName = uniqid() . '_' . $originalName;
            $targetPath = $uploadDir . $uniqueName;

            if (move_uploaded_file($_FILES['designImage']['tmp_name'], $targetPath)) {
                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO designs 
                    (name, description, image_url) 
                    VALUES (?, ?, ?)");
                $stmt->bind_param(
                    "sss",
                    $designName,
                    $designDescription,
                    $targetPath
                );

                if ($stmt->execute()) {
                    $designMessage = $designName . " Design added successfully!";
                } else {
                    $designMessage = "Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $designMessage = "Failed to move uploaded file.";
            }
        } else {
            $designMessage = "Image upload error: " . $_FILES['designImage']['error'];
        }
    }
}

$bookings = [];
$query = "
    SELECT 
        b.booking_id, 
        b.full_name, 
        b.email, 
        b.appointment_date, 
        b.appointment_time, 
        a.name AS artist_name, 
        b.design_type 
    FROM bookings b
    JOIN artists a ON b.artist_id = a.artist_id
    ORDER BY b.appointment_date DESC, b.appointment_time DESC
";
$result = $conn->query($query);

if ($result) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $bookingMessage = "Error fetching bookings: " . $conn->error;
}
