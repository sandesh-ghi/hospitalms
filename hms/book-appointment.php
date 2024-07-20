<?php
session_start();
// Include configuration file
include('include/config.php');
include('include/checklogin.php');
check_login();

// Initialize variables for error and success messages
$error = $success = "";

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve and sanitize user input
    $specilization = $bd->real_escape_string($_POST['Doctorspecialization']);
    $doctorid = $bd->real_escape_string($_POST['doctor']);
    $userid = $_SESSION['id'];
    $fees = $bd->real_escape_string($_POST['fees']);
    $appdate = $bd->real_escape_string($_POST['appdate']);
    $time = $bd->real_escape_string($_POST['apptime']);
    $userstatus = 1;
    $docstatus = 1;

    // Prepare the SQL statement
    $stmt = $bd->prepare("INSERT INTO appointment (doctorSpecialization, doctorId, userId, consultancyFees, appointmentDate, appointmentTime, userStatus, doctorStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssss', $specilization, $doctorid, $userid, $fees, $appdate, $time, $userstatus, $docstatus);

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        $success = "Your appointment was successfully booked.";
    } else {
        $error = "Failed to book appointment: " . $stmt->error;
    }

    // Close the statement
    header("Location:dashboard.php");
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Book Appointment</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script>
    function getdoctor(val) {
        $.ajax({
            type: "POST",
            url: "get_doctor.php",
            data: {specilizationid: val},
            success: function(data) {
                $("#doctor").html(data);
            }
        });
    }

    function getfee(val) {
        $.ajax({
            type: "POST",
            url: "get_doctor.php",
            data: {doctor: val},
            success: function(data) {
                $("#fees").val(data); // Use val() to set the input field value
            }
        });
    }
    </script>
</head>
<body>
    <div id="app">		
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">User | Book Appointment</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>User</span></li>
                                <li class="active"><span>Book Appointment</span></li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row margin-top-30">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Book Appointment</h5>
                                            </div>
                                            <div class="panel-body">
                                                <?php if ($error): ?>
                                                    <p style="color:red;"><?php echo htmlentities($error); ?></p>
                                                <?php endif; ?>
                                                <?php if ($success): ?>
                                                    <p style="color:green;"><?php echo htmlentities($success); ?></p>
                                                <?php endif; ?>
                                                <form role="form" name="book" method="post">
                                                    <div class="form-group">
                                                        <label for="DoctorSpecialization">Doctor Specialization</label>
                                                        <select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required="required">
                                                            <option value="">Select Specialization</option>
                                                            <?php
                                                            $result = $bd->query("SELECT * FROM doctorspecilization");
                                                            while($row = $result->fetch_assoc()) {
                                                                echo '<option value="' . htmlentities($row['specilization']) . '">' . htmlentities($row['specilization']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="doctor">Doctors</label>
                                                        <select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value);" required="required">
                                                            <option value="Select Doctor" </option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="consultancyfees">Consultancy Fees</label>
                                                        <input type="text" name="fees" class="form-control" id="fees" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="AppointmentDate">Date</label>
                                                        <input class="form-control" name="appdate" type="date" required="required">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Appointmenttime">Time</label>
                                                        <input class="form-control" name="apptime" type="time" required="required">
                                                    </div>														
                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php'); ?>
            <?php include('include/setting.php'); ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
</body>
</html>
