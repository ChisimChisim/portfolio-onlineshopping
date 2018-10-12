<?php
require_once  '../conf/DSN.php';

class DbModel{

public function getListByCategory($category){
	try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get product list by category
    $sql = 'SELECT p.code, p.name, p.price, u.name as unit, p.image,c.name as category FROM mst_product p 
    LEFT JOIN mst_category c ON p.category_code = c.code
    LEFT JOIN mst_unit u ON p.unit_code = u.code
    WHERE c.code=? ';
    $data[] = $category;
    $stmt = $db->prepare($sql);
    $stmt->execute($data);

    $db = null;   //Disconected DB 

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e){
    	header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage());
    }
    return $list;
}//end of getListByCategory

public function getUserByEmail($email){
    try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Get exsist user
    $sql = 'SELECT id, email, first_name, last_name, password FROM customer WHERE email=?';
    $stmt = $db->prepare($sql);
    $data[] = $email;
    $stmt->execute($data);
    $db = null;   //Disconected DB 
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
return $rec;
}//end of getUserByEmail

public function createUser($first_name, $last_name, $email, $password){
    try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Create new User
    $sql = 'INSERT INTO customer(email, first_name, last_name, password) VALUES (?,?,?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $email;
    $data[] = $first_name;
    $data[] = $last_name;
    $data[] = $password;
    $stmt->execute($data);

    $db = null;   //Disconected DB 

    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
}//end of createUser

public function createRewards($id){
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //create rewards record
    $sql = 'INSERT INTO rewards(id, points) VALUES (?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $id;
    $data[] = 0;
    $stmt->execute($data);

    $db = null;   //Disconected DB 

    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
}//end of createRewards

public function getRewards($id){
    try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   //Get rewards point
    $sql = 'SELECT id, points FROM rewards WHERE id=?';
    $stmt = $db->prepare($sql);
    $data[] = $id;
    $stmt->execute($data);
    $db = null;   //Disconected DB 
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
    return $rec;
}//end of getRewards

public function changeRewards($id, $points){
try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //create rewards record
    $sql = 'UPDATE rewards SET points=? WHERE id=?';
    $stmt = $db -> prepare($sql);
    $data[] = $points;
    $data[] = $id;
    $stmt->execute($data);

    $db = null;   //Disconected DB 

    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
}//end of changeRewards

public function createOrderList($email, $amount, $stripe_id, $cart){
    $order_id = 0;
     try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Create new order
    $sql = 'INSERT INTO mst_order(customer_email, amount, stripe_id) VALUES (?,?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $email;
    $data[] = $amount;
    $data[] = $stripe_id;
    $stmt->execute($data);
    $order_id = $db->lastInsertId(); 
     
    //Create new orderlines
    for($i=0; $i<count($cart); $i++){
        $data = [];
        $sql = 'INSERT INTO mst_orderline(order_id, product_id, qty) VALUES (?,?,?)';
        $stmt = $db -> prepare($sql);
        $data[] = $order_id;
        $data[] = intval($cart[$i]['code']);
        $data[] = intval($cart[$i]['qty']);
        $stmt->execute($data);
    }

    $db = null;   //Disconected DB 

    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
    return  $order_id;
}//end of createOrderList

public function createShippingAddress($id, $address){
     try{
    $db = new PDO(DSN, DB_USER, DB_PWD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   //Register order shipping address 
    $sql = 'INSERT INTO mst_order_shipping(id, name, address, city, state, zip) VALUES (?,?,?,?,?,?)';
    $stmt = $db -> prepare($sql);
    $data[] = $id;
    $data[] = $address['shipping_name'];
    $data[] = $address['shipping_address_line1'];
    $data[] = $address['shipping_address_city'];
    $data[] = $address['shipping_address_state'];
    $data[] = $address['shipping_address_zip'];
    $stmt->execute($data);

    $db = null;   //Disconected DB 

    } catch(PDOException $e){
       header('Content-Type: text/plain; charset=UTF-8', true, 500);
       exit($e->getMessage());
    }
}//end of createOrderList    

}//class end