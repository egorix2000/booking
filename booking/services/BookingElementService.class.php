<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../repositories/BookingElementRepository.class.php';
require_once dirname(__FILE__).'/../repositories/CalendarRepository.class.php';
require_once dirname(__FILE__).'/../entities/BookingElementEntity.class.php';
require_once dirname(__FILE__).'/../global.inc.php';

class BookingElementService {

  public $repository = null;
  public $calendarRepository = null;

  public function __construct(){
    $this->repository = new BookingElementRepository();
    $this->calendarRepository = new CalendarRepository();
  }

  public function validateCreate($bookingElementEntity){
    $isOk = true;
    $error = "Errors: ";
    if ($bookingElementEntity->name == null || $bookingElementEntity->name == ""){
      $isOk = false;
      $error .= "<br /> Name can't be empty";
    }
    if (strstr($bookingElementEntity->name, "/") !== false){
      $isOk = false;
      $error .= "<br /> Name can't contain /";
    }
    if (is_int($bookingElementEntity->count) || $bookingElementEntity->count < 1){
      $isOk = false;
      $error .= "<br /> Count must be a number > 0";
    }
    if ($bookingElementEntity->access != 0 && $bookingElementEntity->access != 1){
      $isOk = false;
      $error .= "<br /> Please, select access from list";
    }
    $GLOBALS['error'] = $error;
    return $isOk;
  }

  public function validateUpdate($bookingElementEntity){
    $isOk = true;
    $error = "Errors: ";
    if ($bookingElementEntity->name == null || $bookingElementEntity->name == ""){
      $isOk = false;
      $error .= "<br /> Name can't be empty";
    }
    if (strstr($bookingElementEntity->name, "/") !== false){
      $isOk = false;
      $error .= "<br /> Name can't contain /";
    }
    if (is_int($bookingElementEntity->count) || $bookingElementEntity->count < 1){
      $isOk = false;
      $error .= "<br /> Count must be a number > 0";
    }
    if (!$this->calendarRepository->checkUpdateElement($bookingElementEntity->id, $bookingElementEntity->count)){
      $isOk = false;
      $error .= "<br /> Sory more then ".$bookingElementEntity->count." elements: ".$bookingElementEntity->name." already booked";
    }
    if ($bookingElementEntity->access != 0 && $bookingElementEntity->access != 1){
      $isOk = false;
      $error .= "<br /> Please, select access from list";
    }
    $GLOBALS['error'] = $error;
    return $isOk;
  }

  public function addElement($name, $count, $access) {
    $entity = new BookingElementEntity(null, $name, $count, $access);
    if (!$this->validateCreate($entity)){
      return false;
    }
    $this->repository->addElement($entity);
    return true;
  }

  public function updateElement($id, $name, $count, $access) {
    $entity = new BookingElementEntity($id, $name, $count, $access);
    if (!$this->validateUpdate($entity)){
      return false;
    }
    $this->repository->updateElement($entity);
    return true;
  }

  public function getAllElements() {
    return $this->repository->getAllElements();
  }

  public function getAllElementNames() {
    return $this->repository->getAllElementNames();
  }

  public function getAllElementIds() {
    return $this->repository->getAllElementIds();
  }

  public function getAllAvailableElements() {
    return $this->repository->getAllAvailableElements();
  }

  public function deleteElement($id) {
    if (!$this->calendarRepository->checkDeleteElement($id)){
      return false;
    }
    return $this->repository->deleteElement($id);
    return true;
  }

  public function getElement($id) {
    return $this->repository->getElement($id);
  }

  public function getRemainingCountOfElements($elementId, $date) {
    $count = $this->getElement($elementId)->count;
    $booked = $this->calendarRepository->getBookedCount($elementId, $date);
    return $count - $booked;
  }
}
 ?>
