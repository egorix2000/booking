<?php

require_once dirname(__FILE__).'/../entities/BookingElementEntity.class.php';
require_once dirname(__FILE__).'/../global.inc.php';

class BookingElementRepository {
  private $pdo;

  public function __construct(){
    $this->pdo = $GLOBALS['pdo'];
  }

  public function addElement($bookingElementEntity){
    $stmt = $this->pdo->prepare('INSERT INTO bookingElements (name, count, access) VALUES (?, ?, ?)');
    return $stmt->execute(array($bookingElementEntity->name, $bookingElementEntity->count, $bookingElementEntity->access));
  }

  public function updateElement($bookingElementEntity){
    $sql = "UPDATE bookingElements SET name = ?,
            count = ?,
            access = ?
            WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array($bookingElementEntity->name, $bookingElementEntity->count, $bookingElementEntity->access, $bookingElementEntity->id));
  }

  public function deleteElement($id){
    return $this->pdo->exec("DELETE FROM bookingElements WHERE id = ".$id);
  }

  public function getAllElements(){
    $sql = 'SELECT * FROM bookingElements';
    $bookingElementEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $bookingElementEntity = new BookingElementEntity(
          $row['id'],
          $row['name'],
          $row['count'],
          $row['access']);
          array_push($bookingElementEntities, $bookingElementEntity);
    }
    return $bookingElementEntities;
  }

  public function getAllElementNames(){
    $sql = 'SELECT * FROM bookingElements';
    $bookingElementNames = array();
    foreach ($this->pdo->query($sql) as $row) {
          array_push($bookingElementNames, $row['name']);
    }
    return $bookingElementNames;
  }

  public function getAllElementIds(){
    $sql = 'SELECT * FROM bookingElements';
    $bookingElementIds = array();
    foreach ($this->pdo->query($sql) as $row) {
          array_push($bookingElementIds, $row['id']);
    }
    return $bookingElementIds;
  }

  public function getAllAvailableElements(){
    $sql = 'SELECT * FROM bookingElements WHERE access = "1"';
    $bookingElementEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $bookingElementEntity = new BookingElementEntity(
          $row['id'],
          $row['name'],
          $row['count'],
          $row['access']);
          array_push($bookingElementEntities, $bookingElementEntity);
    }
    return $bookingElementEntities;
  }

  public function getElement($id){
    $sql = 'SELECT * FROM bookingElements WHERE id = '.$id;
    $bookingElementEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $bookingElementEntity = new BookingElementEntity(
          $row['id'],
          $row['name'],
          $row['count'],
          $row['access']);
          array_push($bookingElementEntities, $bookingElementEntity);
    }
    return isset($bookingElementEntities[0]) ? $bookingElementEntities[0] : null;
  }
}
 ?>
