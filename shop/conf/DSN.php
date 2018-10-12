<?php

    $user = 'kenkoh';
    $password = 'password_kenkoh';
    $dbName = 'shop';
    $host = 'localhost';
    $port = 3307;

    define('DSN', "mysql:dbname=$dbName;host=$host;charset=utf8;port=$port;");
	define('DB_USER', $user);
	define('DB_PWD', $password);

?>