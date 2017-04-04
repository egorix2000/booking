<?php

require_once dirname(__FILE__).'/../entities/CalendarEntity.class.php';
require_once dirname(__FILE__).'/../global.inc.php';
require_once dirname(__FILE__).'/../Date.class.php';
require_once dirname(__FILE__).'/../PDOSingleton.class.php';

class CalendarRepository {
  private $pdo;

  public function __construct(){
    $this->pdo = PDOSingleton::getInstance();
  }

  public function deleteBooking($calendarEntity){
    $endDate = $calendarEntity->beginDate+($GLOBALS['day']*$calendarEntity->days);
    $sql = "UPDATE calendar SET count = count - 1 WHERE elementId = ".$calendarEntity->elementId." AND date >= ".$calendarEntity->beginDate." AND date < ".$endDate;
    $this->pdo->exec($sql);
  }

  public function checkBooking($elementId, $dateFrom, $bookingDays, $count){
    $dateEnd = $dateFrom+($GLOBALS['day']*$bookingDays);
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$elementId.' AND count > '.($count-1).' AND date >= '.$dateFrom.' AND date < '.$dateEnd;
    foreach ($this->pdo->query($sql) as $row) {
        return false;
    }
    return true;
  }

  public function checkUpdateElement($id, $count){
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$id.' AND count > '.$count;
    foreach ($this->pdo->query($sql) as $row) {
        return false;
    }
    return true;
  }

  public function checkDeleteElement($id){
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$id.' AND count > 0';
    foreach ($this->pdo->query($sql) as $row) {
        return false;
    }
    return true;
  }

  public function getAllBookedDays($elementId, $dateFrom, $bookingDays, $count){
    $dateEnd = $dateFrom+($GLOBALS['day']*$bookingDays);
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$elementId.' AND count > '.($count-1).' AND date >= '.$dateFrom.' AND date < '.$dateEnd;
    $days = array();
    foreach ($this->pdo->query($sql) as $row) {
        array_push($days, $row['date']);
    }
    return $days;
  }

  public function addBooking($elementId, $dateFrom, $bookingDays){
    $dateEnd = $dateFrom+($GLOBALS['day']*$bookingDays);
    $date = $dateFrom;
    $sql = 'INSERT INTO calendar (date, elementId, count) VALUES (?, ?, ?)';
    for ($t = 0; $t < $bookingDays; $t++){
      if ($this->isExist($elementId, $date)){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($date, $elementId, 0));
      }
      $date += $GLOBALS['day'];
    }
    $sql = "UPDATE calendar SET count = count + 1 WHERE elementId = ".$elementId." AND date >= ".$dateFrom." AND date < ".$dateEnd;
    $this->pdo->exec($sql);
  }

  public function isExist($elementId, $date){
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$elementId.' AND date = '.$date;
    foreach ($this->pdo->query($sql) as $row) {
        return false;
    }
    return true;
  }

  public function getBookedCount($elementId, $date){
    $sql = 'SELECT * FROM calendar WHERE elementId = '.$elementId.' AND date = '.$date;
    foreach ($this->pdo->query($sql) as $row) {
        return $row['count'];
    }
    return 0;
  }

}
 ?>
