<?php
include "fragments/header.php";
include "checksession.php";
checkUser();
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";
if(isAdmin()){
    //do some simple validation to check if id exists
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid customerID</h2>"; //simple error feedback
        exit;
    } 

    //prepare a query and send it to the server
    //NOTE for simplicity purposes ONLY we are not using prepared queries
    //make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT * FROM customer WHERE customerid='.$id;
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result); 
    ?>
    <h1>Customer Details View</h1>
    <h2><a href='listcustomers.php'>[Return to the List of Registered Customers]</a></h2>
    <?php
    //makes sure we have the customer
    if ($rowcount > 0) {  
        echo "<fieldset><legend>Customer detail #$id</legend><dl>"; 
        $row = mysqli_fetch_assoc($result);
        echo "<dt>Name:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['firstname']."</dd>".PHP_EOL;
        echo "<dt>Lastname:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['lastname']."</dd>".PHP_EOL;
        echo "<dt>Email:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['email']."</dd>".PHP_EOL;
        echo "<dt>Password:</dt><dd>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp".$row['password']."</dd>".PHP_EOL; 
        echo '</dl></fieldset>'.PHP_EOL;  
    } else {
        echo "<h2>No customer found!</h2>"; //suitable feedback
    }
    mysqli_free_result($result); //free any memory used by the query
  mysqli_close($DBC); //close the connection once done
    echo '</div></div>';
    include "fragments/footer.php";
} else {
echo "<h1>Please log in as Admin to view this page</h1>";
}
?> 