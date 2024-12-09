<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../pages/login.php');
    exit();
}


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

include '../db/dbconnect.php';  
include '../db/get-functions.php'; 
include '../includes/navbar.php';

$userID = isset($_GET['id']) ? $_GET['id'] : '';


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
$organizationInfo = $result->fetch_assoc(); 


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
    <link href="../style/css/view-profile.css" rel="stylesheet">
    <title>In Genius Orders</title>
</head>
<body>
    
<?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';
?>

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
    <?php
        include '../includes/footer.php';
    ?>
