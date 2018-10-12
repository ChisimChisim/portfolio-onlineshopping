<?php
class CartModel{
	public function addCart($pro_code, $pro_name, $pro_price, $pro_unit, $pro_image){
		$cart = [];
		$message = '';
		$max_qty = 50;
		if(isset($_SESSION['cart'])){
			$cart=$_SESSION['cart'];
			$flag = 0;
			for($i=0; $i<count($cart); $i++) {
				if($cart[$i]['code'] === $pro_code){
					if($cart[$i]['qty'] == $max_qty){
						$message = 'Max order quantity or each items is 50.';
					}else{
						$cart[$i]['qty'] = $cart[$i]['qty'] + 1;
						$message = 'Added to Cart';
					}
					$flag = 1;
					break;
				}
			}
			if($flag === 0){
				$cart[] = ['code'  => $pro_code,
		                   'name'  => $pro_name,
                           'price' => $pro_price,
                           'unit'  => $pro_unit,
                           'image' => $pro_image,
                           'qty'   => 1];
                $message = 'Added to Cart';
            }
        }else{
        	$cart[] = ['code' => $pro_code,
		               'name'  => $pro_name,
                       'price' => $pro_price,
                       'unit'  => $pro_unit,
                       'image' => $pro_image,
                       'qty'   => 1];
            $message = 'Added to Cart'; 
        }
    $_SESSION['cart'] = $cart;
    return  [$cart, $message];
}

}