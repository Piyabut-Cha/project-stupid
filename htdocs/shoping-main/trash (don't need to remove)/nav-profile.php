<div class="nav-profile">
    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo '<p class="nav-profile-name">' . $_SESSION['username'] . '</p>';
    } else {
        ?>
        <a href="login.html" class="nav-profile-login">Login</a>
        <a href="register.html" class="nav-profile-register">Register</a>
        <?php
    }
    ?>
    <div onclick="openCart()" style="cursor: pointer;" class="nav-profile-cart">
        <i class="fas fa-cart-shopping"></i>
        <div id="cartcount" class="cartcount" style="display: none;">
            0
        </div>
    </div>
</div>
