<?php
$artistMessage = '';
$designMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addArtist'])) {
        // Retrieve form data
        $artistName = $_POST['artistName'];
        $artistSpecialty = $_POST['artistSpecialty'];
        $artistExperience = $_POST['artistExperience']; // Added experience
        $artistContact = $_POST['artistContact']; // Assuming you want to keep this for future use
        $artistImageUrl = ''; // Placeholder for image URL
        if (isset($_FILES['artistImage']) && $_FILES['artistImage']['error'] === UPLOAD_ERR_OK) {
            $designImage = $_FILES['artistImage']['name'];
            // Move the uploaded file to a designated directory (e.g., 'uploads/')
            move_uploaded_file($_FILES['designImage']['tmp_name'], 'uploads/' . $designImage);
            $designMessage = "Design '$designName' added successfully!";
        } else {
            $designMessage = "Failed to upload design image.";
        }
        // Here you would typically save the artist data to a database
        // For demonstration, we'll just set a success message
        $artistMessage = "Artist '$artistName' added successfully!";
    } elseif (isset($_POST['addDesign'])) {
        // Handle design form submission
        $designName = $_POST['designName'];
        $designDescription = $_POST['designDescription'];

        if (isset($_FILES['designImage']) && $_FILES['designImage']['error'] === UPLOAD_ERR_OK) {
            $designImage = $_FILES['designImage']['name'];
            // Move the uploaded file to a designated directory (e.g., 'uploads/')
            move_uploaded_file($_FILES['designImage']['tmp_name'], 'uploads/' . $designImage);
            $designMessage = "Design '$designName' added successfully!";
        } else {
            $designMessage = "Failed to upload design image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Your existing CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f0f2f5;
        }

        .sidebar {
            width: 250px;
            background-color: #906827;
            color: rgb(255, 255, 255);
            padding: 20px;
            height: 100vh;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar-nav {
            list-style: none;
        }

        .sidebar-nav li {
            margin: 15px 0;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar-nav li:hover {
            background-color: #a77538;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .section {
            display: none;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .section.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="sidebar
        <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul class="sidebar-nav">
            <li onclick="showSection('appointments')">Appointments</li>
            <li onclick="showSection('addArtist')">Add Artist</li>
            <li onclick="showSection('addDesign')">Add Design</li>
        </ul>
    </div>

    <div class="content">
        <!-- Appointments Section -->
        <div id="appointments" class="section active">
            <h2>Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Artist</th>
                        <th>Design</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>2023-08-15</td>
                        <td>14:30</td>
                        <td>Artist 1</td>
                        <td>Dragon Design</td>
                    </tr>
                    <!-- Add more rows dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Add Artist Section -->
        <div id="addArtist" class="section">
            <h2>Add New Artist</h2>
            <form id="artistForm" method="POST">
                <div class="form-group">
                    <label for="artistName">Artist Name:</label>
                    <input type="text" id="artistName" name="artistName" required>
                </div>
                <div class="form-group">
                    <label for="artistSpecialty">Specialty:</label>
                    <input type="text" id="artistSpecialty" name="artistSpecialty" required>
                </div>
                <div class="form-group">
                    <label for="artistExperience">Experience:</label>
                    <input type="text" id="artistExperience" name="artistExperience" required>
                </div>
                <div class="form-group">
                    <label for="artistContact">Contact Information:</label>
                    <input type="text" id="artistContact" name="artistContact" required>
                </div>
                <button type="submit" name="addArtist">Add Artist</button>
            </form>
            <?php if ($artistMessage): ?>
                <p><?php echo $artistMessage; ?></p>
            <?php endif; ?>
        </div>

        <!-- Add Design Section -->
        <div id="addDesign" class="section">
            <h2>Add New Design</h2>
            <form id="designForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="designName">Design Name:</label>
                    <input type="text" id="designName" name="designName" required>
                </div>
                <div class="form-group">
                    <label for="designImage">Design Image:</label>
                    <input type="file" id="designImage" name="designImage" accept="image/png, image/jpeg" required>
                </div>
                <div class="form-group">
                    <label for="designDescription">Description:</label>
                    <textarea id="designDescription" name="designDescription" rows="4" required></textarea>
                </div>
                <button type="submit" name="addDesign">Add Design</button>
            </form>
            <?php if ($designMessage): ?>
                <p><?php echo $designMessage; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });

            // Show selected section
            document.getElementById(sectionId).classList.add('active');
        }

        // Initialize the default section
        showSection('appointments');
    </script>
</body>

</html>