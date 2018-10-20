<?php
require_once '../lib/p.php';
require_once '../lib/cartmodel.php';
require_once '../lib/dbmodel.php';

$dir = '../../images/upload/'; //folder for uploaded images

//Session Start
session_start();
session_regenerate_id(true);

$max_qty = 50;
$cart = [];
$message = '';

//if it is from product detail....
if(isset($_POST['code'])){
	$pro_code= htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
	$pro_name= htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
	$pro_price= htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
	$pro_unit= htmlspecialchars($_POST['unit'], ENT_QUOTES, 'UTF-8');
	$pro_image= htmlspecialchars($_POST['image'], ENT_QUOTES, 'UTF-8');

  $cartModel = new CartModel();
  $result = $cartModel->addCart($pro_code, $pro_name, $pro_price, $pro_unit, $pro_image);
  $cart = $result[0];
  $message = $result[1];

//if it is from cart icon 
}else{
	if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
		$cart=$_SESSION['cart'];
	}else{
		$message = 'Cart is empty';
	}
}
//get subtotal & qty
$subtotal = 0;
$totalqty = 0;
for($i=0; $i<count($cart); $i++) {
	$subtotal = $subtotal + ($cart[$i]['price'] * $cart[$i]['qty']);
	$totalqty = $totalqty + $cart[$i]['qty'];
	}

/***** Change Cart info to JSON   *****/
/* for pass Cart(array) to Javascript */
$jsonCart=json_encode($cart);
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
			<nav class="navi" role='navigation'>
				<ul>
				<li class="cartLogo">
				   <a href="./shop_cart.php"><img src="../images/icon/cart.png"></a>
				   <div id="qty" class='disabled'>
					  <span id='cartLogo_qty'></span>
			       </div>
			    </li>
			    <li class="loginLogo">
			    	<a href="javascript:void(0)" ><img src="../images/icon/login.png" id='login-aside'></a>
			    	<a href="javascript:void(0)" ><img src="../images/icon/logout.png" id='logout-aside'></a>
			    </li>	
			</ul>
			</nav>

			<aside id="drawer-login">
				<p>Hello!</p>
				<ul>
			    <li><a href="../login/loginForm.php"><button>SIGN IN</button></a></li>
			    <li><a href="../login/createAccount.php"><span>CREATE AN ACOUNT</span></a></li>
	            </ul>	
	        </aside>
	        <aside id="drawer-logout">
				<p>Hi, <?php p::h($first_name) ?>!</p>
				<ul>
			    <li><a href="./login/logout.php"><button>SIGN OUT</button></a></li>
			    <li><a href="./myRewards.php"><span>MY REWARDS</span></a></li>
	            </ul>	
	        </aside>
	</header>

<div id="pageBody">

    <?php if( !empty($message) ): ?>
    	 <div id="message">
        	<span><?php p::h($message) ?></span>	
         </div>
    <?php endif;?>
 
  <section class='cart'>
   <div class='cart_total'>
      Subtotal(<span name='totalqty'><?php p::h($totalqty) ?></span> 
        qty): $<span name='subtotal'><?php p::h(number_format($subtotal/100, 2)) ?></span>
      <a href="./checkoutConfirm.php"><button id="checkoutButton" class="btnBlue">Proceed to checkout</button></a>
   </div>

    <?php foreach($cart as $row): ?>
    	<div id="item_<?php P::h($row['code']); ?>">
			<div class='cart_item'>
				<div class='detail_image'>
					<img src="<?php p::h($dir); ?><?php p::h($row['image']); ?>" />
			    </div>
			    <div class='detail_text'>
			    	<p><?php P::h($row['name']) ?></p>
				    <p>$<?php P::h(number_format($row['price']/100,2)) ?>/<?php P::h($row['unit']) ?></p>
				         <label for='qty'>Quantity: </label>
				         <select id="qty_<?php P::h($row['code']); ?>" name='qty'>
					 <?php 
					   for($i=1; $i<=$max_qty; $i++){ 
						   if((int)$row['qty']===$i){
					    ?>		
					    <option value = '<?php p::h($i) ?>' selected><?php p::h($i) ?></option>;
					   <?php	}else{ ?>
                        <option value = '<?php p::h($i) ?>' ><?php p::h($i) ?></option>;
                       <?php    }
                       } //end for
                    ?>
                    </select>
                    <p><a href="javascript:void(0)" id="delete_<?php P::h($row['code']); ?>">x DELETE</a></p>
                </div>
            </div>
            </div>
    <?php endforeach; ?>
</section>

<section class='subtotal'>
 Subtotal(<span name='totalqty'><?php p::h($totalqty) ?></span> items): 
        $<span name='subtotal'><?php p::h(number_format($subtotal/100, 2)) ?></span>
</section>   

<a href="../index.php">Back to Top</a> 

</div>   
   
<footer id="pageFooter">
		<P id="copyright"><small>Copyright&copy; 2018 @Kenkoh All Rights Reserved.</small></P>
</footer>

</div>

<!--- Pass Cart(array) to Javascript ----->
<script type="text/javascript">
  var jsonCart=JSON.parse('<?php echo $jsonCart; ?>');
  var auth = '<?php echo $auth; ?>';
</script>
<!---------------------------------------->
<script type="text/javascript" src="../common/js/nav.js"></script>
<script type="text/javascript" src="../common/js/main.js"></script>
<script src="../common/js/vendor/jquery-1.10.2.min.js"></script>
<script src="../common/js/vendor/jquery-ui-1.10.3.custom.min.js"></script>
<script src="../common/js/main_jq.js"></script>
</body>	
</html>