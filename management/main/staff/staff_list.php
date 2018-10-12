<?php

require_once '../../lib/util.php';
require_once  '../../conf/DSN.php';
require_once '../../lib/p.php';
//Session Start
session_start();
session_regenerate_id(true);

if($_SESSION['auth'] !== true){
    header('Location:../staff_login/staff_login.php');
    exit();
}

//get ramdom token of session check for CSRF
    if(! isset($_SESSION['token'])){
        $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

//Variable initialize
$error='';
$page_flag  = 0;

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get staff list
    $sql = 'SELECT id,name,role FROM mst_staff';
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $db = null;   //Disconected DB 

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

//select Edit button
if(isset($_POST['btn_edit'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['staffid'])==false){
		$error = 'Please select a staff.';
	}else{
	$_SESSION['staffid'] = htmlspecialchars($_POST['staffid'], ENT_QUOTES, 'UTF-8');
    header('Location:staff_edit.php');
    }
//Select Delete button
}else if(isset($_POST['btn_delete'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['staffid'])==false){
		$error = 'Please select a staff.';
	}else{
	$_SESSION['staffid'] = htmlspecialchars($_POST['staffid'], ENT_QUOTES, 'UTF-8');
	header('Location:staff_delete.php');
	}
//Select Add button
}else if(isset($_POST['btn_add'])){
	header('Location:staff_add.php');
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
 <h1>Kenkho - Staff list</h1>
 <h3>Hello <?php p::h($_SESSION['name']) ?> !</h3>
 <p><a href="../staff_login/staff_logout.php" class='btnBlue'>Log-out</a></p>
</div>
 <hr>
<div id='pageBody'>
<div class='list'>
<?php if($error!=''):?>
    <ul class="error_list">
    	<li><?php P::h($error); ?></li>
    </ul>
<?php endif; ?>
<form method='post' action='staff_list.php'>
<table>
	<caption>Staff list</caption>
	<thead>
		<tr>
			<th></th>
			<th width=15%>Staff Id</th>
			<th>Staff name</th>
			<th>Role</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $row): ?>
		<tr>
			<td>
			<input type='radio' name='staffid' value="<?php P::h($row['id']) ?>"/></td>
			<td><?php P::h($row['id']) ?></td>
			<td><?php P::h($row['name']) ?></td>
			<td><?php P::h($row['role']) ?></td>
		</tr>
        <?php endforeach; ?>
    </tbody>
</table>
	<input type='submit' class='btnBlue' name='btn_edit' value='Edit' />
	<input type='submit' class='btnRed' name='btn_delete' value='Delete'>
	<input type='submit' class='btnBlue' name='btn_add' value='Add new staff'>
</form>
    <p><a href="../../index.php" class='btnBlue'>Go to Top</a></p>
</div>
</div>
</div>
</body>
</html>