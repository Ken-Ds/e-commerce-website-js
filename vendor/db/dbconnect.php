<!-- AUTHORED BY: CAGULADA, SHERYN ANN -->
 
<?php

$conn = new mysqli("localhost", "root", "", "ings");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>