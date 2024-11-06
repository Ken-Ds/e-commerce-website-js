<?php

include '../db/dbconnect.php';  
include '../db/get-functions.php'; 

$userID = 1; 

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
            align-items: flex-start;
            padding: 20px;
            font-size: 20px;
            width: 100%;
        }

        .profile-section h2, .organization-section h2 {
            font-size: 30px;
            margin-bottom: 10px;
            font-family: Retrograde;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-right: 20px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #000;
            padding: 40px;
            width: 250px;
            height: 250px;
            position: relative;
            overflow: hidden;
        }

        .org-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            margin-top: 20px;
        }

        .change-button {
            font-family: Inter;
            font-size: 20px;
            background-color: #2b2d7c;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            margin: 10px 20px;
        }

        .organization-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex: 1; 
        }

        .organization-info {
            border: 1px solid #000;
            padding: 30px;
            width: 90%;
            font-size: 20px;
            height: 270px;
        }

        .organization-info ul {
            list-style: none;
            padding: 0;
            font-size: 15px;
            font-family: Poppins-regular;
            text-align: justify;
        }

        .org-info-text {
            font-size: 15px; /* Uniform font size */
            font-family: Poppins-regular, sans-serif; /* Uniform font family */
            color: #555;
            margin-top: 10px;
        }

        .schedules-section {
            width: 90%;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #000;
        }

        .schedules-section h3 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .schedules-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedules-table th, .schedules-table td {
            padding: 10px;
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
                    <img src="../style/img/<?php echo htmlspecialchars($organizationInfo['oLogo']); ?>" alt="Org Logo" class="org-logo">
                <?php else: ?>
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
            </div>

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

</body>
</html>
