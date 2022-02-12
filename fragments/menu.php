<div id="menubar">
    <ul id="menu">
        <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
        <li class="selected"><a href="./">Home</a></li>
        <li><a href="./listrooms.php">Rooms</a></li>
        <?php
            $un = $_SESSION['username'];
            if($un) {
            echo "<li><a href='./listbookings.php'>Bookings</a></li>";
            echo "<li><a href='./make_booking.php'>Make Booking</a></li>";
            }
        ?>
            <li><a href='./registercustomer.php'>Register</a></li>
        <?php
            if($un) {
            echo "<li><a href='./login.php'> $un </a></li>";
            } else {
            echo "<li><a href='./login.php'>Login</a></li>";
            }
        ?>
    </ul>
</div>

