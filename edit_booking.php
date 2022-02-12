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

//retrieve the bookingID from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid booking ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
//validate incoming data - only the first field is done for you in this example - rest is up to you do
  $error = 0; //clear our error flag
  $msg = 'Error: ';  
//bookingID (sent via a form ti is a string not a number so we try a type conversion!)    
if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
  $id = cleanInput($_POST['id']); 
} else {
  $error++; //bump the error flag
  $msg .= 'Invalid booking ID '; //append error message
  $id = 0;  
}   
//rooms
$rooms = cleanInput($_POST['rooms']); 
//checkInDate
$checkIn_database = date('Y-m-d', strtotime($_POST['checkInDate']));
//checkOut_database
$checkOut_database = date('Y-m-d', strtotime($_POST['checkOutDate']));
//contactNumber
$contactNumber = cleanInput($_POST['contactNumber']);         
//breakfastStyle
$breakfastStyle = cleanInput($_POST['breakfastStyle']);  
//bookingExtras
$bookingExtras = cleanInput($_POST['bookingExtras']);  
//roomReview
$roomReview = cleanInput($_POST['roomReview']);  

//save the booking data if the error flag is still clear and bookingID is > 0
if ($error == 0 and $id > 0) {
  $query = "UPDATE booking 
  SET roomID=?,checkInDate=?,checkOutDate=?,contactNumber=?,breakfastStyle=?, bookingExtras=?,roomReview=? 
  WHERE bookingID=?";
  $stmt = mysqli_prepare($DBC,$query); //prepare the query
  mysqli_stmt_bind_param($stmt,'sssssssi', $rooms,$checkIn_database, $checkOut_database, $contactNumber,$breakfastStyle,$bookingExtras,$roomReview, $id); 
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);    
  echo "<h2>Booking details updated.</h2>";     
} else { 
echo "<h2>$msg</h2>".PHP_EOL;
}      
}
$query = 'SELECT * 
FROM booking,room, customer 
WHERE booking.roomID=room.roomID AND booking.customerID=customer.customerID AND bookingID='.$id;        
$result = mysqli_query($DBC,$query);                
$row = mysqli_fetch_assoc($result);        
?>
<h1>Booking Details Update</h1>
<?php  
// Row below prevents accessing data by manualy changing id number in the browser
  if (isAdmin() == $un || $row['firstname'] == $un) { 
?>
  <form method="POST" action="edit_booking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  <p>
      <label for="rooms">Room (name, type, beds): </label>
      <select name="rooms" id="rooms" required>
          <?php
            $query = 'SELECT roomID,roomname,roomtype,beds 
            FROM room';
            $result = mysqli_query($DBC,$query);
            $rowcount = mysqli_num_rows($result);
            echo $rowcount;
            if ($rowcount>0){
              while($room=mysqli_fetch_assoc ($result)) {
                if($room['roomID']!= $row['roomID']){
                  echo '<option value="'.$room['roomID'].'">'.$room['roomname'].', '.$room['roomtype'].', '.$room['beds'].'</options>';
                  }else {
                    echo '<option selected value="'.$row['roomID'].'">'.$row['roomname'].', '.$row['roomtype'].', '.$row['beds'].'</options>';
              }
            }
          }
          ?>
      </select>
    </p> 
    <p>
      <label for="checkInDate">Check-in date: </label>
      <input type="text" id="from" name="checkInDate" value="<?php echo $row['checkInDate']; ?>" required> 
    </p>  
    <p> 
      <label for="checkOutDate">Check-out date: </label>
      <input type="text" id="to" name="checkOutDate" value="<?php echo $row['checkOutDate']; ?>" required> 
    </p>  
    <p>  
      <label for="contactNumber">Contact number: </label>
      <input type="tel" id="contactNumber" placeholder="(###)###-####" name="contactNumber" pattern="[(][0-9]{3}[)][0-9]{3}-[0-9]{4}" minlength="13" maxlength="13" value="<?php echo $row['contactNumber']?>" required> 
    </p>
    <p>  
      <label for="breakfastStyle">Breakfast Style: </label>
      <input type="radio" id="breakfastStyle" name="breakfastStyle" value="cooked" <?php echo $row['breakfastStyle']=='cooked'?'Checked':''; ?> required> Cooked 
      <input type="radio" id="breakfastStyle" name="breakfastStyle" value="continental" <?php echo $row['breakfastStyle']=='continental'?'Checked':''; ?> required> Continental  
    </p>
    <p>  
      <label for="bookingExtras">Booking extras: </label>
      <textarea id="bookingExtras" name="bookingExtras" rows="4" cols="40"> <?php if(empty($row['bookingExtras'])){
        echo "nothing";
      } else {
        echo $row['bookingExtras'];
      }
        ?></textarea>
    </p>
    <p>  
      <label for="roomReview">Room review: </label>
      <textarea id="roomReview" name="roomReview" rows="4" cols="40"> <?php if(empty($row['roomReview'])){
        echo "nothing";
      } else {
        echo $row['roomReview'];
      }
        ?></textarea>
    </p>
    <input type="submit" name="submit" value="Update">
  </form>
<?php
} else {
  echo "<h1>Please log in as Admin or as " .$row['firstname']." to view this page</h1>";
  } 
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?> 