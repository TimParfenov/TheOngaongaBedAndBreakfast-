<?php
include "fragments/header.php";
include "checksession.php";
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";
//prepare a query and send it to the server
$query = 'SELECT roomID,roomname,roomtype 
FROM room 
ORDER BY roomtype';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
if (isAdmin()) {
    echo "<h2><a href='./addroom.php'>[Add a new room]</a></h2>";
}
?>
<h1>Availabe rooms</h1>
<table border="1">
<thead><tr><th>Room Name</th><th>Type</th><th>Action</th></tr></thead>
<?php
//makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) 
    {
        $id = $row['roomID'];	
        echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
        echo '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
         //check if we have permission to modify data
    if (isAdmin()) {
        echo '<a href="editroom.php?id='.$id.'">[edit]</a>';
        echo '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
    }        
        echo '</tr>'.PHP_EOL;
    }
} else echo "<h2>No rooms found!</h2>"; //suitable feedback
?>
</table>
<?php
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?>