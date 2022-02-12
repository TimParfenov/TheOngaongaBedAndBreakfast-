<?php
include "fragments/header.php";
include "checksession.php";
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";

//show the current login status
if($_SESSION['username']){
  loginStatus();
}else {
  echo '<h1>Login</h1>';
}
//simple logout
if (isset($_POST['logout'])) logout();
if (isset($_POST['login']) and !empty($_POST['login']) and ($_POST['login'] == 'Login')) {
  $error = 0; //clear our error flag
  $msg = 'Error: ';

  if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {
    $un = htmlspecialchars(stripslashes(trim($_POST['username'])));  
    $username = (strlen($un)>32)?substr($un,1,32):$un; //check length and clip if too big       
  } else {
    $error++; //bump the error flag
    $msg .= 'Invalid username '; //append error message
    $username = '';  
  } 
//password  - normally we avoid altering a password apart from whitespace on the ends   
  $password = trim($_POST['password']);        
//This should be done with prepared statements!!
if ($error == 0) {
  $query = "SELECT customerID,password 
  FROM customer 
  WHERE firstname = '$username'";
  $result = mysqli_query($DBC,$query);     
  if (mysqli_num_rows($result) == 1) { //found the user
    $row = mysqli_fetch_assoc($result);
    if ($password === $row['password']) //using plaintext for demonstration only!            
      login($row['customerID'],$username);
      } echo "<h2>Login fail</h2>".PHP_EOL;   
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
}
?>
<form method="POST" action="login.php">
<?php if($_SESSION['username']) {
echo '<input type="submit" name="logout" value="Logout">';
}else {
  ?>
  <p>
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" maxlength="32"> 
  </p> 
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" maxlength="32"> 
  </p> 
  <input type="submit" name="login" value="Login">
  
</form>
<?php
} 
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
?> 