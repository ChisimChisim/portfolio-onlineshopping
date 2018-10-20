<?php
require_once '../lib/p.php';

$dir = '../../images/upload/'; //folder for uploaded images

//Session Start
session_start();
session_regenerate_id(true);

if(isset($_POST['code'])){
	$pro_code= htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');
	$pro_name= htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
	$pro_price= htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
	$pro_unit= htmlspecialchars($_POST['unit'], ENT_QUOTES, 'UTF-8');
	$pro_image= htmlspecialchars($_POST['image'], ENT_QUOTES, 'UTF-8');
}

/***** Change Cart info to JSON   *****/
/* for pass Cart(array) to Javascript */
if(isset($_SESSION['cart'])){
	$jsonCart=json_encode($_SESSION['cart']);
}else{
	$jsonCart=json_encode('');
}
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
			    <li><a href="../login/logout.php"><button>SIGN OUT</button></a></li>
			    <li><a href="./myRewards.php"><span>MY REWARDS</span></a></li>
	            </ul>	
	        </aside>
	</header>

<div id="pageBody">
	<section class='detail'>
		<div class='detail_image'>
            <img src="<?php p::h($dir); ?><?php p::h($pro_image); ?>" />
		</div>
		<div class='detail_text'>
            <p><?php P::h($pro_name) ?></p>
			<p>price: $<?php P::h(number_format($pro_price/100,2)) ?>/<?php P::h($pro_unit) ?></p>
			<form method='post' action='shop_cart.php'>
				<input type="submit" class="btnBlue" value="Add to Cart!"/>
				<input type="hidden" name='code' value="<?php p::h($pro_code); ?>" />
				<input type="hidden" name='name' value="<?php p::h($pro_name); ?>" />
				<input type="hidden" name='price' value="<?php p::h($pro_price); ?>" />
				<input type="hidden" name='unit' value="<?php p::h($pro_unit); ?>" />
				<input type="hidden" name='image' value="<?php p::h($pro_image); ?>" />
		    </form>
		</div>
   
	</section>

	<a href="../index.php">Back to Top</a> 
</div>

<footer id="pageFooter">
	<P id="copyright"><small>Copyright&copy; 2018 @Kenkoh All Rights Reserved.</small></P>
</footer>

</div>
<!--- Pass Cart(array) to Javascript ----->
<script type="text/javascript">
  var jsonCart = JSON.parse('<?php echo $jsonCart; ?>');
  var auth = '<?php echo $auth; ?>';
</script>
<!---------------------------------------->
<script type="text/javascript" src="../common/js/nav.js"></script>
<script src="../common/js/vendor/jquery-1.10.2.min.js"></script>
<script src="../common/js/vendor/jquery-ui-1.10.3.custom.min.js"></script>
<script src="../common/js/main_jq.js"></script>
</body>	
</html>