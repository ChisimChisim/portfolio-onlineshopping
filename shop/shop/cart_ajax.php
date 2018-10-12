<?php
//Session Start
session_start();
session_regenerate_id(true);

$cart = [];

/* Get data form Javascript to PHP */
if(isset($_POST['cart']) ){
	//XMLHttp header check
    $headers = getallheaders();
    if ($headers["X-Requested-With"] != "XMLHttpRequest") {
        header("HTTP/1.1 403 Forbidden");
        exit;}
       
	$cart = json_decode($_POST['cart'],true);
	$_SESSION['cart'] = $cart;
}