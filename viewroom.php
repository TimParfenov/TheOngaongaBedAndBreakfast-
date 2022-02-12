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
 echo "<h2>Invalid Room ID</h2>"; //simple error feedback
    exit;
} 

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * 
FROM room 
WHERE roomid='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Room Details View</h1>
<?php

//makes sure we have the Room
if ($rowcount > 0) {  
    echo "<fieldset><legend>Room detail #$id</legend><dl>"; 
    $row = mysqli_fetch_assoc($result);
    echo "<dt>Room name:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['roomname']."</dt>".PHP_EOL;
    echo "<dt>Description:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['description']."</dd>".PHP_EOL;
    echo "<dt>Room type:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['roomtype']."</dd>".PHP_EOL;
    echo "<dt>Beds:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['beds']."</dd>".PHP_EOL; 
    echo '</dl></fieldset>'.PHP_EOL;  
} else echo "<h2>No Room found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?> 