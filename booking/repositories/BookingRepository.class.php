<?php

require_once dirname(__FILE__).'/../entities/BookingEntity.class.php';
require_once dirname(__FILE__).'/UserFieldRepository.class.php';
require_once dirname(__FILE__).'/../global.inc.php';

class BookingRepository {
  private $pdo;

  public function __construct(){
    $this->pdo = $GLOBALS['pdo'];
  }

  public function addColumn($userFieldEntity){
    $type = 'TEXT';
    if ($userFieldEntity->type == 'number'){
      $type = 'INTEGER';
    }
    $this->pdo->exec("ALTER TABLE bookings ADD COLUMN '".$userFieldEntity->label."' ".$type);
  }

  public function deleteColumn($label){
    $this->pdo->beginTransaction();
    $this->pdo->exec("ALTER TABLE bookings RENAME TO bookings_old");
    $userFieldRepository = new UserFieldRepository();
    $userFields = $userFieldRepository->getAllFields();
    $sqlFields = "";
    $sqlInsertFields = "";
    foreach ($userFields as $value) {
      if ($value->label != $label){
        $sqlInsertFields .= ", ".$value->label;
        if ($value->type == 'number'){
          $sqlFields .= ", ".$value->label." INTEGER";
        } else {
          $sqlFields .= ", ".$value->label." TEXT";
        }
      }
    }

    $sql = "CREATE TABLE IF NOT EXISTS bookings (
    	id INTEGER PRIMARY KEY AUTOINCREMENT,
    	elementId	INTEGER,
    	username TEXT,
    	email TEXT,
    	phone TEXT,
    	sendDate TEXT,
      beginDate TEXT,
      bookingDays INTEGER".$sqlFields.")";
    $this->pdo->exec($sql);

    $sql = "INSERT INTO bookings (id, elementId, username, email, phone, sendDate, beginDate, bookingDays".$sqlInsertFields.")
     SELECT id, elementId, username, email, phone, sendDate, beginDate, bookingDays".$sqlInsertFields." FROM bookings_old";
    $this->pdo->exec($sql);

    $this->pdo->exec("DROP TABLE bookings_old");
    $this->pdo->commit();
  }


  public function renameColumn($labelBefore, $label){
    $this->pdo->beginTransaction();
    $this->pdo->exec("ALTER TABLE bookings RENAME TO bookings_old");
    $userFieldRepository = new UserFieldRepository();
    $userFields = $userFieldRepository->getAllFields();
    $sqlFields = "";
    $sqlNewInsertFields = "";
    $sqlOldInsertFields = ""; ///посмотреть на type
    foreach ($userFields as $value) {
      if ($value->label != $labelBefore){
        $sqlNewInsertFields .= ", ".$value->label;
        $sqlOldInsertFields .= ", ".$value->label;
        if ($value->type == 'number'){
          $sqlFields .= ", ".$value->label." INTEGER";
        } else {
          $sqlFields .= ", ".$value->label." TEXT";
        }
      } else {
        $sqlNewInsertFields .= ", ".$label;
        $sqlOldInsertFields .= ", ".$value->label;
        if ($value->type == 'number'){
          $sqlFields .= ", ".$label." INTEGER";
        } else {
          $sqlFields .= ", ".$label." TEXT";
        }
      }
    }

    $sql = "CREATE TABLE IF NOT EXISTS bookings (
    	id INTEGER PRIMARY KEY AUTOINCREMENT,
    	elementId	INTEGER,
    	username TEXT,
    	email TEXT,
    	phone TEXT,
    	sendDate TEXT,
      beginDate TEXT,
      bookingDays INTEGER".$sqlFields.")";
    $this->pdo->exec($sql);

    $sql = "INSERT INTO bookings (id, elementId, username, email, phone, sendDate, beginDate, bookingDays".$sqlNewInsertFields.")
     SELECT id, elementId, username, email, phone, sendDate, beginDate, bookingDays".$sqlOldInsertFields." FROM bookings_old";
    $this->pdo->exec($sql);

    $this->pdo->exec("DROP TABLE bookings_old");
    $this->pdo->commit();
  }

  public function addBooking($bookingEntity){
    $params = array($bookingEntity->elementId, $bookingEntity->username, $bookingEntity->email, $bookingEntity->phone, $bookingEntity->sendDate, $bookingEntity->beginDate, $bookingEntity->bookingDays);
    $columns = '';
    $questions = '';
    foreach ($bookingEntity->userFields as $key => $value) {
      $questions .= ', ?';
      $columns .= ', '.$key;
      array_push($params, $value);
    }
    $sql = 'INSERT INTO bookings (elementId, username, email, phone, sendDate, beginDate, bookingDays'.$columns.') VALUES (?, ?, ?, ?, ?, ?, ?'.$questions.')';
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
  }

  public function updateBooking($bookingEntity){
    $params = array($bookingEntity->username, $bookingEntity->email, $bookingEntity->phone, $bookingEntity->sendDate);
    $columns = '';
    foreach ($bookingEntity->userFields as $key => $value) {
      $columns .= ', '.$key.' = ?';
      array_push($params, $value);
    }
    array_push($params, $bookingEntity->id);
    $sql = "UPDATE bookings SET username = ?, email = ?, phone = ?, sendDate = ?".$columns." WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
  }

  public function deleteBooking($id){
    return $this->pdo->exec("DELETE FROM bookings WHERE id = ".$id);
  }

  public function getAllBookings($allUserFields){
    $sql = 'SELECT * FROM bookings ORDER BY sendDate DESC';
    $bookingEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFields = array();
        foreach ($allUserFields as $value) {
          $userFields[$value->label] = $row[$value->label];
        }
        $bookingEntity = new BookingEntity(
          $row['id'],
          $row['elementId'],
          $row['username'],
          $row['email'],
          $row['phone'],
          $row['sendDate'],
          $row['beginDate'],
          $row['bookingDays'],
          $userFields);
          array_push($bookingEntities, $bookingEntity);
    }
    return $bookingEntities;
  }

  public function getBooking($id, $allUserFields){
    $sql = 'SELECT * FROM bookings WHERE id = '.$id;
    $bookingEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFields = array();
        foreach ($allUserFields as $value) {
          $userFields[$value->label] = $row[$value->label];
        }
        $bookingEntity = new BookingEntity(
          $row['id'],
          $row['elementId'],
          $row['username'],
          $row['email'],
          $row['phone'],
          $row['sendDate'],
          $row['beginDate'],
          $row['bookingDays'],
          $userFields);
          array_push($bookingEntities, $bookingEntity);
    }
    return isset($bookingEntities[0]) ? $bookingEntities[0] : null;
  }

  public function getLastThreeBookings($allUserFields){
    $sql = 'SELECT * FROM bookings ORDER BY sendDate DESC LIMIT 3';
    $bookingEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        $userFields = array();
        foreach ($allUserFields as $value) {
          $userFields[$value->label] = $row[$value->label];
        }
        $bookingEntity = new BookingEntity(
          $row['id'],
          $row['elementId'],
          $row['username'],
          $row['email'],
          $row['phone'],
          $row['sendDate'],
          $row['beginDate'],
          $row['bookingDays'],
          $userFields);
          array_push($bookingEntities, $bookingEntity);
    }
    return $bookingEntities;
  }

  public function getTodayBookings($allUserFields){
    date_default_timezone_set('UTC');
    $todayDate = strtotime(date('d-m-Y'));
    $sql = 'SELECT * FROM bookings WHERE beginDate <= '.$todayDate;
    $bookingEntities = array();
    foreach ($this->pdo->query($sql) as $row) {
        if ($row['beginDate']+($row['bookingDays']*$GLOBALS['day']) > $todayDate) {
          $userFields = array();
          foreach ($allUserFields as $value) {
            $userFields[$value->label] = $row[$value->label];
          }
          $bookingEntity = new BookingEntity(
            $row['id'],
            $row['elementId'],
            $row['username'],
            $row['email'],
            $row['phone'],
            $row['sendDate'],
            $row['beginDate'],
            $row['bookingDays'],
            $userFields);
          array_push($bookingEntities, $bookingEntity);
        }
    }
    return $bookingEntities;
  }

  public function getDayBookings($allUserFields, $date){
    date_default_timezone_set('UTC');

    $sql = 'SELECT * FROM bookings WHERE beginDate <= '.$date;
    $bookingEntities = array();
    foreach ($this->pdo->query($sql) as $row) {

        if ($row['beginDate']+($row['bookingDays']*$GLOBALS['day2']) > $date) {
          $userFields = array();
          foreach ($allUserFields as $value) {
            $userFields[$value->label] = $row[$value->label];
          }
          $bookingEntity = new BookingEntity(
            $row['id'],
            $row['elementId'],
            $row['username'],
            $row['email'],
            $row['phone'],
            $row['sendDate'],
            $row['beginDate'],
            $row['bookingDays'],
            $userFields);
          array_push($bookingEntities, $bookingEntity);
        }
    }
    return $bookingEntities;
  }
}
 ?>
