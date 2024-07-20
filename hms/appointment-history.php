<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

if (isset($_GET['cancel'])) {
    $appointment_id = intval($_GET['id']);
    
    // Use prepared statements to prevent SQL injection
    $stmt = $bd->prepare("UPDATE appointment SET userStatus = '0' WHERE id = ?");
    $stmt->bind_param('i', $appointment_id);
    
    if ($stmt->execute()) {
        $_SESSION['msg'] = "Your appointment canceled!";
    } else {
        $_SESSION['msg'] = "Failed to cancel appointment.";
    }
    $stmt->close();
}

// Fetch appointment history using prepared statements
$user_id = intval($_SESSION['id']);
$stmt = $bd->prepare("SELECT doctors.doctorName AS docname, appointment.* 
                       FROM appointment 
                       JOIN doctors ON doctors.id = appointment.doctorId 
                       WHERE appointment.userId = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Appointment History</title>
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
</head>
<body>
<div id="app">
        <?php include('include/sidebar.php');?>
        <div class="app-content">
            <?php include('include/header.php');?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">User | Appointment History</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>User</span></li>
                                <li class="active"><span>Appointment History</span></li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                // Initialize $_SESSION['msg'] if not set
                                if (!isset($_SESSION['msg'])) {
                                    $_SESSION['msg'] = "";
                                }
                                ?>
                                <p style="color:red;"><?php echo htmlentities($_SESSION['msg']); ?></p>
                                <?php $_SESSION['msg'] = ""; // Clear the message ?>
                                
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th class="hidden-xs">Doctor Name</th>
                                            <th>Specialization</th>
                                            <th>Consultancy Fee</th>
                                            <th>Appointment Date / Time</th>
                                            <th>Appointment Creation Date</th>
                                            <th>Current Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                    ?>
                                            <tr>
                                                <td class="center"><?php echo $cnt; ?>.</td>
                                                <td class="hidden-xs"><?php echo htmlspecialchars($row['docname']); ?></td>
                                                <td><?php echo htmlspecialchars($row['doctorSpecialization']); ?></td>
                                                <td><?php echo htmlspecialchars($row['consultancyFees']); ?></td>
                                                <td><?php echo htmlspecialchars($row['appointmentDate']); ?> / <?php echo htmlspecialchars($row['appointmentTime']); ?></td>
                                                <td><?php echo htmlspecialchars($row['postingDate']); ?></td>
                                                <td>
                                                    <?php
                                                    if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) {
                                                        echo "Active";
                                                    } elseif (($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) {
                                                        echo "Cancelled by You";
                                                    } elseif (($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) {
                                                        echo "Cancelled by Doctor";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
                                                            <a href="appointment-history.php?id=<?php echo $row['id']; ?>&cancel=update" onClick="return confirm('Are you sure you want to cancel this appointment?')" class="btn btn-transparent btn-xs tooltips" title="Cancel Appointment">Cancel</a>
                                                        <?php } else {
                                                            echo "Cancelled";
                                                        } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php 
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No records found.</td></tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('include/footer.php');?>
            <?php include('include/setting.php');?>
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
