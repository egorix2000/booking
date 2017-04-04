<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$pdo;
$day = 86400;
$day2 = 86400;
$error = "";
$adminEmail = "";

define("SITE_ADMIN", "");


try {
  $pdo = new PDO('sqlite:'.dirname(__FILE__).'/db/bookingDB.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
} catch(PDOException $e){
    echo 'Connection failed. Try later.';
}


?>
