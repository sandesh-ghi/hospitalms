<?php
session_start();
include('include/config.php');

// Set the session variable to an empty string
$_SESSION['dlogin'] = "";

// Set timezone and get current date and time
date_default_timezone_set('Asia/kathmandu');
$ldate = date('d-m-Y h:i:s A', time());

// Prepare and bind
$stmt = $bd->prepare("UPDATE doctorslog SET logout = ? WHERE uid = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param('ss', $ldate, $_SESSION['id']);

// Execute the statement
$stmt->execute();
$stmt->close();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Set a logout message
$_SESSION['errmsg'] = "You have successfully logged out";

// Redirect to the login page
?>

<script language="javascript">
    document.location = "index.php";
</script>