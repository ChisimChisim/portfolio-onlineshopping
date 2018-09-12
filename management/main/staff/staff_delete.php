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
//Click 'Delete'
if(isset($_POST['btn_submit'])){
    //Session check for CSRF
    if(! isset($_POST['token'])){
        exit('Invalid or missing CSRF token1');
    }else if($_POST['token'] != $_SESSION['token']){
        exit('Invalid or missing CSRF token2');
    }


$page_flag = 1;
$staff_id = htmlspecialchars($_POST['delete_code'], ENT_QUOTES, 'UTF-8');

//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //Update record
    $sql = 'DELETE FROM mst_staff WHERE id=?';
    $stmt = $db -> prepare($sql);
    $data[] = $staff_id;
    $stmt->execute(array($staff_id));
 
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
 <h1>Kenkho - Delete staff</h1>
</div>
<div id='pageBody'>
<?php if( $page_flag ==0):?>
<form method='post' class='registration' action='staff_delete.php'>
    <div class='inputText'>
        <header>Staff id: <?php echo $staff_id; ?></header><br>
        <label for='name'>Staff name: </label><br>
        <input id='name' name='name' type='text' value="<?php p::h($staff_name); ?>" disabled="disabled"/><br>
        <label for='role'>Role: </label><br>
        <input id='role' name='role' type='text' value="<?php P::h($staff_role); ?>" disabled="disabled"/><br>
    </div>
    <br>
    <p>Would you like to delete the above staff?</p> 
    <a href='staff_list.php' class='btnBlue'>Back</a>
    <input type='submit' class='btnRed' name='btn_submit' value='Delete'>
    <input type="hidden" name="delete_code" value="<?php P::h($staff_id); ?>"/>
    <input type="hidden" name="token" value="<?php P::h($_SESSION['token']); ?>"/>
</form>
<?php else:?>
<p>Delete succeeded!</p>
<br>
<a href='staff_list.php' class='btnBlue'>Back</a>
<?php endif;?>
</div>
</div>
</html>