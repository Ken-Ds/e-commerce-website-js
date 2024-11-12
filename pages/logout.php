<?php
session_start();

if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
    session_destroy();
    echo "<script>alert('You have been logged out.'); window.location = '../pages/login.php';</script>";
    exit();
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    echo "<script>
            var confirmLogout = confirm('Are you sure you want to log out?');
            if (confirmLogout) {
                window.location.href = '?confirm=yes'; // Proceed with logout
            } else {
                window.location.href = '../pages/view-product.php?id=" . htmlspecialchars($userId) . "';
            }
          </script>";
    exit();
}

header("Location: ?logout=true");
exit();
?>