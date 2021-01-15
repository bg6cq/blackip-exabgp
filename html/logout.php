<?php

if(isset($_SESSION['username']));
{
	// Resume session
	session_start();

	// Erase session variables
	$_SESSION = array();

	// Remove session cookies
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	// Remove session
	session_destroy();
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"" />
<title>ExaBGP WEB</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/wwwroot/css/site.css">

</head>

<body>
	<div class="container">
		You are logged out. Please go <a href=index.php>here</a> to log in again.
	</div>
</body>
</html>
