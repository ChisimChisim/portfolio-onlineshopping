<?php
require_once  '../conf/DSN.php';
require_once '../lib/p.php';
require_once '../lib/dbmodel.php';

//clickjacking defence
header('X-FRAME-OPTIONS: SAMEORIGIN');

//session start
session_start();
session_regenerate_id(true);

//get ramdom token of session check for CSRF
if(! isset($_SESSION['token'])){
        $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

//Initialize
$error = '';

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])){
	$first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'); 
	$last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'); 
	$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); 
	$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
	$password2 = htmlspecialchars($_POST['password2'], ENT_QUOTES, 'UTF-8');  

    if($password!==$password2){
    	$error = 'Passwords must mach.';
    }else{

//DB connection User exist check
$dbModel = new DbModel();
$rec = $dbModel->getUserByEmail($email);
    if($email=== $rec['email']){
    	$error = 'The email is alreay used.';
    }else{
//Encrypted password
     $hash_cost = array('cost' => 10);
     $hash_password = password_hash($password, PASSWORD_DEFAULT, $hash_cost);
//Session check for CSRF
     if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
      }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
      }   

//DB connection User register   
      $dbModel = new DbModel();
      $dbModel->createUser($first_name, $last_name, $email, $hash_password);
      $rec = $dbModel->getUserByEmail($email);
//DB connection Rewards register 
      $dbModel->createRewards($rec['id']);
//login
      session_regenerate_id(true);
      $_SESSION['auth'] = true;
      $_SESSION['customer_id'] = $rec['id'];
      $_SESSION['email'] = $rec['email'];
      $_SESSION['first_name'] = $rec['first_name'];
      $_SESSION['last_name'] = $rec['last_name'];
      header('Location:../shop/myRewards.php');
  }
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
			<form method='post' class='registration' action="createAccount.php">
				<h1>Sign up</h1>
				<lable for='first_name'><b>First name</b></lable>
			    <input type='text' id='first_name' name='first_name' placeholder="First name" required="required" />
			    <lable for='last_name'><b>Last name</b></lable>
			    <input type='text' id='last_name' name='last_name' placeholder="Last name" required="required" />
				<lable for='email'><b>Email</b></lable>
			    <input type='email' id='email' name='email' placeholder="Your email address" required="required" />
			    <label for='password'><b>Password</b></label>
	            <input type='password' id='password' name='password' placeholder="Your password" required="required" />
	            <label for='password2'><b>Re-enter password:</b></label>
	            <input type='password' id='password2' name='password2' required="required" />
	            <br>
	        <input type='submit' class='btnBlue' name='btn_confirm' value='Sign up'>
	        <input type="hidden" name="token" value="<?php P::h($_SESSION['token']); ?>"/>
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