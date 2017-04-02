<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$pdo;
$day = 86400;
$day2 = 86400;
$error = "";
$adminEmail = "";
$smtp_username = "";
$smtp_port = "465";
$smtp_host = "ssl://smtp.yandex.ru";
$smtp_password = "";
$smtp_debug = false;
$smtp_charset = "utf-8";
$smtp_from = "bookingSite";

//define("SITE_ADMIN", "");


try {
  $pdo = new PDO('sqlite:'.dirname(__FILE__).'/db/bookingDB.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
} catch(PDOException $e){
    echo 'Connection failed. Try later.';
}


?>
