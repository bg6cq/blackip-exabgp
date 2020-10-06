<?php

// //require_once('USTC_CAS.php');

// session_start();

// if(isset($_SESSION['isadmin']));
// 	 $_SESSION['isadmin']=0;
	 
// $isadmin = $_SESSION['isadmin'];

// // if (!$isadmin) {
// //     $cas = ustc_cas_login();
// //     $user = $cas->user();
// //     $gid = $cas->gid();
// // }
// $user = "Wallace";
// $gid = '3199700722';

// echo "hello $user/$gid<p>";

// // if ( $gid == '3199700722') // james
// // 	$_SESSION['isadmin']=1;

// $isadmin = 0;
// $_SESSION['isadmin']=0;

// if(!$isadmin) {
// 	echo "you not allowed to login, contact james@ustc.edu.cn";	
// } else {
// 	echo "you are logged in, please go <a href=/admin/> here</a>.";
// }

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if($_POST['username'] == 'wallace' && $_POST['password'] == '1234')
	{
		echo $_POST['username'];
		session_start();
		$_SESSION['username'] = 'wallace';
		$_SESSION['isadmin']=1;

		// Redirects to index
		header("Location: index.php");
		die();
	}
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

