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

  //the data was sent using a formtherefore we use the $_POST instead of $_GET
  //check if we are saving data first by checking if the submit button exists in the array
  if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    $roomname = cleanInput($_POST['roomname']);

  //description
    $description = cleanInput($_POST['description']);        
  //roomtype
    $roomtype = cleanInput($_POST['roomtype']);            
  //beds    
    $beds = cleanInput($_POST['beds']);        
  //save the room data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO room (roomname,description,roomtype,beds) 
        VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssd', $roomname, $description, $roomtype,$beds); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>New room added to the list</h2>";        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
  }
  ?>
  <h1>Add a new room</h1>
  <form method="POST" action="addroom.php">
    <p>
      <label for="roomname">Room name: </label>
      <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" required> 
    </p> 
    <p>
      <label for="description">Description: </label>
      <input type="text" id="description" size="70" name="description" minlength="5" maxlength="200" required> 
    </p>  
    <p>  
      <label for="roomtype">Room type: </label>
      <input type="radio" id="roomtype" name="roomtype" value="S"> Single 
      <input type="radio" id="roomtype" name="roomtype" value="D" Checked> Double 
    </p>
    <p>
      <label for="beds">Beds (1-5): </label>
      <input type="number" id="beds" name="beds" min="1" max="5" value="1" required> 
    </p> 
    
    <input type="submit" name="submit" value="Add">
  </form>
  <?php 
  mysqli_close($DBC); //close the connection once done
  echo '</div></div>';
  include "fragments/footer.php";
} else {
  echo "<h1>Please log in as Admin to view this page</h1>";
}
  ?> 