<?php
require_once '../lib/p.php';
require_once '../lib/dbmodel.php';

$dir = '../../images/upload/'; //folder for uploaded images

//Session Start
session_start();
session_regenerate_id(true);

if(isset($_GET['pro_category'])){
	$category= htmlspecialchars($_GET['pro_category'], ENT_QUOTES, 'UTF-8');
//DB connection
    $dbModel = new DbModel();
    $list = $dbModel->getListByCategory($category);
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
	<section class='prolist'>
		<?php foreach($list as $row): ?>
			<div class='prodisplay'>
				<form method='post' action='shop_detail.php'>
					<input class='img' type='image' src="<?php p::h($dir); ?><?php p::h($row['image']); ?>" />
				    <input type='hidden' name='code' value='<?php p::h($row['code']); ?>'/>
				    <input type='hidden' name='name' value='<?php p::h($row['name']); ?>'/>
				    <input type='hidden' name='price' value='<?php p::h($row['price']); ?>'/>
				    <input type='hidden' name='unit' value='<?php p::h($row['unit']); ?>'/>
				    <input type='hidden' name='image' value='<?php p::h($row['image']); ?>'/>	
				</form>	
					<p><?php P::h($row['name']) ?></p>
					<p>$<?php P::h($row['price']) ?>/<?php P::h($row['unit']) ?></p>
			 </div>
        <?php endforeach; ?>
   
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