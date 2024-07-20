<?php
// $mysql_hostname = "localhost";
// $mysql_user = "root";
// $mysql_password = "";
// $mysql_database = "hms";
// $bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect database");
// mysql_select_db($mysql_database, $bd) or die("Could not select database");

?>
<?php

$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "hms";

// Create connection using mysqli
$bd = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($bd->connect_error) {
    die("Connection failed: " . $bd->connect_error);
}
?>
