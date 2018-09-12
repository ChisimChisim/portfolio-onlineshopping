<?php
session_start();
// finish session
$_SESSION = array();
if(isset($_COOKIE[session_name()]) === true){
   setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8">
<title>Kenkoh</title>
<link rel="stylesheet" type="text/css" href="../../common/css/normalize.css">
<link rel="stylesheet" type="text/css" href="../../common/css/style.css">
</head>
<body>
<div id='page'>
<div id='pageHeader'>
 <h1>Successfully logged out!</h1>
</div>
<div id='pageBody' >
   <a href="staff_top.php" class='btnBlue'>Go to Log-in form</a> 
</div>
</div>
</body>
</html>