<?php
require_once './lib/p.php';
//Session Start
session_start();
session_regenerate_id(true);

/***** Change to JSON code*****/
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Kenkoh</title>
	<link rel="stylesheet" href="./common/css/normalize.css">
	<link rel="stylesheet" href="./common/css/style.css">
</head>
<body>
<div id='header-banner' role='banner'>
	<?php if($auth===false){ ?>
		<p>Click <a href="./login/createAccount.php">here</a> to Sign Up and Get 5% rewards on your purchace</p>
    <?php }else{ ?>
        <p>Free shipping on $35+ orders</p>		
	<?php  }?>
</div>
<div id="page">
	<header id="pageHeader" role='banner'>
			<h1 class='siteLogo'>
				<a href="./index.php"><img src="./images/logo_nav.jpg"></a>
			</h1>
			<nav class="navi" role='navigation'>
				<ul>
				<li class="cartLogo">
				   <a href="./shop/shop_cart.php"><img src="./images/icon/cart.png"></a>
				   <div id="qty" class='disabled'>
					  <span id='cartLogo_qty'></span>
			       </div>
			    </li>
			    <li class="loginLogo">
			    	<a href="javascript:void(0)" ><img src="./images/icon/login.png" id='login-aside'></a>
			    	<a href="javascript:void(0)" ><img src="./images/icon/logout.png" id='logout-aside'></a>
			    </li>	
			</ul>
			</nav>

			<aside id="drawer-login">
				<p>Hello!</p>
				<ul>
			    <li><a href="./login/loginForm.php"><button>SIGN IN</button></a></li>
			    <li><a href="./login/createAccount.php"><span>CREATE AN ACOUNT</span></a></li>
	            </ul>	
	        </aside>
	        <aside id="drawer-logout">
				<p>Hi, <?php p::h($first_name) ?>!</p>
				<ul>
			    <li><a href="./login/logout.php"><button>SIGN OUT</button></a></li>
			    <li><a href="./shop/myRewards.php"><span>MY REWARDS</span></a></li>
	            </ul>	
	        </aside>
	</header>

<div id="pageBody">
	<div class="mainVisual">
       <p><img src="./images/main_01.jpg"></p>
       <p><img src="./images/main_02.jpg"></p>
       <p><img src="./images/main_03.jpg"></p>
       <p><img src="./images/main_04.jpg"></p>
       <p><img src="./images/main_05.jpg"></p>
	</div>

	<section> 
		<div class='category'>
			<h2> Category </h2>
			<div class='inner' >
			<form name='proList' action='./shop/shop_list.php' method="get">
				<button type='submit' name='pro_category' value=1>
					<img src="./images/vege.png">
					<p>Produce</p>
				</button>
				<button type='submit' name='pro_category' value=2>
					<img src="./images/meat.png">
					<p>Meat & Fish</p>
				</button>
				<button type='submit' name='pro_category' value=3>
					<img src="./images/diary.png">
					<p>Dairy</p>
				</button>
				<button type='submit' name='pro_category' value=4>
					<img src="./images/bakery.png">
					<p>Bakery</p>
				</button>
			</form>
		</div>
		</div>
	</section>
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
<script type="text/javascript" src="./common/js/nav.js"></script>
<script src="./common/js/vendor/jquery-1.10.2.min.js"></script>
<script src="./common/js/vendor/jquery-ui-1.10.3.custom.min.js"></script>
<script src="./common/js/main_jq.js"></script>
</body>	
</html>