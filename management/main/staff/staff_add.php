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

//Click 'Comfirm'
if(isset($_POST['btn_confirm'])){
$staff_id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
$staff_name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$staff_pass1 = htmlspecialchars($_POST['pass1'], ENT_QUOTES, 'UTF-8');
$staff_pass2 = htmlspecialchars($_POST['pass2'], ENT_QUOTES, 'UTF-8');
$staff_role = htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8');


//Validation
if($staff_id==''){
    $error[] = 'Enter staff Id.';
}
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

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Insert new record
    $sql = 'SELECT name FROM mst_staff WHERE id=?';
    $stmt = $db -> prepare($sql);
    $data[] = $staff_id;
    $stmt->execute($data);

    $db = null;   //Disconected DB 
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if($rec){
        $error[] = 'The staff ID already exists.';
    }


} catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

if(empty($error)){
    $page_flag = 1;
    //Encrypted password
    $hash_cost = array('cost' => 10);
    $staff_pass = password_hash($staff_pass1, PASSWORD_DEFAULT, $hash_cost);
  }

}elseif(isset($_POST['btn_submit'])){
    //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }

    $page_flag = 2;
    $staff_id = htmlspecialchars($_POST['reg_id'], ENT_QUOTES, 'UTF-8');
    $staff_name = htmlspecialchars($_POST['reg_name'], ENT_QUOTES, 'UTF-8');
    $staff_pass = htmlspecialchars($_POST['reg_pass'], ENT_QUOTES, 'UTF-8');
    $staff_role = htmlspecialchars($_POST['reg_role'], ENT_QUOTES, 'UTF-8');
//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Insert new record
    $sql = 'INSERT INTO mst_staff(id, name,password, role) VALUES (?,?,?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $staff_id;
    $data[] = $staff_name;
    $data[] = $staff_pass;
    $data[] = $staff_role;
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
 <h1>Kenkho - Staff registration</h1>
</div>
<div id='pageBody'>
    
        <?php if( $page_flag === 0):?>
            <?php if( !empty($error) ): ?>
                <ul class="error_list">
                    <?php foreach( $error as $value ): ?>
                        <li><?php p::h($value); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>


        <form method='post' class='registration' action='staff_add.php'>
            <div class='inputText'>
                <label for='id'>Staff Id: </label><br>
                <input id='id' name='id' type='text' /><br>
                <label for='name'>Staff name: </label><br>
                <input id='name' name='name' type='text' /><br>
                <label for='pass1'>Password: </label><br>
                <input id='pass1' name='pass1' type='password' pattern="{6,}" placeholder="At least 6 characters" /><br>
                <label for='pass2'>Re-enter password: </label><br>
                <input id='pass2' name='pass2' type='password'/><br>
                <label for='role'>Role: </label><br>
                <select id='role' name='role'>
                    <option value="STAFF">STAFF</option>
                    <option value="MANAGER">MANAGER</option>
                </select>
            </div>
                <a href='staff_list.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_confirm' value='Confirm'>
        </form>

        <?php elseif( $page_flag === 1):?>
            <form method='post' class='registration' action='staff_add.php'>
            <div class='inputText'>
                <label for='id'>Staff name: </label><br>
                <input id='id' name='id' type='text' value="<?php p::h($staff_id); ?>" disabled="disabled"/><br>
                <label for='name'>Staff name: </label><br>
                <input id='name' name='name' type='text' value="<?php p::h($staff_name); ?>" disabled="disabled"/><br>
                <label for='pass1'>Password: </label><br>
                <input id='pass1' name='pass1' type='password' value="<?php P::h($staff_pass1); ?>" disabled="disabled"/><br>
                <label for='role'>Role: </label><br>
                <input id='role' name='role' type='text' value="<?php P::h($staff_role); ?>" disabled="disabled"/><br>
            </div>
            <br>
            <p>Would you like to register the above staff?</p> 
                <a href='staff_add.php' class='btnBlue'>Back</a>
                <input type='submit' class='btnBlue' name='btn_submit' value='Submit'>
                <input type="hidden" name="reg_id" value="<?php p::h($staff_id); ?>"/>
                <input type="hidden" name="reg_name" value="<?php p::h($staff_name); ?>"/>
                <input type="hidden" name="reg_pass" value="<?php p::h($staff_pass); ?>"/>
                <input type="hidden" name="reg_role" value="<?php p::h($staff_role); ?>"/>
                <input type="hidden" name="token" value="<?php P::h($_SESSION['token']); ?>"/>
        </form>

       <?php elseif( $page_flag === 2):?>
        <p>Registration succeeded!</p>
        <br>
           <a href='staff_list.php' class='btnBlue'>Back</a>
       <?php endif;?>

</div>
</div>
</body>
</html>