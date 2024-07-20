<?php
session_start();

// $_SESSION['id'] = $doctorId; // Store the doctor's ID in the session
// $_SESSION['username'] = $doctorName; // Store the doctor's name in the session

include("include/config.php");

if (isset($_POST['submit'])) {
    // Retrieve user input
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Encrypt password

    // Prepare and execute SQL statement to prevent SQL injection
    $stmt = $bd->prepare("SELECT * FROM doctors WHERE docEmail = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $num = $result->fetch_assoc();
        $extra = "dashboard.php";
        $_SESSION['dlogin'] = $username;
        $_SESSION['id'] = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        // Log successful login
        $log_stmt = $bd->prepare("INSERT INTO doctorslog (uid, username, userip, status) VALUES (?, ?, ?, ?)");
        $log_stmt->bind_param("issi", $_SESSION['id'], $_SESSION['dlogin'], $uip, $status);
        $log_stmt->execute();

        header("Location: $extra");
        exit();
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;

        // Log failed login attempt
        $log_stmt = $bd->prepare("INSERT INTO doctorslog (username, userip, status) VALUES (?, ?, ?)");
        $log_stmt->bind_param("ssi", $username, $uip, $status);
        $log_stmt->execute();

        $_SESSION['errmsg'] = "Invalid username or password";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Login</title>
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
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body class="login">
    <div class="row">
        <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="logo margin-top-30">
                <h2> HMS | Doctor Login</h2>
            </div>
            <div class="box-login">
                <form class="form-login" method="post">
                    <fieldset>
                        <legend>Sign in to your account</legend>
                        <p>
                            Please enter your username and password to log in.<br />
                            <!-- Check if the session message is set and not empty -->
                            <span style="color:red;">
                                <?php 
                                    // Use a default empty string if 'errmsg' is not set
                                    echo isset($_SESSION['errmsg']) ? $_SESSION['errmsg'] : ''; 
                                    // Clear the message after displaying
                                    $_SESSION['errmsg'] = ""; 
                                ?>
                            </span>
                        </p>
                        <div class="form-group">
                            <span class="input-icon">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                                <i class="fa fa-user"></i> 
                            </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                <input type="password" class="form-control password" name="password" placeholder="Password" required>
                                <i class="fa fa-lock"></i>
                            </span>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary pull-right" name="submit">
                                Login <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </fieldset>
                </form>
                <div class="copyright">
                    &copy; <span class="current-year"></span><span class="text-bold text-uppercase"> HMS</span>. <span>All rights reserved</span>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/login.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            Login.init();
        });
    </script>
</body>
</html>
