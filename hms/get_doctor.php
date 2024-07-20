<?php
include('include/config.php');

// Check if specilizationid is set and not empty
if (isset($_POST["specilizationid"]) && !empty($_POST["specilizationid"])) {
    $specilizationid = $_POST["specilizationid"];

    // Sanitize input
    $specilizationid = filter_var($specilizationid, FILTER_SANITIZE_STRING);

    // Prepare and execute SQL statement
    $stmt = $bd->prepare("SELECT doctorName, id FROM doctors WHERE specilization = ?");
    $stmt->bind_param("s", $specilizationid);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option selected="selected">Select Doctor</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlentities($row['id']) . '">' . htmlentities($row['doctorName']) . '</option>';
    }

    $stmt->close();
}

// Check if doctor is set and not empty
if (isset($_POST["doctor"]) && !empty($_POST["doctor"])) {
    $doctorId = $_POST["doctor"];

    // Sanitize input
    $doctorId = filter_var($doctorId, FILTER_SANITIZE_NUMBER_INT);

    // Prepare and execute SQL statement
    $stmt = $bd->prepare("SELECT docFees FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Output the consultancy fee directly
        echo htmlentities($row['docFees']);
    }

    $stmt->close();
}
?>
