<?php
include('include/config.php');

// Check if action and docinfo are set
if (isset($_GET['action']) && $_GET['action'] == 'doctorid' && isset($_POST['docinfo'])) {
    $docinfo = $_POST['docinfo'];

    // Sanitize input
    $docinfo = filter_var($docinfo, FILTER_SANITIZE_STRING);

    // Prepare and execute SQL statement
    $stmt = $bd->prepare("SELECT docFees FROM doctors WHERE doctorName = ?");
    $stmt->bind_param("s", $docinfo);
    $stmt->execute();
    $stmt->bind_result($docFees);
    
    if ($stmt->fetch()) {
        echo htmlspecialchars($docFees);  // Output the fees, safely encoded
    } else {
        echo "No data found";  // Handle case where no data is found
    }
    
    $stmt->close();
}
?>
