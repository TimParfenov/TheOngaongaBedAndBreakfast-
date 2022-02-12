<?php
include "fragments/header.php";
include "checksession.php";
checkUser();
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";
if(isAdmin()){
    //function to clean input but not validate type and content
    function cleanInput($data) {  
    return htmlspecialchars(stripslashes(trim($data)));
    }

    //retrieve the bookingID from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
            exit;
        } 
    }

    //the data was sent using a form therefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
        $error = 0; //clear our error flag
        $msg = 'Error: ';  
    //BookingID (sent via a form it is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
            $id = cleanInput($_POST['id']); 
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid Booking ID '; //append error message
            $id = 0;  
        }        
        
    //save the Booking data if the error flag is still clear and Room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "DELETE FROM booking WHERE booking.bookingID=?";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'i', $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>Booking details deleted.</h2>";     
            } else { 
            echo "<h2>$msg</h2>".PHP_EOL;
            }      
        }
    //prepare a query and send it to the server
    //NOTE for simplicity purposes ONLY we are not using prepared queries
    //make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT bookingID, checkInDate, checkOutDate, roomname 
    FROM booking,room 
    WHERE booking.roomID=room.roomID AND booking.bookingID='.$id;
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result); 
    ?>
    <h1>Booking preview before deletion</h1>
    <?php
    //makes sure we have the Booking
    if ($rowcount > 0) {  
        echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
        $row = mysqli_fetch_assoc($result);
        echo "<dt>Room name:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['roomname']."</dd>".PHP_EOL;
        echo "<dt>Check-in date:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['checkInDate']."</dd>".PHP_EOL;
        echo "<dt>Check-out date:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['checkOutDate']."</dd>".PHP_EOL;
        echo '</dl></fieldset>'.PHP_EOL;  
    ?>
    <form method="POST" action="delete_booking.php">
        <h2>Are you sure you want to delete this Booking?</h2>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" name="submit" value="Delete">
        <a href="listbookings.php"><input type=button value="Cancel"></a>
        </form>
    <?php    
    } else {
        echo "<h2>No Booking found, possibly deleted!</h2>"; //suitable feedback
    }
    mysqli_free_result($result); //free any memory used by the query
    mysqli_close($DBC); //close the connection once done
    echo '</div></div>';
    include "fragments/footer.php";
} else {
    echo "<h1>Please log in as Admin to view this page</h1>";
}
    ?> 