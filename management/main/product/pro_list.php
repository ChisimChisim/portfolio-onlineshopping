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

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get staff list
    $sql = 'SELECT p.code, p.name, p.price, u.name as unit, c.name as category FROM mst_product p 
    LEFT JOIN mst_category c ON p.category_code = c.code
    LEFT JOIN mst_unit u ON p.unit_code = u.code';
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $db = null;   //Disconected DB 

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

//select Detail button
if(isset($_POST['btn_detail'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['procode'])==false){
		$error = 'Please select a product.';
	}else{
	$_SESSION["procode"] = htmlspecialchars($_POST['procode'], ENT_QUOTES, 'UTF-8');
    header('Location:pro_detail.php');
}

//select Edit button
}elseif(isset($_POST['btn_edit'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['procode'])==false){
		$error = 'Please select a product.';
	}else{
	$_SESSION["procode"] = htmlspecialchars($_POST['procode'], ENT_QUOTES, 'UTF-8');
	header('Location:pro_edit.php');
}

//Select Delete button
}else if(isset($_POST['btn_delete'])){
	//if radio button is not selected ==> error message
	if(isset($_POST['procode'])==false){
		$error = 'Please select a product.';
	}else{
	$_SESSION["procode"] = htmlspecialchars($_POST['procode'], ENT_QUOTES, 'UTF-8');
	header('Location:pro_delete.php');
}

//Select Add button
}else if(isset($_POST['btn_add'])){
	header('Location:pro_add.php');
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
 <h1>Kenkho - Product list</h1>
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
<form method='post' action='pro_list.php'>
<table>
	<caption>Product list</caption>
	<thead>
		<tr>
			<th></th>
			<th width=15%>Product code</th>
			<th>Product name</th>
			<th>Price</th>
			<th>Unit</th>
			<th>Category</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $row): ?>
		<tr>
			<td>
			<input type='radio' name='procode' value="<?php P::h($row['code']) ?>"/></td>
			<td><?php P::h($row['code']) ?></td>
			<td><?php P::h($row['name']) ?></td>
			<td><?php P::h($row['price']) ?></td>
			<td><?php P::h($row['unit']) ?></td>
			<td><?php P::h($row['category']) ?></td>
		</tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <input type='submit' class='btnBlue' name='btn_detail' value='Detail'>
	<input type='submit' class='btnBlue' name='btn_edit' value='Edit'>
	<input type='submit' class='btnRed' name='btn_delete' value='Delete'><br>
	<input type='submit' class='btnBlue' name='btn_add' value='Add new product'>
</form>
<p><a href="../../index.php" class='btnBlue'>Go to Top</a></p>
</div>
</div>
</div>

</body>
</html>