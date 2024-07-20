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
$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if (!$bd) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "hms";
try
{
 $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
 $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
 $e->getMessage();
}
?>
