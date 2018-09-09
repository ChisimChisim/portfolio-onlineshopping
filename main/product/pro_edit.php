<?php
require_once '../../lib/util.php';
require_once  '../../conf/DSN.php';
require_once '../../lib/p.php';
require_once '../../lib/fileCheck.php';

//Session Start
session_start();

//Variable initialize
$page_flag = 0;
$error = array();
$error_msg = array();
$dir = '../../images/upload/'; //folder for uploaded images

if(isset($_GET['procode']) && $_GET['procode'] == True){
    //get ramdom token of session check for CSRF
    if(! isset($_SESSION['token'])){
        $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }

$pro_code = htmlspecialchars($_GET['procode'], ENT_QUOTES, 'UTF-8');
//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get info of the selected product
    $sql = 'SELECT p.code, p.name, p.price, p.unit_code, u.name as unit, p.image, p.category_code, c.name as category FROM mst_product p 
    LEFT JOIN mst_category c ON p.category_code = c.code 
    LEFT JOIN mst_unit u on p.unit_code = u.code WHERE p.code=?';
    $stmt = $db->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_unit = $rec['unit'];
    $pro_oldImage = $rec['image'];
    $pro_unitCode = $rec['unit_code'];
    $pro_unitName = $rec['unit'];
    $pro_categoryCode = $rec['category_code'];
    $pro_categoryName = $rec['category'];

    //Get category list
    $sql = 'SELECT code,name FROM mst_category';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $category = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Get Unit list
    $sql = 'SELECT code,name FROM mst_unit';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $unit = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Disconected DB 
    $db = null;   

    } catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
}
//Click 'Comfirm'
if(isset($_POST['btn_confirm'])){

$pro_code = htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
$pro_name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$pro_price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
$pro_unit = explode(';', htmlspecialchars($_POST['unit'], ENT_QUOTES, 'UTF-8'));
$pro_category = explode(';', htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'));
$pro_oldImage = htmlspecialchars($_POST['oldImage'], ENT_QUOTES, 'UTF-8');
$pro_newImage = $_FILES['newImage'];

//Validation
if($pro_name==''){
    $error[] = 'Enter product name.';
}
if($pro_price==''){
    $error[] = 'Enter price.';
}elseif (preg_match('/^([1-9][0-9]*|0)(\.[0-9]{0,2})?$/', $pro_price)==0) {
    $error[] = 'Invlid valuj for price.';
}
if($pro_newImage['name']==''){
    $move_to = $dir . $pro_oldImage;
    $new_file = '';
}elseif($pro_newImage != $pro_oldImage){
/**Start uploaded file check**/
define('MAX_SIZE', 1000000);  //image max size
$fileCheck = new fileCheck();
$new_file = null;
$move_to =null;
$image_name = null;
$tmp_name = null;
//File size check for post_max_size(php.ini)
if(! $fileCheck->checkPostMaxSize()){
    $error[] = 'File size is too large.';
}
//
list($result, $ext, $error_msg) = $fileCheck->checkFile($pro_newImage, MAX_SIZE);
if($result){
    $image_name = $pro_newImage['name'];
    $tmp_name = $pro_newImage['tmp_name'];
    //New file name = current time + "-" + MD5 of current microtime and file name and IP address. 
    $new_file = time() . '_' . md5(microtime() . $image_name . $_SERVER['REMOTE_ADDR']) . '.' . $ext;
    $move_to = $dir . $new_file;
    //Move file to folder.
    if(move_uploaded_file($tmp_name,  $move_to)){
    }else{
        $error[] = 'File upload error';
    }
 }else{
    $error = $error + $error_msg;
 }   
}else{
    $move_to = $dir . $pro_oldImage;
    $new_file = '';
}

if(empty($error)){
    $page_flag = 1;
  }

}else if(isset($_POST['btn_submit'])){
    //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }
    //close session
    killSession();

    $page_flag = 2;
    $pro_code = htmlspecialchars($_POST['edit_code'], ENT_QUOTES, 'UTF-8');
    $pro_name = htmlspecialchars($_POST['edit_name'], ENT_QUOTES, 'UTF-8');
    $pro_price = htmlspecialchars($_POST['edit_price'], ENT_QUOTES, 'UTF-8');
    $pro_unit = htmlspecialchars($_POST['edit_unit'], ENT_QUOTES, 'UTF-8');
    $pro_category = htmlspecialchars($_POST['edit_category'], ENT_QUOTES, 'UTF-8');
    $pro_image = htmlspecialchars($_POST['edit_image'], ENT_QUOTES, 'UTF-8');
    $old_image = htmlspecialchars($_POST['old_image'], ENT_QUOTES, 'UTF-8');

//DB connection

try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Update record
    if ($pro_image == ''){
        $sql = 'UPDATE mst_product SET name=?,price=?,unit_code=?, category_code=? WHERE code=?';
        $stmt = $db -> prepare($sql);
        $stmt->execute(array($pro_name, $pro_price, $pro_unit, $pro_category, $pro_code));
    }else{
        $sql = 'UPDATE mst_product SET name=?,price=?,unit_code=?, category_code=?, image=? WHERE code=?';
        $stmt = $db -> prepare($sql);
        $stmt->execute(array($pro_name, $pro_price, $pro_unit, $pro_category, $pro_image, $pro_code));
        //Delete old image
        unlink($dir . '/' . $old_image);
    }


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
 <h1>Kenkho - Edit product</h1>
</div>
<div id='pageBody'>

      <?php if( $page_flag == 0):?>
            <?php if( !empty($error) ): ?>
                <ul class="error_list">
                    <?php foreach( $error as $value ): ?>
                        <li><?php p::h($value); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

   <form method='post' class='registration' action='pro_edit.php' enctype="multipart/form-data">
    <div class='inputText'>
        <header>Product code: <?php p::h($pro_code); ?></header><br>
        <label for='name'>Product name: </label><br>
        <input id='name' name='name' type='text' value="<?php  p::h($pro_name); ?>"/><br>
        <label for='price'>Price: </label><br>
        <input id='price' name='price' type='text' value="<?php  p::h($pro_price); ?>"/><br>
        <label for='unit'>Unit: </label><br>
        <select id='unit' name='unit'>
            <?php foreach($unit as $row): ?>
                <?php $selected='';
                if($row['code']===$pro_unitCode){
                    $selected='selected';
                } ?>
                <option value ="<?php P::h($row['code']) ?>;<?php P::h($row['name']) ?>" <?php P::h($selected) ?> ><?php P::h($row['name']) ?></option> 
            <?php endforeach; ?>
        </select><br>
        <label for='category'>Category: </label><br>
        <select id='category' name='category'>
            <?php foreach($category as $row): ?>
                <?php $selected='';
                if($row['code']===$pro_categoryCode){
                    $selected='selected';
                } ?>
                <option value ="<?php P::h($row['code']) ?>;<?php P::h($row['name']) ?>" <?php P::h($selected) ?> ><?php P::h($row['name']) ?></option> 
            <?php endforeach; ?>
        </select><br>
        <label for='newImage'>Image(file size is up to 1M byte): </label></br>
        <input type='file' id='newImage' name='newImage'/></br>
        <img src="<?php p::h($dir); ?><?php p::h($pro_oldImage); ?>" /></br>
    </div>
    <a href='pro_list.php' class='btnBlue'>Back</a>
    <input type='submit' class='btnBlue' name='btn_confirm' value='Confirm'>
    <input type="hidden" name="code" value="<?php  p::h($pro_code); ?>"/>
    <input type="hidden" name="oldImage" value="<?php  p::h($pro_oldImage); ?>"/>

</form>
 <?php elseif( $page_flag == 1):?>
            <form method='post' class='registration' action='pro_edit.php'>
            <div class='inputText'>
                <header>Product code: <?php  p::h($pro_code); ?></header><br>
                <label for='name'>Product name: </label><br>
                <input id='name' name='name' type='text' value="<?php  p::h($pro_name); ?>" disabled="disabled"/><br>
                <label for='price'>Price: </label><br>
                <input id='price' name='price' type='text' value="<?php  p::h($pro_price); ?>" disabled="disabled"/><br>
                <label for='unit'>Unit: </label><br>
                <input id='unit' name='unit' type='text' value="<?php  p::h($pro_unit[1]); ?>" disabled="disabled"/><br>
                <label for='category'>Catetgory: </label><br>
                <input id='category' name='category' type='text' value="<?php  p::h($pro_category[1]); ?>" disabled="disabled"/><br>
                <img src="<?php p::h($move_to); ?>" />
            </div>
            <br>
            <p>Would you like to update the above product?</p> 
                <a href='pro_list.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_submit' value='Submit'>
                <input type="hidden" name="edit_code" value="<?php  p::h($pro_code); ?>"/>
                <input type="hidden" name="edit_name" value="<?php  p::h($pro_name); ?>"/>
                <input type="hidden" name="edit_price" value="<?php  p::h($pro_price); ?>"/>
                <input type="hidden" name="edit_unit" value="<?php  p::h($pro_unit[0]); ?>"/>
                <input type="hidden" name="edit_category" value="<?php  p::h($pro_category[0]); ?>"/>
                <input type="hidden" name="edit_image" value="<?php p::h($new_file); ?>"/>
                <input type="hidden" name="old_image" value="<?php p::h($pro_oldImage); ?>"/>
                <input type="hidden" name="token" value="<?php  p::h($_SESSION['token']); ?>"/>
            </form>
        <?php elseif( $page_flag == 2):?>
        <p>Update succeeded!</p>
        <br>
            <a href='pro_list.php' class='btnBlue'>Back</a>

       <?php endif;?>
</div>
</div>
<script src="./common/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="./common/js/staff.js"></script>
</body>
</html>