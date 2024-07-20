<?php
// function check_login()
// {
// if(strlen($_SESSION['dlogin'])==0)
// 	{	
// 		$host = $_SERVER['HTTP_HOST'];
// 		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
// 		$extra="./index.php";		
// 		header("Location: http://$host$uri/$extra");
// 	}
// }
?>

<?php
function check_login()
{
    // Start the session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the session variable 'dlogin' is set and not empty
    if (empty($_SESSION['dlogin'])) {
        // Define the host and URI for redirection
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "index.php";

        // Redirect to login page
        header("Location: http://$host$uri/$extra");
        exit(); // Ensure no further code is executed after redirect
    }
}
?>
