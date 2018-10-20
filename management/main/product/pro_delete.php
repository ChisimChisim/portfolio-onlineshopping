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

$dir = '../../../images/upload/'; //folder for uploaded images

if(!isset($_POST['btn_submit'])){
//Variable initialize
$page_flag = 0;

$pro_code = $_SESSION['procode'];
//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get info of the selected product
     $sql = 'SELECT p.code, p.name, p.price, p.image, u.name as unit, c.name as category FROM mst_product p 
              LEFT JOIN mst_category c ON p.category_code = c.code 
              LEFT JOIN mst_unit u ON p.unit_code = u.code 
              WHERE p.code=?';
    $stmt = $db->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_unit = $rec['unit'];
    $pro_category = $rec['category'];
    $pro_image = $rec['image'];

    $db = null;   //Disconected DB 
    } catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
}
//Click 'Delete'
if(isset($_POST['btn_submit'])){
    //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }


$page_flag = 1;
$pro_code = htmlspecialchars($_POST['delete_code'], ENT_QUOTES, 'UTF-8');

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Delete record
    $sql = 'DELETE FROM mst_product WHERE code=?';
    $stmt = $db -> prepare($sql);
    $data[] = $pro_code;
    $stmt->execute(array($pro_code));
 
    $db = null;   //Disconected DB 

} catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
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

<div id="page">
<div id='pageHeader'>
 <h1>Kenkho - Delete product</h1>
</div>
<div id='pageBody'>
<?php if( $page_flag ==0):?>
<form method='post' class='registration' action='pro_delete.php'>
    <div class='inputText'>
      <header>Product code: <?php  p::h($pro_code); ?></header><br>
                <label for='name'>Product name: </label><br>
                <input id='name' name='name' type='text' value="<?php  p::h($pro_name); ?>" disabled="disabled"/><br>
                <label for='price'>Price: </label><br>
                <input id='price' name='price' type='text' value="<?php  p::h(number_format($pro_price/100,2)); ?>" disabled="disabled"/><br>
                <label for='unit'>Unit: </label><br>
                <input id='unit' name='unit' type='text' value="<?php  p::h($pro_unit); ?>" disabled="disabled"/><br>
                <label for='category'>Category: </label><br>
                <input id='category' name='category' type='text' value="<?php  p::h($pro_category); ?>" disabled="disabled"/><br>
                <img src="<?php p::h($dir); ?><?php p::h($pro_image); ?>" /></br>
    </div>
    <br>
    <p>Would you like to delete the above staff?</p> 
    <a href='pro_list.php' class='btnBlue'>Back</a>
    <input type='submit' class='btnRed' name='btn_submit' value='Delete'>
    <input type="hidden" name="delete_code" value="<?php P::h($pro_code); ?>"/>
    <input type="hidden" name="token" value="<?php P::h($_SESSION['token']); ?>"/>
</form>
<?php else:?>
<p>Delete succeeded!</p>
<br>
<a href='pro_list.php' class='btnBlue'>Back</a>
<?php endif;?>
</div>
</div>
</body>
</html>