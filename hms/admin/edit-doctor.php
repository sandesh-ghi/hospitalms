<?php
session_start();
// Enable error reporting for development (comment out in production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include('include/config.php');
include('include/checklogin.php');
check_login();

$did = intval($_GET['id']); // Get doctor id

if (isset($_POST['submit'])) {
    $docspecialization = $_POST['Doctorspecialization'];
    $docname = $_POST['docname'];
    $docaddress = $_POST['clinicaddress'];
    $docfees = $_POST['docfees'];
    $doccontactno = $_POST['doccontact'];
    $docemail = $_POST['docemail'];

    // Prepare an update statement
    $stmt = $bd->prepare("UPDATE doctors SET specilization = ?, doctorName = ?, address = ?, docFees = ?, contactno = ?, docEmail = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $docspecialization, $docname, $docaddress, $docfees, $doccontactno, $docemail, $did);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor Details updated Successfully');</script>";
    } else {
        echo "<script>alert('Failed to update Doctor Details');</script>";
    }
    header('Location: manage-doctors.php');
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Doctor Details</title>
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
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <?php include('include/header.php'); ?>
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Edit Doctor Details</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Edit Doctor Details</span></li>
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
                                                <h5 class="panel-title">Edit Doctor</h5>
                                            </div>
                                            <div class="panel-body">
                                                <?php
                                                $stmt = $bd->prepare("SELECT * FROM doctors WHERE id = ?");
                                                $stmt->bind_param("i", $did);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($data = $result->fetch_assoc()) {
                                                ?>
                                                <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                                    <div class="form-group">
                                                        <label for="DoctorSpecialization">Doctor Specialization</label>
                                                        <select name="Doctorspecialization" class="form-control" required="required">
                                                            <option value="<?php echo htmlentities($data['specilization']); ?>">
                                                                <?php echo htmlentities($data['specilization']); ?>
                                                            </option>
                                                            <?php
                                                            $ret = $bd->query("SELECT * FROM doctorSpecilization");
                                                            while ($row = $ret->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo htmlentities($row['specilization']); ?>">
                                                                <?php echo htmlentities($row['specilization']); ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="doctorname">Doctor Name</label>
                                                        <input type="text" name="docname" class="form-control" value="<?php echo htmlentities($data['doctorName']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="address">Doctor Clinic Address</label>
                                                        <textarea name="clinicaddress" class="form-control"><?php echo htmlentities($data['address']); ?></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fess">Doctor Consultancy Fees</label>
                                                        <input type="text" name="docfees" class="form-control" required="required" value="<?php echo htmlentities($data['docFees']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fess">Doctor Contact no</label>
                                                        <input type="text" name="doccontact" class="form-control" required="required" value="<?php echo htmlentities($data['contactno']); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fess">Doctor Email</label>
                                                        <input type="email" name="docemail" class="form-control" readonly="readonly" value="<?php echo htmlentities($data['docEmail']); ?>">
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Update</button>
                                                </form>
                                                <?php } $stmt->close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white"></div>
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
