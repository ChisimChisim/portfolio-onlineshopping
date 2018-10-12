<?php
require_once('./vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_test_wecZHZ5RkspvJqBBMjIAbh2r",
  "publishable_key" => "pk_test_GwnRfGIo7S3uW7j1URzlEU7E"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>