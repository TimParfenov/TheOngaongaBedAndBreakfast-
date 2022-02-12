<?php
//Our customer search/filtering engine
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE) or die();


//do some simple validation to check if sq contains a string
$d1 = $_GET['d1'];
$d2 = $_GET['d2'];

$searchresult = '';
if (isset($d1) and !empty($d1) and isset($d2) and !empty($d2)) {
    
//prepare a query and send it to the server using our search string as a wildcard on surname
    $query = "SELECT * FROM room WHERE roomID NOT IN (SELECT roomID FROM booking WHERE checkInDate>='$d1' AND checkOutDate<='$d2')";
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result); 
        //makes sure we have customers
    if ($rowcount > 0) {  
        $rows=[]; //start an empty array
        
        //append each row in the query result to our empty array until there are no more results                    
        while ($row = mysqli_fetch_assoc($result)) {            
            $rows[] = $row; 
        }
        //take the array of our 1 or more customers and turn it into a JSON text
        $searchresult = json_encode($rows);
        //this line is crucial for the browser to understand what data is being sent
        header('Content-Type: text/json; charset=utf-8');
    } else echo "<tr><td colspan=3><h2>No Customers found!</h2></td></tr>";
} else echo "<tr><td colspan=3> <h2>Invalid search query</h2>";
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done

echo  $searchresult;
