<?php

require_once '../../lib/util.php';
require_once  '../../conf/DSN.php';
require_once '../../lib/p.php';

//Variable initialize
$error='';

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get staff list
    $sql = 'SELECT code,name,role FROM mst_staff';
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
	if(isset($_POST['staffcode'])==false){
		$error = 'Please select a staff.';
	}else{
	$staff_code = $_POST['staffcode'];
	header('Location:staff_edit.php?staffcode='.$staff_code);
	exit();
}

//Select Delete button
}else if(isset($_POST['btn_delete'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['staffcode'])==false){
		$error = 'Please select a staff.';
	}else{
	$staff_code = $_POST['staffcode'];
	header('Location:staff_delete.php?staffcode='.$staff_code);
	exit();
}

//Select Delete button
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
</div>
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
			<th width=15%>User code</th>
			<th>User name</th>
			<th>Role</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $row): ?>
		<tr>
			<td>
			<input type='radio' name='staffcode' value="<?php P::h($row['code']) ?>"/></td>
			<td><?php P::h($row['code']) ?></td>
			<td><?php P::h($row['name']) ?></td>
			<td><?php P::h($row['role']) ?></td>
		</tr>
        <?php endforeach; ?>
    </tbody>
</table>
	<input type='submit' class='btnBlue' name='btn_edit' value='Edit'>
	<input type='submit' class='btnRed' name='btn_delete' value='Delete'><br>
	<input type='submit' class='btnBlue' name='btn_add' value='Add new staff'>
</form>
</div>
</div>
</div>

</body>
</html>