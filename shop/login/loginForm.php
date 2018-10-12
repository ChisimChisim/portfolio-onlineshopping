<?php
require_once  '../conf/DSN.php';
require_once '../lib/p.php';
require_once '../lib/dbmodel.php';

//clickjacking defence
header('X-FRAME-OPTIONS: SAMEORIGIN');

//session start
session_start();
session_regenerate_id(true);

//Initialize
$error = '';

//authentication check
if(! isset($_SESSION['auth'])){
	$_SESSION['auth'] = false;
}

if(isset($_POST['email']) && isset($_POST['password'])){
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); 
	$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); 
//DB connection
$dbModel = new DbModel();
$rec = $dbModel->getUserByEmail($email);
if($email=== $rec['email'] && password_verify($password, $rec['password'])){
    //sessionID re-create
    session_regenerate_id(true);
    $_SESSION['auth'] = true;
    $_SESSION['customer_id'] = $rec['id'];
    $_SESSION['email'] = $rec['email'];
    $_SESSION['first_name'] = $rec['first_name'];
    $_SESSION['last_name'] = $rec['last_name'];
    header('Location:../index.php');
    exit();
}
if($_SESSION['auth'] ===false){
	$error = 'Invalid customer id and password.';
}
}

header('Content-Type: text/html; charset=utf-8');
header('X-Context-Type-Options: nonsniff');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Kenkoh</title>
	<link rel="stylesheet" href="../common/css/normalize.css">
	<link rel="stylesheet" href="../common/css/style.css">
	<link rel="stylesheet" href="../common/css/login.css">
</head>
<body>
<div id="page">
	
	<header id="pageHeader">
		<h1 class='siteLogo'>
		   <a href="../index.php"><img src="../images/logo_nav.jpg"></a>
		</h1>
	</header>

<div id="pageBody">
	<div class='loginForm'>
		<section class='loginForm-input'>
			<form method='post' class='registration' action="loginForm.php">
				<h1>Sign-in Form</h1>
				<lable for='email'><b>Email</b></lable>
			    <input type='email' id='email' name='email' placeholder="Your email address" required="required" />
			    <label for='password'><b>Password</b></label>
	            <input type='password' id='password' name='password' placeholder="Your password" required="required" />
	            <br>
	        <input type='submit' class='btnBlue' name='btn_confirm' value='Sign in'>
	        </form>
	    </section>
	    <section class='error_message'>
	      <?php P::h($error); ?>
        </section> 
	</div>

	<a href="../index.php">Back to Top</a> 
</div>

<footer id="pageFooter">
		<P id="copyright"><small>Copyright&copy; 2018 @Kenkoh All Rights Reserved.</small></P>
</footer>

</div>
</body>	
</html>