<?php
session_start();
include('include/config.php');

// Clear the login session variable
$_SESSION['login'] = "";

// Set the timezone and get the current date and time
date_default_timezone_set('Asia/kathmandu');
$ldate = date('d-m-Y h:i:s A', time());

// Prepare and execute SQL statement to update logout time
if (isset($_SESSION['id'])) {
    $stmt = $bd->prepare("UPDATE userlog SET logout = ? WHERE uid = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("si", $ldate, $_SESSION['id']);
    $stmt->execute();
    $stmt->close();
}

// Unset session variables and destroy the session
session_unset();
session_destroy();

// Set logout message
$_SESSION['errmsg'] = "You have successfully logged out";

// Redirect to login page
?>

<script language="javascript">
    document.location = "./user-login.php";
</script>
