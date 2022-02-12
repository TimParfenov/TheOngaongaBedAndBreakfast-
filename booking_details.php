<?php
include "fragments/header.php";
include "checksession.php";
checkUser();
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";


//function to clean input but not validate type and content
function cleanInput($data) {  
    return htmlspecialchars(stripslashes(trim($data)));
}

//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
exit;
} 

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT *
FROM booking,room,customer 
WHERE booking.roomID=room.roomID AND booking.customerID=customer.customerID AND booking.bookingID='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
?>
<h1>Booking Details View</h1>
<?php
//makes sure we have the Room
    if ($rowcount > 0) {  
        echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
        $row = mysqli_fetch_assoc($result);
        // Row below prevents accessing data by manualy changing id number in the browser
        if (isAdmin() == $un || $row['firstname'] == $un) { 
            echo "<dt>Room name:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['roomname']."</dd>".PHP_EOL;
            echo "<dt>Check-in date:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['checkInDate']."</dd>".PHP_EOL;
            echo "<dt>Check-out date:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['checkOutDate']."</dd>".PHP_EOL;
            echo "<dt>Contact number:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['contactNumber']."</dd>".PHP_EOL; 
            if(empty($row['bookingExtras'])){
            echo "<dt>Extras:</dt><dd>nothing</dd>".PHP_EOL;
            } else  { 
            echo "<dt>Extras:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['bookingExtras']."</dd>".PHP_EOL;
            } 
            if(empty($row['roomReview'])){
            echo "<dt>Room review:</dt><dd>nothing</dd>".PHP_EOL; 
            }else {
            echo "<dt>Room review:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['roomReview']."</dd>".PHP_EOL; 
            }
            if(empty($row['breakfastStyle'])){
            echo "<dt>Breakfast Style:</dt><dd>Breakfast not selected</dd>".PHP_EOL;     
            } else {
            echo "<dt>Breakfast Style:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['breakfastStyle']."</dd>".PHP_EOL; 
            }
            echo '</dl></fieldset>'.PHP_EOL;  
        } else {
        echo "<h1>Please log in as Admin or as " .$row['firstname']." to view this page</h1>";
        }
    } else {
        echo "<h2>No Booking found!</h2>";
    } //suitable feedback
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?>