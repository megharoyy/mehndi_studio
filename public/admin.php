<?php
include '../includes/admin_logic.php';

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

        .delete-button {
            background-color: #e74c3c;
            margin-left: 10px;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul class="sidebar-nav">
            <li onclick="showSection('appointments')">Appointments</li>
            <li onclick="showSection('addArtist')">Add Artist</li>
            <li onclick="showSection('addDesign')">Add Design</li>
            <li onclick="showSection('artists')">Our Artists</li>
            <li onclick="showSection('designs')">Designs</li>
        </ul>
    </div>

    <div class="content">
        <!-- Appointments Section -->
        <div id="appointments" class="section active">
            <h2>Appointments</h2>
            <?php if (isset($bookingMessage)): ?>
                <center>
                    <p><b><?php echo $bookingMessage; ?></b></p>
                </center>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Artist</th>
                        <th>Design Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['appointment_time']); ?></td>
                                <td><?php 
$artist_id = $booking['artist_id']; // Assuming $booking is an associative array with 'artist_id'
$query = "SELECT name FROM artists WHERE artist_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $artist_id); // Assuming artist_id is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $artist = $result->fetch_assoc();
    $artist_name = $artist['name'];
} else {
    $artist_name = "Unknown Artist"; // Handle case where artist is not found
}echo $artist_name; ?></td>
                                <td><?php echo htmlspecialchars($booking['design_type']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                        <button type="submit" name="deleteBooking" class="delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Artist Section -->
        <div id="addArtist" class="section">
            <h2>Add New Artist</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="artistName">Artist Name:</label>
                    <input type="text" id="artistName" name="artistName" required>
                </div>
                <div class="form-group">
                    <label for="artistSpecialty">Specialty:</label>
                    <input type="text" id="artistSpecialty" name="artistSpecialty" required>
                </div>
                <div class="form-group">
                    <label for="artistExperience">Experience (in years):</label>
                    <input type="number" id="artistExperience" name="artistExperience" min="0" required>
                </div>
                <div class="form-group">
                    <label for="artistContact">Contact Information (10 digits):</label>
                    <input type="tel" id="artistContact" name="artistContact" pattern="\d{10}" required>
                    <small>Format: 1234567890</small>
                </div>
                <div class="form-group">
                    <label for="artistImage">Artist Image:</label>
                    <input type="file" id="artistImage" name="artistImage" accept="image/*" required>
                </div>
                <button type="submit" name="addArtist">Add Artist</button>
            </form>
            <?php if ($artistMessage): ?>
                <br>
                <p><?php echo $artistMessage; ?></p>
            <?php endif; ?>
        </div>

        <!-- Our Artists Section -->
        <div id="artists" class="section">
            <h2>Our Artists</h2>
            <table>
                <thead>
                    <tr>
                        <th>Artist Name</th>
                        <th>Specialty</th>
                        <th>Experience</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($artists)): ?>
                        <?php foreach ($artists as $artist): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($artist['name']); ?></td>
                                <td><?php echo htmlspecialchars($artist['specialty']); ?></td>
                                <td><?php echo htmlspecialchars($artist['experience']); ?> years</td>
                                <td><?php echo htmlspecialchars($artist['contact']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="artist_id" value="<?php echo htmlspecialchars($artist['artist_id']); ?>">
                                        <button type="submit" name="deleteArtist" class="delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No artists found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Add Design Section -->
        <div id="addDesign" class="section">
            <h2>Add New Design</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="designName">Design Name:</label>
                    <input type="text" id="designName" name="designName" required>
                </div>
                <div class="form-group">
                    <label for="designType">Design Type:</label>
                    <select name="design_type" required>
                        <option value="" selected disabled>Design Type</option>
                        <option value="Bridal">Bridal</option>
                        <option value="Arabic">Arabic</option>
                        <option value="Traditional">Traditional</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="designImage">Design Image:</label>
                    <input type="file" id="designImage" name="designImage" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="designDescription">Description:</label>
                    <textarea id="designDescription" name="designDescription" rows="4" required></textarea>
                </div>
                <button type="submit" name="addDesign">Add Design</button>
            </form>
            <?php if ($designMessage): ?>
                <br>
                <p><?php echo $designMessage; ?></p>
            <?php endif; ?>
        </div>

        <!-- Designs Section -->
        <div id="designs" class="section">
            <h2>Designs</h2>
            <table>
                <thead>
                    <tr>
                        <th>Design Name</th>
                        <th>Design Type</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($designs)): ?>
                        <?php foreach ($designs as $design): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($design['name']); ?></td>
                                <td><?php echo htmlspecialchars($design['design_type']); ?></td>
                                <td><?php echo htmlspecialchars($design['description']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="design_id" value="<?php echo htmlspecialchars($design['design_id']); ?>">
                                        <button type="submit" name="deleteDesign" class="delete-button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No designs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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