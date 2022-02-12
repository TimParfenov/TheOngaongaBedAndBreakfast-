<?php
include "fragments/header.php";
include "checksession.php";
checkUser();
include "fragments/menu.php";
echo '<div id="site_content">';
include "fragments/sidebar.php";
if(isAdmin()){
  ?>
<h1>Customer List Search by Lastname</h1>
  <form>
    <label for="lastname">Lastname: </label>
    <input id="lastname" type="text" size="30" onkeyup="searchResults(this.value)" onclick="javascript: this.value = ''" placeholder="Start typing a last name">
  </form>
  <table id="tblcustomers" border="1">
  <thead><tr><th>Lastname</th><th>Firstname</th><th>actions</th></tr></thead>
  </table>
<?php 
mysqli_close($DBC); //close the connection once done
echo '</div></div>';
include "fragments/footer.php";
} else {
echo "<h1>Please log in as Admin to view this page</h1>";
}
?> 