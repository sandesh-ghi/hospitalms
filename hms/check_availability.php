<?php
require_once("include/config.php");

if (!empty($_POST["email"])) {
    $email = $_POST["email"];

    // Sanitize and validate email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<span style='color:red'> Invalid email format. </span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
        exit();
    }

    // Prepare and execute SQL statement
    $stmt = $bd->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<span style='color:red'> Email already exists. </span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'> Email available for registration. </span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }

    $stmt->close();
}
?>
