<!-- AUTHORED BY: CAGULADA, SHERYN ANN 
    LOGOUT ADDED BY: LUMANGLAS, YENZY HYNNA-->
<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
?>

    <header class='header'>
        <nav>
            <a href='view-product.php?id=<?php echo htmlspecialchars($id); ?>'>
                <img src='../../shared/style/img/logo.png' alt='Logo of INGS'>
            </a>
            <h1>In Genius Orders</h1>
            <div class='navbar' id='navbar'>
                <ul>
                    <li><a href='../pages/view-product.php?id=<?php echo htmlspecialchars($id); ?>'>Products</a></li>
                    <li><a href='../pages/view-orders.php?id=<?php echo htmlspecialchars($id); ?>'>Orders</a></li>
                    <li><a href='../pages/view-sales.php?id=<?php echo htmlspecialchars($id); ?>'>Sales</a></li>
                    <li><a href='../pages/view-profile.php?id=<?php echo htmlspecialchars($id); ?>'>Profile</a></li>
                    <!-- Logout Form with CSS Styling -->
                    <li>
                        <form action='../../shared/db/logout.php' method='POST' class='logout-form'>
                            <button type='submit' class='logout-button'>
                                <img src='../style/img/logout.png' alt='Logout' class='logout-img'>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</body>
</html>
