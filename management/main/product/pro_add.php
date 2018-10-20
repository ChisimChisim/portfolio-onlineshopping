<?php

require_once '../../lib/util.php';
require_once '../../lib/p.php';
require_once '../../lib/fileCheck.php';
require_once  '../../conf/DSN.php';

//Session Start
session_start();
session_regenerate_id(true);

if($_SESSION['auth'] !== true){
    header('Location:../staff_login/staff_login.php');
    exit();
}

//Variable initialize
$page_flag = 0;
$error = array();
$error_msg = array();
$dir = '../../../images/upload'; //folder for uploaded images

//DB connection to get category
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    $db = null;   //Disconected DB 
    
} catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}


//Click 'Comfirm'
if(isset($_POST['btn_confirm'])){

$pro_name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$pro_price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
$pro_unit = explode(';', htmlspecialchars($_POST['unit'], ENT_QUOTES, 'UTF-8'));
$pro_category = explode(';', htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'));
$pro_image = $_FILES['image'];

//Validation
if($pro_name==''){
    $error[] = 'Enter product name.';
}
if($pro_price==''){
    $error[] = 'Enter price.';
}elseif (preg_match('/^([1-9][0-9]*|0)(\.[0-9]{0,2})?$/', $pro_price)==0) {
    $error[] = 'Invlid valuj for price.';
}

/**Start uploaded file**/
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
list($result, $ext, $error_msg) = $fileCheck->checkFile($pro_image, MAX_SIZE);
if($result){
    $new_file = '';
    $move_to ='';

    $image_name = $pro_image['name'];
    $tmp_name = $pro_image['tmp_name'];
    //New file name = current time + "-" + MD5 of current microtime and file name and IP address. 
    $new_file = time() . '_' . md5(microtime() . $image_name . $_SERVER['REMOTE_ADDR']) . '.' . $ext;
    $move_to = $dir . '/' . $new_file;
    //Move file to folder.
    if(move_uploaded_file($tmp_name,  $move_to)){
    }else{
        $error[] = 'File upload error';
    }
 }else{
    $error = $error + $error_msg;
 }   

if(empty($error)){
    $page_flag = 1;
  }

}elseif(isset($_POST['btn_submit'])){
     //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }

    $page_flag = 2;
    $pro_name = htmlspecialchars($_POST['reg_name'], ENT_QUOTES, 'UTF-8');
    $pro_price = htmlspecialchars($_POST['reg_price'], ENT_QUOTES, 'UTF-8');
    $pro_unit = htmlspecialchars($_POST['reg_unit'], ENT_QUOTES, 'UTF-8');
    $pro_category = htmlspecialchars($_POST['reg_category'], ENT_QUOTES, 'UTF-8');
    $pro_image = htmlspecialchars($_POST['reg_image'], ENT_QUOTES, 'UTF-8');

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Insert new record
    $sql = 'INSERT INTO mst_product(name,price,unit_code,image,category_code) VALUES (?,?,?,?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $pro_name;
    $data[] = $pro_price*100;
    $data[] = $pro_unit;
    $data[] = $pro_image;
    $data[] = $pro_category;
    $stmt->execute($data);

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
 <h1>Kenkho - Product registration</h1>
</div>
<div id='pageBody'>
    
        <?php if( $page_flag === 0):?>
            <?php if( !empty($error) ): ?>
                <ul class="error_list">
                    <?php foreach( $error as $value ): ?>
                        <li><?php P::h($value); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>


        <form method='post' class='registration' action='pro_add.php' enctype="multipart/form-data">
            <div class='inputText'>
                <label for='name'>Product name: </label><br>
                <input id='name' name='name' type='text' /><br>
                <label for='price'>Price: </label><br>
                <input id='price' name='price' type='text'/><br>
                <label for='unit'>Unit: </label><br>
                <select id='unit' name='unit'>
                    <?php foreach($unit as $row): ?>
                    <option value ="<?php P::h($row['code']) ?>;<?php P::h($row['name']) ?>"><?php P::h($row['name']) ?></option> <?php endforeach; ?>
                </select><br>
                <label for='category'>Category: </label><br>
                <select id='category' name='category'>
                    <?php foreach($category as $row): ?>
                    <option value ="<?php P::h($row['code']) ?>;<?php P::h($row['name']) ?>"><?php P::h($row['name']) ?></option> <?php endforeach; ?>
                </select><br>
                <label for='image'>Image(Recommened size -> W:400xH:250pixels. File size is up to 1M byte): </label><br>
                <input type='file' id='image' name='image'/>
            </div>
                <a href='pro_list.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_confirm' value='Confirm'>
        </form>

        <?php elseif( $page_flag === 1):?>
            <form method='post' class='registration' action='pro_add.php'>
            <div class='inputText'>
                <label for='name'>Product name: </label><br>
                <input id='name' name='name' type='text' value="<?php P::h($pro_name); ?>" disabled="disabled"/><br>
                <label for='price'>Price: </label><br>
                <input id='price' name='price' type='text' value="<?php p::h($pro_price); ?>" disabled="disabled"/><br>
                <label for='unit'>Unit: </label><br>
                <input id='unit' name='unit' type='text' value="<?php p::h($pro_unit[1]); ?>" disabled="disabled"/><br>
                <label for='category'>Category: </label><br>
                <input id='category' name='category' type='text' value="<?php p::h($pro_category[1]); ?>" disabled="disabled"/><br>
                <img src="<?php p::h($move_to); ?>" />
             </div>   
            </div>
            <br>
            <p>Would you like to register the above product?</p> 
                <a href='pro_add.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_submit' value='Submit'>
                <input type="hidden" name="reg_name" value="<?php p::h($pro_name); ?>"/>
                <input type="hidden" name="reg_price" value="<?php p::h($pro_price); ?>"/>
                <input type="hidden" name="reg_unit" value="<?php p::h($pro_unit[0]); ?>"/>
                <input type="hidden" name="reg_category" value="<?php p::h($pro_category[0]); ?>"/>
                <input type="hidden" name="reg_image" value="<?php p::h($new_file); ?>"/>
                <input type="hidden" name="token" value="<?php P::h($_SESSION['token']); ?>"/>
        </form>

       <?php elseif( $page_flag === 2):?>
        <p>Registration succeeded!</p>
        <br>
           <a href='pro_list.php' class='btnBlue'>Back</a>
       <?php endif;?>

</div>
</div>
</body>
</html>