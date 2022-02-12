<?php
include "fragments/header.php";
include "checksession.php";
checkUser();
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";
// prepare a query and send it to the server
$query = 'SELECT * 
FROM room,booking,customer
WHERE booking.roomID=room.roomID AND booking.customerID=customer.customerID';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
$un = $_SESSION['username'];
?>
<h2>Existing bookings</h2>
<table border="1">
<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></tr></thead>        
<?php

    if ($rowcount > 0) {  
        while ($row = mysqli_fetch_assoc($result)) 
        {   //For privacy it will display only loggedin customer bookings or all list for admin!
            if ($row['firstname']==$un or isAdmin() == $un){ 
            $id = $row['bookingID'];
            echo '<tr><td>'.$row['roomname'].', '.$row['checkInDate'].', '.$row['checkOutDate'].'</td><td>'.$row['lastname'].', '.$row['firstname'].'</td>';
            echo '<td><a href="booking_details.php?id='.$id.'">[view]</a>';
            // Customer will have an access to it's bookings to make changes to booking and manage review
            echo '<a href="edit_booking.php?id='.$id.'">[edit]</a>';
            echo '<a href="edit_add_room_review.php?id='.$id.'">[manage review]</a>';
            //Only admin has power to delete booking, customer must send an email to confirm!!
            if(isAdmin()){ 
            echo '<a href="delete_booking.php?id='.$id.'">[delete]</a></td>';
            }
            echo '</tr>'.PHP_EOL;
        }
    }
} else echo "<h2>No booking found!</h2>"; //suitable feedback

?>
</table>
<?php
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?>