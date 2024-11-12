<?php
include '../db/dbconnect.php';

session_start();  

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if attempts were exceeded
    $current_time = time();
    $last_attempt_time = $_SESSION['last_attempt_time'];
    $time_diff = $current_time - $last_attempt_time;
    $cooldown_period = 30 * 60; // 30 seconds cooldown

   // If user has exceeded 5 attempts and the cooldown period is not over
   if ($_SESSION['login_attempts'] >= 5 && $time_diff < $cooldown_period) {
    $remaining_time = $cooldown_period - $time_diff;
    $minutes_left = floor($remaining_time / 60); 
    $seconds_left = $remaining_time % 60; 

    echo "<script>
        alert('You have exceeded the maximum number of login attempts. Please try again in $minutes_left minutes $seconds_left seconds.');
        window.location.href = '../pages/login.php';
    </script>";
    exit();
}

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {

            if ($user['type'] === 'vendor') {
                $_SESSION['login_attempts'] = 0;
                $_SESSION['last_attempt_time'] = 0;  

                $_SESSION['userID'] = $user['userID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['type'] = $user['type'];

                header("Location: ../pages/view-product.php?id={$user['orgID']}");
                exit();
            } else {
                // User is not Vendor
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = $current_time;

                // Check the remaining attempts
                $remaining_attempts = max(5 - $_SESSION['login_attempts'], 0);

                if ($_SESSION['login_attempts'] >= 5) {
                    echo "<script>
                            alert('You have exceeded the maximum number of login attempts. Please try again in $minutes_left minutes $seconds_left seconds.');
                            window.location.href = '../pages/login.php';
                          </script>";
                    exit();
                } else {
                    echo "<script>
                            alert('You must be a vendor to log in. You have $remaining_attempts attempt(s) remaining.'); 
                            window.location.href = '../pages/login.php';
                        </script>";
                    exit();
                }
            }
        } else {
            // Incorrect password
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = $current_time;

            // Check the remaining attempts
            $remaining_attempts = max(5 - $_SESSION['login_attempts'], 0);

            if ($_SESSION['login_attempts'] >= 5) {
                echo "<script>
                        alert('You have exceeded the maximum number of login attempts. Please try again in $minutes_left minutes $seconds_left seconds.');
                        window.location.href = '../pages/login.php';
                    </script>";
                exit();
            } else {
                echo "<script>
                        alert('Incorrect username or password. You have $remaining_attempts attempt(s) remaining.'); 
                        window.location.href = '../pages/login.php';
                     </script>";
                exit();
            }
        }
    } else {
        // Username not found in the database
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = $current_time;

        $remaining_attempts = max(5 - $_SESSION['login_attempts'], 0);

        echo "<script>
                alert('Username not found in the database. You have $remaining_attempts attempt(s) remaining.'); 
                window.location.href = '../pages/login.php';
            </script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>