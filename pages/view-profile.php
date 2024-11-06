<?php

include '../db/dbconnect.php';  
include '../db/get-functions.php'; 

$userID = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch organization information based on user ID
$organizationQuery = "
    SELECT o.orgID, o.oName, o.oDesc, o.oLogo, b.location
    FROM ORGANIZATIONS o
    JOIN BOOTHS b ON o.orgID = b.orgID
    WHERE o.orgID = ?
";

$stmt = $conn->prepare($organizationQuery);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
$organizationInfo = $result->fetch_assoc(); // Fetch the data from the query result

// Fetch schedules for the current organization
$schedulesQuery = "
    SELECT s.dayAvailable, s.timeStart, s.timeEnd
    FROM SCHEDULES s
    JOIN BOOTHS b ON s.boothID = b.boothID
    WHERE b.orgID = ?
";

$stmtSchedules = $conn->prepare($schedulesQuery);
$stmtSchedules->bind_param('i', $userID);
$stmtSchedules->execute();
$schedulesResult = $stmtSchedules->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style/style.css" rel="stylesheet">
    <title>View Profile</title>
    <style>
        /* VIEW PROFILE */
        .profile-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between; /* Align items evenly */
            align-items: center; /* Vertically align items in the center */
            padding: 20px; /* Adjust padding as needed */
            font-size: 18px;
            width: 100%;
            max-width: 1300px; /* Increased max-width for a larger layout */
            margin: 0 auto;
        }

        .profile-section h2, .organization-section h2 {
            font-size: 30px; /* Increased font size */
            margin-bottom: 10px; /* Increased margin */
            font-family: Retrograde;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the profile section content */
            margin-right: 30px; /* Increased margin for better spacing */
        }

        .logo-container {
            display: flex;
            justify-content: center; /* Center the logo container */
            align-items: center; /* Center logo vertically */
            border: 1px solid #000;
            padding: 50px; /* Increased padding */
            width: 300px; /* Increased width */
            height: 300px; /* Increased height */
            text-align: center; /* Center text and logo */
            margin-left: 20px; /* Space between the logo and other content */
        }

        .org-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block; /* Ensures logo is a block element to center */
            margin: 0 auto; /* Center the logo horizontally */
        }

        .change-button {
            font-family: Inter;
            font-size: 22px; /* Increased font size */
            background-color: #2b2d7c;
            color: white;
            border: none;
            padding: 12px 18px; /* Increased padding */
            cursor: pointer;
            margin: 10px 20px; /* Increased margin */
        }

        .organization-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1;
            padding-left: 30px; /* Increased padding */
            margin-top: 20px; /* Increased margin */
        }

        .organization-info {
            border: 1px solid #000;
            padding: 40px; /* Increased padding */
            width: 100%;
            font-size: 20px; /* Increased font size */
            height: auto;
        }

        .organization-info ul {
            list-style: none;
            padding: 0;
            font-size: 16px; /* Increased font size */
            font-family: Poppins-regular;
            text-align: justify;
        }

        .org-info-text {
            font-size: 16px; /* Increased font size */
            font-family: Poppins-regular, sans-serif;
            color: #555;
            margin-top: 10px; /* Increased margin */
        }

        .schedules-section {
            margin-top: 20px; /* Increased margin */
            padding: 30px; /* Increased padding */
            border: 1px solid #000;
            font-size: 18px; /* Increased font size */
        }

        .schedules-section h3 {
            font-size: 26px; /* Increased font size */
            margin-bottom: 12px; /* Increased margin */
        }

        .schedules-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedules-table th, .schedules-table td {
            padding: 12px; /* Increased padding */
            text-align: center;
            border: 1px solid #ddd;
        }

        .schedules-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <header class="header">
        <nav>
            <a href="view-product.html"> <img src="../style/img/logo.png" alt="Logo of INGS"></a>
            <h1>Website Name</h1>
            <div class="navbar" id="navbar">
                <ul>
                    <li><a href="view-product.html">Products</a></li>
                    <li><a href="">Orders</a></li>
                    <li><a href="view-sales.html">Sales</a></li>
                    <li><a href="view-profile.html">Profile</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="profile-container">
        <div class="profile-section">
            <h2>Profile</h2>
            <div class="logo-container">
                <?php if (!empty($organizationInfo['oLogo'])): ?>
                    <!-- Fetch and display the logo as a base64-encoded image -->
                    <?php
                    $logoBlob = $organizationInfo['oLogo'];
                    $logoBase64 = base64_encode($logoBlob);
                    echo '<img src="data:image/jpeg;base64,' . $logoBase64 . '" alt="Org Logo" class="org-logo">';
                    ?>
                <?php else: ?>
                    <!-- Default logo if no logo is found -->
                    <img src="../style/img/default-logo.png" alt="Org Logo" class="org-logo">
                <?php endif; ?>
            </div>
        </div>
        
        <div class="organization-section">
            <h2><?php echo htmlspecialchars($organizationInfo['oName']); ?></h2>
            <div class="organization-info">
                <ul>
                    <li><strong>Description:</strong> <span class="org-info-text"><?php echo htmlspecialchars($organizationInfo['oDesc']); ?></span></li>
                    <li><strong>Location:</strong> <span class="org-info-text"><?php echo htmlspecialchars($organizationInfo['location']); ?></span></li>
                </ul>
                
                <!-- Schedules Section -->
                <div class="schedules-section">
                    <h3>Booth Schedules</h3>
                    <table class="schedules-table">
                        <thead>
                            <tr>
                                <th>Day Available</th>
                                <th>Time Start</th>
                                <th>Time End</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($schedule = $schedulesResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($schedule['dayAvailable']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['timeStart']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['timeEnd']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include "../includes/footer.php"?>