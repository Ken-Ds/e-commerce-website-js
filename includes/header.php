<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
echo "<!DOCTYPE html>
    <html>
    <head>
        <title>INGS Advanced Orders</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link href='../style/style.css' rel='stylesheet' >
    </head>
    <body>
        <header class = 'header'>
                <nav>
                    <a href= 'view-product.php?id=";  echo htmlspecialchars($id); echo"'> <img src='../style/img/logo.png' alt='Logo of INGS'></a>
                    <h1>In Genius Orders</h1>
                    <div class='navbar' id='navbar'>
                        <ul>
                            <li><a href='../pages/view-product.php?id=";  echo htmlspecialchars($id); echo" ' >Products</a></li>
                            <li><a href='../pages/view-orders.php?id=";  echo htmlspecialchars($id); echo"'>Orders</a></li>
                            <li><a href='../pages/view-sales.php?id=";  echo htmlspecialchars($id); echo"'>Sales</a></li>
                            <li><a href='../pages/view-profile.php?id=";  echo htmlspecialchars($id); echo"'>Profile</a></li>
                          <!-- Logout Form with CSS Styling -->
                        <li>
                            <form action='../pages/logout.php' method='POST' class='logout-form'>
                                <button type='submit' class='logout-button'>
                                    <img src='../style/img/logout.png' alt='Logout' class='logout-img'>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>";
?>