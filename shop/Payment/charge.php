<?php
  require_once './config.php';
  require_once '../lib/dbmodel.php';

//session start
session_start();
session_regenerate_id(true);

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $total  = intval($_POST['totalPrice']);
  $address = json_decode($_POST['address'],true);

try{
  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $total,
      'currency' => 'USD'
  ));
}catch(\Stripe\Error\Card $e) {
   die('Payment process error');
}
  

/******** Resister&Update DB *******/
$cart = [];
$total = 0;
$qty = 0;
$points = 0;

$cart=$_SESSION['cart'];

//Register the order to DB
for($i=0; $i<count($cart); $i++) {
  $total = $total + ($cart[$i]['price'] * (int)$cart[$i]['qty']);
  $qty = $qty + (int)$cart[$i]['qty'];
  }

//DB connection
//Register order and orderline to DB
$dbModel = new DbModel();
$order_id = $dbModel->createOrderList($email, $total, $token, $cart);
//Register shipping Address to DB
$dbModel->createShippingAddress($order_id, $address);
//Update user reward to DB if the user log in
if(isset($_SESSION['auth']) && $_SESSION['auth']===true){
  $rewards = $dbModel->getRewards($_SESSION['customer_id']);

  $points = (floor($total/50)*5) + $rewards['points'] - $_SESSION['use_points'];
  $dbModel->changeRewards($rewards['id'], $points);
  }

$_SESSION['use_points'] = '';
//empty cart
$_SESSION['cart'] = [];
  header('Location:./complete_payment.php');
?>