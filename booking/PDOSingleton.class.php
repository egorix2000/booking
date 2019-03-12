<?php

class PDOSingleton {
    private static $pdo = null;

    public static function getInstance()
    {
        if (null === self::$pdo)
        {
          try {
            self::$pdo = new PDO('sqlite:'.dirname(__FILE__).'/db/bookingDB.db');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
          } catch (PDOException $e){
              self::$pdo = null;
              echo 'Connection failed. Try later.';
          }
        }
        return self::$pdo;
    }
    private function __clone() {}
    private function __construct() {}
}

?>
