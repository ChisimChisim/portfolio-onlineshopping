<?php
require_once('./vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_test_************************",  //Secret Key
  "publishable_key" => "pk_test_************************"  //Publish Key
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>