<!-- AUTHORED BY: LUMANGLAS, YENZY HYNNA -->
<?php
session_start();


session_unset();
session_destroy();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");


echo "<script>alert('You have been logged out.'); window.location = '../pages/login.php';</script>";
exit();
?>
