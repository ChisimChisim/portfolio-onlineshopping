<?php
require_once '../lib/p.php';
require_once '../lib/dbmodel.php';
$dir = '../../images/upload/'; //folder for uploaded images
                       
//Session Start
session_start();
session_regenerate_id(true);

$cart = [];
$subtotal = 0;
$total = 0;
$qty = 0;
$apply_points = 0;
$points = 0;

$cart = $_SESSION['cart'];
for($i=0; $i<count($cart); $i++) {
	$subtotal = $subtotal + ($cart[$i]['price'] * (int)$cart[$i]['qty']);
	$qty = $qty + (int)$cart[$i]['qty'];
	}
$total = $subtotal;	
if(!isset($_POST['points'])){
//DB connection Get rewards
$id = $_SESSION['customer_id'];
$dbModel = new DbModel();
$rewards = $dbModel->getRewards($id);
if(isset($rewards['points'])){
	$points = $rewards['points'];
}else{
	$point = 0;
}

if($total<$points){
	$max_apply_point = intval($total);
}else{
	$max_apply_point = $points;
}
}

if(isset($_POST['apply_points']) && isset($_POST['total']) && isset($_POST['points'])){	
	$apply_points= htmlspecialchars($_POST['apply_points'], ENT_QUOTES, 'UTF-8');
	$total= htmlspecialchars($_POST['total'], ENT_QUOTES, 'UTF-8');
	$points= htmlspecialchars($_POST['points'], ENT_QUOTES, 'UTF-8');
	$total = $total - $apply_points;
	$points = $points - $apply_points;
	if($total<$points){
	$max_apply_point = intval($total);
    }else{
	$max_apply_point = $points;
    }
}

$_SESSION['use_points'] = $subtotal - $total;
/***** Change Cart info to JSON   *****/
/* for pass Cart(array) to Javascript */
$jsonTotal=json_encode($total);
$jsonQty=json_encode($qty);
/*************************************/
if(isset($_SESSION['auth'])){
    $auth=$_SESSION['auth'];
}else{
    $auth=false;
}
if(isset($_SESSION['first_name'])){
    $first_name=$_SESSION['first_name'];
}else{
    $first_name='';
}

header('Content-Type: text/html; charset=utf-8');
header('X-Context-Type-Options: nonsniff');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Kenkoh</title>
	<link rel="stylesheet" href="../common/css/normalize.css">
	<link rel="stylesheet" href="../common/css/style.css">
</head>
<body>
<div id="page">
	<header id="pageHeader" role='banner'>
			<h1 class='siteLogo'>
				<a href="../index.php"><img src="../images/logo_nav.jpg"></a>
			</h1>
	</header>

<div id="pageBody">
		<table>
			<caption><strong>ORDER SUMMARY</strong></caption>
			<thead>
				<tr>
					<th colspan="2">Item</th>
				    <th>Price/Unit</th>
				    <th>Qty</th>
			    </tr>
		    </thead>
			<?php foreach($cart as $row): ?>
			<tbody>	
				<tr>
				    <td><img src="<?php p::h($dir); ?><?php p::h($row['image']); ?>" /></td>
				    <td><?php P::h($row['name']) ?></td>
				    <td>$<?php P::h($row['price']) ?>/<?php P::h($row['unit']) ?></td>
				    <td><?php P::h($row['qty']) ?></td>
			    </tr>
			</tbody>
			<?php endforeach; ?>
			<tfoot>
				<tr>
				<td colspan="4">subtotal: $<?php P::h(number_format($subtotal, 2)) ?>(qty: <?php P::h($qty) ?>)</td>
				</tr>
                <tr>
				<td colspan="4">Reward points: -$<?php P::h(number_format($apply_points, 2)) ?></td>
			    </tr>
			    <tr>
				<td colspan="4"><strong>total: $<?php P::h(number_format($total, 2)) ?></strong></td>
			    </tr>
			</tfoot>
		</table>
        
		<div class="payment">
	    	<button id="paymentButton" class="btnBlue">Pay with card</button>
	    </div>
        
        <div id=applyRewards>
        	<form action="checkoutConfirm.php" method="post">
        		<p><b><?php P::h($first_name) ?>, You have $<?php P::h($points)?> rewards!</b></p>
        		<input type="number" name="apply_points" value="0" step="1" min="0" max="<?php P::h($max_apply_point)?>" >
        		<input type="hidden" name="total" value="<?php P::h($total)?> ">
        		<input type="hidden" name="points" value="<?php P::h($points)?> ">
        		<input type="submit" value="Apply Rewards" >
            </form>
		</div>

		<a href="../shop/shop_cart.php">Back to Cart</a>
</div>   
   
<footer id="pageFooter">
		<P id="copyright"><small>Copyright&copy; 2018 @Kenkoh All Rights Reserved.</small></P>
</footer>

</div>

<!--- Pass Cart(array) to Javascript ----->
<script type="text/javascript">
  var checkout_total=JSON.parse('<?php echo $jsonTotal; ?>');
  var checkout_qty =JSON.parse('<?php echo  $jsonQty; ?>');
  var auth = '<?php echo $auth; ?>';
</script>
<!---------------------------------------->
<script src="../common/js/vendor/jquery-1.10.2.min.js"></script>
<script src="../common/js/vendor/jquery-ui-1.10.3.custom.min.js"></script>
<script src="../common/js/main_jq.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript" src="../payment/checkout.js"></script>
</body>	
</html>