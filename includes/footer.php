<?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';
        echo "<footer>
                <div class='footer-container'>
                    <div class='logo-container'>
                        <img class='logo' src='../style/img/logo.png' alt='Logo of INGS'>
                    </div>
                    <div class='contact-us'>
                        <h2>Developers</h2>
                        <div class='devs'>
                            <div class='dev'>
                                <p>Burgos, Miguel Joshua<br></p>
                                <p>Cagulada, Sheryn Ann</p>
                                <p>Chua, Harvey Lance</p>
                            </div>
                            <div class='dev'>
                                <p>Evangelista, Kendrick</p>
                                <p>Lumanglas, Yenzy Hynna</p>
                                <p>Modelo, Alexx Evan</p>
                            </div>
                        </div>
                    </div>
                    <div class='quick-links'>
                        <h2>Quick Links</h2>
                        <ul>
                            <li><a href='../pages/view-product.php?id=";  echo htmlspecialchars($id); echo"' >Products</a></li>
                            <li><a href='../pages/view-orders.php?id=";  echo htmlspecialchars($id); echo"'>Orders</a></li>
                            <li><a href='../pages/view-sales.php?id=";  echo htmlspecialchars($id); echo"'>Sales</a></li>
                            <li><a href='../pages/view-profile.php?id=";  echo htmlspecialchars($id); echo"'>Profile</a></li>
                        </ul>
                    </div>
                </div>
                <div class='copyright'>
                    <p>&copy; INGS_CS312L</p>
                </div>
            </footer>
        </body>
        </html>
        ";
?>