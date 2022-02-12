<?php
include "fragments/header.php";
include "checksession.php";
include "fragments/menu.php";

echo '<div id="site_content">';
include "fragments/sidebar.php";

echo '<div id="content">';
include "content.php";

echo '</div></div>';
include "fragments/footer.php";
?>
