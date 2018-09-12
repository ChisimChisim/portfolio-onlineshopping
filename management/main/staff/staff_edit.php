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

//Variable initialize
$page_flag = 0;
$error = array();

if(!isset($_POST['btn_submit'])){

$staff_id = $_SESSION['staffid'];
//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get the selected staff
    $sql = 'SELECT name, role FROM mst_staff WHERE id=?';
    $stmt = $db->prepare($sql);
    $data[] = $staff_id;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $staff_name = $rec['name'];
    $staff_role = $rec['role'];

    $db = null;   //Disconected DB 
    } catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
}
//Click 'Comfirm'
if(isset($_POST['btn_confirm'])){

$staff_id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
$staff_name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$staff_pass1 = htmlspecialchars($_POST['pass1'], ENT_QUOTES, 'UTF-8');
$staff_pass2 = htmlspecialchars($_POST['pass2'], ENT_QUOTES, 'UTF-8');
$staff_role = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');

//Validation
if($staff_name==''){
    $error[] = 'Enter staff name.';
}
if($staff_pass1==''){
    $error[] = 'Enter password.';
}else if(mb_strlen($staff_pass1)<6){
    $error[] = 'Passwords must be at least 6 characters.';
}
if($staff_pass1!=$staff_pass2){
    $error[] = 'Passwords must mach.';
}

if(empty($error)){
    $page_flag = 1;
    //Encrypted password
    $hash_cost = array('cost' => 10);
    $staff_pass = password_hash($staff_pass1, PASSWORD_DEFAULT, $hash_cost);
  }

}else if(isset($_POST['btn_submit'])){
    //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }

    $page_flag = 2;
    $staff_id = htmlspecialchars($_POST['edit_id'], ENT_QUOTES, 'UTF-8');
    $staff_name = htmlspecialchars($_POST['edit_name'], ENT_QUOTES, 'UTF-8');
    $staff_pass = htmlspecialchars($_POST['edit_pass'], ENT_QUOTES, 'UTF-8');
    $staff_role = htmlspecialchars($_POST['edit_role'], ENT_QUOTES, 'UTF-8');

//DB connection

try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Update record
    $sql = 'UPDATE mst_staff SET name=?,password=?,role=? WHERE id=?';
    $stmt = $db -> prepare($sql);
    $stmt->execute(array($staff_name, $staff_pass, $staff_role, $staff_id));

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
 <h1>Kenkho - Edit staff</h1>
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

   <form method='post' class='registration' action='staff_edit.php'>
    <div class='inputText'>
        <header>Staff id: <?php p::h($staff_id); ?></header><br>
        <label for='name'>Staff name: </label><br>
        <input id='name' name='name' type='text' value="<?php  p::h($staff_name); ?>"/><br>
        <label for='pass1'>Password: </label><br>
        <input id='pass1' name='pass1' type='password' pattern="{6,}" placeholder="At least 6 characters" /><br>
        <label for='pass2'>Re-enter password: </label><br>
        <input id='pass2' name='pass2' type='password'/><br>
        <label for='role'>Role: </label><br>
        <select id='role' name='role'>
         <?php 
             $Options = array('STAFF'=>'STAFF', 'MANAGER'=>'MANAGER');
             foreach ($Options as $key => $value) {
                 $selected='';
                 if($key===$staff_role){
                    $selected='selected';
                }
                print "<option value=$key $selected>$value</option>\n";
            }
           ?>
        </select>
    </div>
    <a href='staff_list.php' class='btnBlue'>Back</a>
    <input type='submit' class='btnBlue' name='btn_confirm' value='Confirm'>
    <input type="hidden" name="id" value="<?php  p::h($staff_id); ?>"/>

</form>
 <?php elseif( $page_flag == 1):?>
            <form method='post' class='registration' action='staff_edit.php'>
            <div class='inputText'>
                <header>Staff id: <?php  p::h($staff_id); ?></header><br>
                <label for='name'>Staff name: </label><br>
                <input id='name' name='name' type='text' value="<?php  p::h($staff_name); ?>" disabled="disabled"/><br>
                <label for='pass1'>Password: </label><br>
                <input id='pass1' name='pass1' type='password' value="<?php  p::h($staff_pass1); ?>" disabled="disabled"/><br>
                <label for='role'>Role: </label><br>
                <input id='role' name='role' type='text' value="<?php  p::h($staff_role); ?>" disabled="disabled"/><br>
            </div>
            <br>
            <p>Would you like to update the above staff?</p> 
                <a href='staff_list.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_submit' value='Submit'>
                <input type="hidden" name="edit_id" value="<?php  p::h($staff_id); ?>"/>
                <input type="hidden" name="edit_name" value="<?php  p::h($staff_name); ?>"/>
                <input type="hidden" name="edit_pass" value="<?php  p::h($staff_pass); ?>"/>
                <input type="hidden" name="edit_role" value="<?php  p::h($staff_role); ?>"/>
                <input type="hidden" name="token" value="<?php  p::h($_SESSION['token']); ?>"/>
            </form>
        <?php elseif( $page_flag == 2):?>
        <p>Update succeeded!</p>
        <br>
            <a href='staff_list.php' class='btnBlue'>Back</a>

       <?php endif;?>
</div>
</div>
</body>
</html>