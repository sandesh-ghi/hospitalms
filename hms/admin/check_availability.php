<?php 
require_once("include/config.php");

if(!empty($_POST["email"])) {
    $email = $_POST["email"];
    
    // Use prepared statements to avoid SQL injection
    $stmt = $bd->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    $count = $stmt->num_rows;
    
    if($count > 0) {
        echo "<span style='color:red'> Email already exists.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'> Email available for Registration.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
    
    $stmt->close();
}
?>
