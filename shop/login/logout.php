<?php
session_start();
// finish session
$_SESSION = array();
if(isset($_COOKIE[session_name()]) === true){
   setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();

header('Location:../index.php');
    exit();