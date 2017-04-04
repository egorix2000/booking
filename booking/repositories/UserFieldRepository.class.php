<?php

require_once dirname(__FILE__).'/../entities/UserFieldEntity.class.php';
require_once dirname(__FILE__).'/../global.inc.php';
require_once dirname(__FILE__).'/../PDOSingleton.class.php';

class UserFieldRepository {
  private $pdo;

  public function __construct(){
    $this->pdo = PDOSingleton::getInstance();
  }

  public function getLastId(){
    $sql = 'SELECT id FROM userFields WHERE rowid=last_insert_rowid()';
    foreach ($this->pdo->query($sql) as $row) {
      return $row['id'];
    }
    return null;
  }

  public function addField($userFieldEntity){
    $stmt = $this->pdo->prepare('INSERT INTO userFields (type, access, isRequired, placeholder, label) VALUES (?, ?, ?, ?, ?)');
    return $stmt->execute(array($userFieldEntity->type, $userFieldEntity->access, $userFieldEntity->isRequired, $userFieldEntity->placeholder, $userFieldEntity->label));
  }

  public function updateField($userFieldEntity){
    $sql = "UPDATE userFields SET type = ?,
            access = ?,
            isRequired = ?,
            placeholder = ?,
            label = ?
            WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array($userFieldEntity->type, $userFieldEntity->access, $userFieldEntity->isRequired, $userFieldEntity->placeholder, $userFieldEntity->label, $userFieldEntity->id));
  }

  public function deleteField($id){
    return $this->pdo->exec("DELETE FROM userFields WHERE id = ".$id);
  }

  public function getAllFields(){
    $sql = 'SELECT * FROM userFields';
    $userFieldEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFieldEntity = new UserFieldEntity(
          $row['id'],
          $row['type'],
          $row['access'],
          $row['isRequired'],
          $row['placeholder'],
          $row['label']);
          array_push($userFieldEntities, $userFieldEntity);
    }
    return $userFieldEntities;
  }

  public function getField($id){
    $sql = 'SELECT * FROM userFields WHERE id = '.$id;
    $userFieldEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFieldEntity = new UserFieldEntity(
          $row['id'],
          $row['type'],
          $row['access'],
          $row['isRequired'],
          $row['placeholder'],
          $row['label']);
          array_push($userFieldEntities, $userFieldEntity);
    }
    return isset($userFieldEntities[0]) ? $userFieldEntities[0] : null;
  }

  public function getFieldByLabel($label){
    $sql = 'SELECT * FROM userFields WHERE label = "'.$label.'"';
    $userFieldEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFieldEntity = new UserFieldEntity(
          $row['id'],
          $row['type'],
          $row['access'],
          $row['isRequired'],
          $row['placeholder'],
          $row['label']);
          array_push($userFieldEntities, $userFieldEntity);
    }
    return isset($userFieldEntities[0]) ? $userFieldEntities[0] : null;
  }
}
 ?>
