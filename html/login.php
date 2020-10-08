<?php

include "utils.php";
include "../config.php";
use Dapphp\Radius\Radius;

if($authentication_method == AuthenticationMethod::USTC_CAS)
{
	require_once('USTC_CAS.php');

	session_start();

	if(isset($_SESSION['isadmin']));
		$_SESSION['isadmin']=0;
		
	$isadmin = $_SESSION['isadmin'];

	if (!$isadmin) {
		$cas = ustc_cas_login();
		$user = $cas->user();
		$gid = $cas->gid();
	}

	echo "hello $user/$gid<p>";

	if ( $gid == '3199700722') // james
		$_SESSION['isadmin']=1;

	$isadmin = $_SESSION['isadmin'];

	if(!$isadmin) {
		echo "you not allowed to login, contact james@ustc.edu.cn";	
	} else {
		echo "you are logged in, please go <a href=/admin/> here</a>.";
	}
}
elseif ($authentication_method == AuthenticationMethod::RADIUS)
{
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		// loading RADIUS client
		require_once('radius-2.5.4/autoload.php');

		$client = new Radius();
		$client->setServer($radius_server_ip)
			   ->setSecret($radius_shared_secret);

		if($client->accessRequest($_POST['username'], $_POST['password']))
		{
			session_start();
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['isadmin']=1;

			// Redirects to index
			RedirectTo("index.php");
		}
		else
		{
			// false returned on failure
			echo sprintf(
				"Access-Request failed with error %d (%s).\n",
				$client->getErrorCode(),
				$client->getErrorMessage()
			);
		}
	}

	?>
	
	<!-- This form is only shown when RADIUS authentication is selected -->
	<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"" />
		<title>ExaBGP WEB</title>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="/wwwroot/css/site.css">

		</head>
		<body>
			<div class="container">
				<h2>ExaBGP WEB</h2>
				<form action="login.php" method="POST">
					<label>Username:</label>
					<input type="text" name="username" required>
					<label>Password:</label>
					<input type="password" name="password" required>
					<button type="submit">Login</button>
				</form>
			</div>
		</body>
	</html>
	<?php
}
?>