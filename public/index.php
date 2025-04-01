<?php
include "config.php";


// Fetch artists from the database
$artists = [];
$stmt=$conn->prepare("SELECT*FROM artist");
$stmt->execute();
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch designs from the database
$designs = [];
$stmt = $conn->prepare("SELECT * FROM design");
$stmt->execute();
$designs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mehindi Studio Management</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5e6f1;
            color: #4a2b0f;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background-color: #8b5e3c;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #d4a798;
            margin: 0 5px;
            border-radius: 5px;
        }

        .nav-tab {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .tab.active {
            background-color: #8b5e3c;
            color: white;
        }

        .content-section {
            display: none;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content-section.active {
            display: block;
        }

        .booking-form {
            display: grid;
            grid-gap: 15px;
            max-width: 500px;
            margin: 0 auto;
        }

        input, select, button {
            padding: 10px;
            border: 1px solid #add;
            border-radius: 5px;
        }

        button {
            background-color: #8b5e3c;
            color: white;
            cursor: pointer;
        }

        .artist-card {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px;
            background-color: #fff5ee;
            border-radius: 10px;
        }

        .artist-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .design-gallery {
            display: grid;
            grid-template-columns: repeat(auto, minmax(200px, 1fr));
            gap: 15px;
        }

        .design-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }

        .design-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s;
        }

       .design-card:hover img {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Mehindi Magic Studio</h1>
    </div>

    <div class="container">
        <div class="non-tabs">
            <div class="tab action" onclick="showSection('booking')">Booking Appointment</div>
            <div class="tab" onclick="showSection('artists')">Our Artists</div>
            <div class="tab" onclick="showSection('gallery')">Design Gallery</div>
        </div>

        <div id="booking" class="content-section action active">
            <h2>Booking Your Appointment</h2>
            <form class="booking-form" method="POST" action="book.php">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="date" name="date" required>
                <input type="time" name="time" required>

                <select name="artist" required>
                    <option value="">Select Artist</option>
                    <?php foreach ($artists as $artist): ?>
                        <option value="<?= htmlspecialchars($artist['name']) ?>"><?= htmlspecialchars($artist['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="design_type" required>
                    <option value="">Design Type</option>
                    <option value="Bridal">Bridal</option>
                    <option value="Arabic">Arabic</option>
                    <option value="Traditional">Traditional</option>
                </select>

                <button type="submit">Book Now</button>
            </form>
        </div>

        <div id="artists" class="content-section">
            <h2>Our Talented Artists</h2>
            <div id="artist-container">
                <?php foreach ($artists as $artist): 
                       ?>
                    <div class="artist-card">
                        <img src="<?= htmlspecialchars($artist['image_url']) ?>" class="artist-image" alt="<?= htmlspecialchars($artist['name']) ?>">
                        <div>
                            <h3><?= htmlspecialchars($artist['name']) ?></h3>
                            <p>Experience: <?= htmlspecialchars($artist['experience']) ?> years</p>
                            <p>Specialty: <?= htmlspecialchars($artist['specialty']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="gallery" class="content-section">
            <h2>Design Gallery</h2>
            <div class="design-gallery" id="gallery-container">
                <?php foreach ($designs as $design): ?>
                    <div class="design-card">
                        <img src="<?= htmlspecialchars($design['image_url']) ?>" alt="Mehndi Design">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Tab navigation
        function showSection(sectionID) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(sectionID).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>