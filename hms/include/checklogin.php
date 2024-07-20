<?php
// function check_login()
// {
// if(strlen($_SESSION['login'])==0)
// 	{	
// 		$host = $_SERVER['HTTP_HOST'];
// 		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
// 		$extra="./user-login.php";		
// 		header("Location: http://$host$uri/$extra");
// 	}
// }
?>

<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    // Check if user is not logged in
    if (empty($_SESSION['login'])) {
        // Redirect to login page
        $host = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'user-login.php';
        header("Location: http://$host$uri/$extra");
        exit(); // Make sure to exit after redirection
    }
}
?>
