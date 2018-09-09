<?php
require_once '../../lib/util.php';
require_once  '../../conf/DSN.php';
require_once '../../lib/p.php';

$dir = '../../images/upload/'; //folder for uploaded images

$pro_code = htmlspecialchars($_GET['procode'], ENT_QUOTES, 'UTF-8');
//DB connection
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get info of the selected product
    $sql = 'SELECT p.code, p.name, p.price, u.name as unit, p.image, c.name as category FROM mst_product p 
    LEFT JOIN mst_category c ON p.category_code = c.code 
    LEFT JOIN mst_unit u ON p.unit_code = u.code 
    WHERE p.code=?';
    $stmt = $db->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_unit = $rec['unit'];
    $pro_image = $rec['image'];
    $pro_category = $rec['category'];

    $db = null;   //Disconected DB 
    } catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

header('Content-Type: text/html; charset=utf-8');
header('X-Context-Type-Options: nonsniff');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8">
<title>Kenkoh</title>
<link rel="stylesheet" type="text/css" href="../../common/css/normalize.css">
<link rel="stylesheet" type="text/css" href="../../common/css/style.css">
</head>
<body>

<div id="page">
<div id='pageHeader'>
 <h1>Kenkho - Product detail</h1>
<div id='pageBody'>
    <p>Product code: <?php  p::h($pro_code); ?></p>
    <p>Name: <?php  p::h($pro_name); ?></p>
    <p>Price: <?php  p::h($pro_price); ?></p>
    <p>Unit: <?php  p::h($pro_unit); ?></p>
    <p>Category: <?php  p::h($pro_category); ?></p>
    <img src="<?php p::h($dir); ?><?php p::h($pro_image); ?>" /></br>
</div>
<a href='pro_list.php' class='btnBlue'>Back</a>
</div>
</div>
<script src="./common/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="./common/js/staff.js"></script>
</body>
</html>