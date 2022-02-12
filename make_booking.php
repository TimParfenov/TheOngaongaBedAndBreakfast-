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
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
  $error = 0; //clear our error flag
  $msg = 'Error: ';

//rooms
  $rooms = cleanInput($_POST['rooms']); 
//customers
  $customers = cleanInput($_POST['customers']); 
//checkIn_database
  $checkIn_database = date('Y-m-d', strtotime($_POST['checkInDate']));
//checkOut_database
  $checkOut_database = date('Y-m-d', strtotime($_POST['checkOutDate']));
//contactNumber
  $contactNumber = cleanInput($_POST['contactNumber']);         
//breakfastStyle
  $breakfastStyle = cleanInput($_POST['breakfastStyle']);  
//bookingExtras
  $bookingExtras = cleanInput($_POST['bookingExtras']);

//save the booking data if the error flag is still clear
  if ($error == 0) {
      $query = "INSERT INTO booking (roomID,customerID, checkInDate,checkOutDate,contactNumber,breakfastStyle, bookingExtras) VALUES (?,?,?,?,?,?,?)";
      $stmt = mysqli_prepare($DBC,$query); //prepare the query
      mysqli_stmt_bind_param($stmt,'sssssss',$rooms,$customers, $checkIn_database, $checkOut_database, $contactNumber,$breakfastStyle,$bookingExtras);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);    
      echo "<h2>New Booking added</h2>";        
  } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
}
  $id = $_SESSION['userid']; 
?>
<h1>Make booking</h1>
<form method="POST" action="make_booking.php">
  <p>
    <label for="rooms">Room (name, type, beds): </label>
      <select name="rooms" id="rooms" required>
        <?php
          $query = 'SELECT * FROM room';
          $result = mysqli_query($DBC,$query);
          $rowcount = mysqli_num_rows($result);
          if ($rowcount>0){
            while($row=mysqli_fetch_array ($result)) 
            {
              echo '<option value="'.$row['roomID'].'">'.$row['roomname'].', '.$row['roomtype'].', '.$row['beds'].'</options>';
            }
          }
        ?>
      </select>
  </p>
    <input type="hidden" id="customers" name="customers" value="<?php echo $id?>"> 
  <p> 
    <label for="checkInDate">Check-in date: </label>
    <input id="from" type="text" name="checkInDate" required> 
  </p>  
  <p> 
    <label for="checkOutDate">Check-out date: </label>
    <input id="to" type="text" name="checkOutDate" required> 
  </p>  
  <p>  
    <label for="contactNumber">Contact number: </label>
    <input type="tel" id="contactNumber" placeholder="(###)###-####" name="contactNumber" pattern="[(][0-9]{3}[)][0-9]{3}-[0-9]{4}" minlength="13" maxlength="13" required> 
  </p>
  <p>  
    <label for="breakfastStyle">Breakfast Style: </label>
    <input type="radio" id="breakfastStyle" name="breakfastStyle" value="cooked" required> Cooked 
    <input type="radio" id="breakfastStyle" name="breakfastStyle" value="continental" required> Continental  
  </p>
  <p>  
    <label for="bookingExtras">Booking extras: </label>
    <textarea id="bookingExtras" name="bookingExtras" rows="4" cols="40"> 
      </textarea>
  </p>
    <input type="submit" name="submit" value="Add">
</form>
<form onsubmit="searchResult(this.checkin2.value , this.checkout2.value); return false">
  <label  for="checkin2">Check in date: </label>
  <input type="date"  name="checkin2">
  <label for="checkout2">Check out date: </label>
  <input type="date"  name="checkout2" >
  <input type="submit" value="Search">
</form> 
<table id="tblrooms" border="1">
  <thead><tr>
    <th>RoomID</th>
    <th>Room name</th>
    <th>Room type</th>
    <th>Beds</th>
  </tr></thead>
</table>
<?php 
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?>  


