<?php
require_once '../../lib/p.php';

//Session Start
session_start();
session_regenerate_id(true);

if($_SESSION['auth'] !== true){
    header('Location:./staff_login.php');
    exit();
}

header('Content-Type: text/html; charset=utf-8');
header('X-Context-Type-Options: nonsniff');

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
 <h1>Kenkho - Management System</h1>
 <hr>
 <h3>Hello <?php p::h($_SESSION['name']) ?> !</h3>
 <p><a href="staff_logout.php" class='btnBlue'>Log-out</a></p>
</div>
<div id='pageBody' >
    <form method='get' action='../staff/staff_list.php' style='display: inline-block;'>
        <input type='submit' class='btnBlue_lg' value="Staff Management"/>
    </form> 
    <form method='get' action='../product/pro_list.php' style='display: inline-block;'>
        <input type='submit' class='btnGreen_lg' value="Product Management"/>
    </form>  
</div>    
</div>
</body>
</html>
