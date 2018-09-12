<?php

require_once '../../lib/util.php';
require_once  '../../conf/DSN.php';
require_once '../../lib/p.php';

//clickjacking defence
header('X-FRAME-OPTIONS: SAMEORIGIN');

//session start
session_start();

//Initialize
$error = '';

//authentication check
if(! isset($_SESSION['auth'])){
	$_SESSION['auth'] = false;
}

if(isset($_POST['staffid']) && isset($_POST['password'])){
	$staffid = htmlspecialchars($_POST['staffid'], ENT_QUOTES, 'UTF-8'); 
	$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); 
//DB connection
   try{
   	$db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get log-in staff
    $sql = 'SELECT id,name, password, role FROM mst_staff WHERE id=?';
    $stmt = $db->prepare($sql);
    $data[] = $staffid;
    $stmt->execute($data);
    $db = null;   //Disconected DB 
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $staff_id = $rec['id'];
    $staff_name = $rec['name'];
    $staff_pass = $rec['password'];
    $staff_role = $rec['role'];

} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

if($staffid === $staff_id && password_verify($password, $staff_pass)){
    //sessionID re-create
    session_regenerate_id(true);
    $_SESSION['auth'] = true;
    $_SESSION['name'] = $staff_name;
    $_SESSION['role'] = $staff_role;
    header('Location:./staff_top.php');
    exit();
}
if($_SESSION['auth'] ===false){
	$error = 'Invalid staff id and password.';
}
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
 <h1>Kenkho - Staff log-in form</h1>
</div>
<div id='pageBody'>

<?php if($error):?>
    <ul class="error_list">
    	<li><?php P::h($error); ?></li>
    </ul>
<?php endif; ?>

<form method='post' class='registration' action="<?php echo p::h($_SESSION['SCRIPT_NAME']) ?>">
	<div class='loginForm'>
		<label for='staffid'>Staff Id: </label><br>
        <input id='staffid' name='staffid' type='text' /><br>
        <label for='password'>Password: </label><br>
        <input id='password' name='password' type='password' /><br>
    </div>
    <input type='submit' class='btnBlue' name='btn_confirm' value='log-in'>
</form>
</div>
</div>
</body>
</html>
