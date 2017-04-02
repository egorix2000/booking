<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once dirname(__FILE__).'/../repositories/BookingRepository.class.php';
require_once dirname(__FILE__).'/../entities/BookingEntity.class.php';
require_once dirname(__FILE__).'/../entities/CalendarEntity.class.php';
require_once dirname(__FILE__).'/../repositories/CalendarRepository.class.php';
require_once dirname(__FILE__).'/../repositories/BookingElementRepository.class.php';
require_once dirname(__FILE__).'/../repositories/UserFieldRepository.class.php';
require_once dirname(__FILE__).'/../global.inc.php';
require_once dirname(__FILE__).'/../Mail.class.php';

class BookingService {

  public $repository = null;
  public $userFieldRepository = null;
  public $calendarRepository = null;
  public $bookingElementRepository = null;

  public function __construct(){
    $this->repository = new BookingRepository();
    $this->userFieldRepository = new UserFieldRepository();
    $this->calendarRepository = new CalendarRepository();
    $this->bookingElementRepository = new BookingElementRepository();
  }

  public function validateCreate($bookingEntity){
    $isOk = true;
    $error = "Errors: ";
    if ($this->bookingElementRepository->getElement($bookingEntity->elementId) == null ||
          $this->bookingElementRepository->getElement($bookingEntity->elementId)->access == 0){
      $isOk = false;
      $error .= "<br /> Booking element doesn't exist";
    }
    if ($bookingEntity->username == null || $bookingEntity->username == ""){
      $isOk = false;
      $error .= "<br /> Username can't be empty";
    }
    if (!filter_var($bookingEntity->email, FILTER_VALIDATE_EMAIL)){
      $isOk = false;
      $error .= "<br /> Email doesn't exist";
    }
    if ($bookingEntity->phone == null || $bookingEntity->phone == "" || !preg_match("/^[\+0-9\-\s]*$/", $bookingEntity->phone)){
      $isOk = false;
      $error .= "<br /> Invalid phone number";
    }
    if ($bookingEntity->bookingDays < 1 || $bookingEntity->bookingDays > 29){
      $isOk = false;
      $error .= "<br /> Count of days must be from 1 to 29";
    }
    if ($bookingEntity->beginDate == null || $bookingEntity->beginDate == "" || !$this->checkDate($bookingEntity->beginDate)){
      $isOk = false;
      $error .= "<br /> Begin Date: invalid date (format: mm/dd/yyyy)";
    }
    foreach ($bookingEntity->userFields as $key => $value) {
      $userField = $this->userFieldRepository->getFieldByLabel($key);
      if ($userField->isRequired == 1){
        if ($bookingEntity->userFields[$userField->label] == null || $bookingEntity->userFields[$userField->label] == ""){
          $isOk = false;
          $error .= "<br /> " . $userField->label . " can't be empty";
        }
        else if ($userField->type == 'number' && !preg_match("/^[0-9]*$/", $bookingEntity->userFields[$userField->label])){
          $isOk = false;
          $error .= "<br /> " . $userField->label . " must be a number";
        }
        else if ($userField->type == 'date' && !$this->checkDate($bookingEntity->userFields[$userField->label])){
          $isOk = false;
          $error .= "<br /> " . $userField->label . ": invalid date (format: mm/dd/yyyy)";
        }
      }
    }
    $GLOBALS['error'] = $error;
    return $isOk;
  }

  public function validateUpdate($bookingEntity){
    $isOk = true;
    $error = "Errors: ";
    if ($bookingEntity->username == null || $bookingEntity->username == ""){
      $isOk = false;
      $error .= "<br /> Username can't be empty";
    }
    if (!filter_var($bookingEntity->email, FILTER_VALIDATE_EMAIL)){
      $isOk = false;
      $error .= "<br /> Email doesn't exist";
    }
    if ($bookingEntity->phone == null || $bookingEntity->phone == "" || !preg_match("/^[\+0-9]*$/", $bookingEntity->phone)){
      $isOk = false;
      $error .= "<br /> Invalid phone number";
    }
    if ($bookingEntity->sendDate == null || $bookingEntity->sendDate == "" || !$this->checkDate($bookingEntity->sendDate)){
      $isOk = false;
      $error .= "<br /> Send Date: invalid date (format: mm/dd/yyyy)";
    }
    foreach ($bookingEntity->userFields as $key => $value) {
      $userField = $this->userFieldRepository->getFieldByLabel($key);
      if ($userField->isRequired == 1){
        if ($bookingEntity->userFields[$userField->label] == null || $bookingEntity->userFields[$userField->label] == ""){
          $isOk = false;
          $error .= "\n " . $userField->label . " can't be empty";
        }
        else if ($userField->type == 'number' && !preg_match("/^[0-9]*$/", $bookingEntity->userFields[$userField->label])){
          $isOk = false;
          $error .= "\n " . $userField->label . " must be a number";
        }
        else if ($userField->type == 'date' && !$this->checkDate($bookingEntity->userFields[$userField->label])){
          $isOk = false;
          $error .= "<br /> " . $userField->label . ": invalid date (format: mm/dd/yyyy)";
        }
      }
    }
    $GLOBALS['error'] = $error;
    return $isOk;
  }

  private function checkDate($date){
    $date = explode("/", $date);
    if (count($date) != 3){
      return false;
    }
    if ($date[0] == "" || $date[1] == "" || $date[2] == ""){
      return false;
    }
    if (!checkdate($date[0], $date[1], $date[2])){
      return false;
    }
    return true;
  }

  public function addBooking($elementId, $username, $email, $phone, $sendDate, $dateFrom, $bookingDays, $userFields) {
    $entity = new BookingEntity(null, $elementId, $username, $email, $phone, $sendDate, $dateFrom, $bookingDays, $userFields);
    if (!$this->validateCreate($entity)){
      return false;
    }
    date_default_timezone_set('UTC');
    $dateFrom = explode("/", $dateFrom);
    $dateFrom = strtotime($dateFrom[1].'-'.$dateFrom[0].'-'.$dateFrom[2]);
    $entity->beginDate = $dateFrom;
    $count = $this->bookingElementRepository->getElement($elementId)->count;
    if ($this->calendarRepository->checkBooking($elementId, $dateFrom, $bookingDays, $count)) {
      if (Mail::smtpmail($username, $email, 'Booking', 'Your reservation accepted') &&
            Mail::smtpmail('admin', $GLOBALS['adminEmail'], 'Booking', 'User: '.$username.' booked element with id: '.$elementId)){
        $this->calendarRepository->addBooking($elementId, $dateFrom, $bookingDays);
        $this->repository->addBooking($entity);
        return true;
      }
      $GLOBALS['error'] = 'Impossible to send email. Check entered email';
      return false;
    }
    $days = $this->calendarRepository->getAllBookedDays($elementId, $dateFrom, $bookingDays, $count);
    $daysString = "";
    foreach ($days as $value) {
      $daysString .= ' '.date('m/d/Y', $value);
    }
    $GLOBALS['error'] = "Errors: \n All elements: ".$this->bookingElementRepository->getElement($elementId)->name." are already booked at these days:".$daysString;
    return false;
  }

  public function updateBooking($id, $username, $email, $phone, $sendDate, $userFields) {
    $entity = new BookingEntity($id, null, $username, $email, $phone, $sendDate, null, null, $userFields);
    if (!$this->validateUpdate($entity)){
      return false;
    }
    date_default_timezone_set('UTC');
    $dateTemp = explode("/", $sendDate);
    $sendDate = strtotime($dateTemp[1].'-'.$dateTemp[0].'-'.$dateTemp[2]);
    $entity->sendDate = $sendDate;
    $this->repository->updateBooking($entity);
    return true;
  }

  public function getAllBookings() {
    $userFields = $this->userFieldRepository->getAllFields();
    return $this->repository->getAllBookings($userFields);
  }

  public function deleteBooking($id) {
    $booking = $this->getBooking($id);
    $this->calendarRepository->deleteBooking(new CalendarEntity($booking->beginDate, $booking->elementId, null, $booking->bookingDays));
    return $this->repository->deleteBooking($id);
  }

  public function getBooking($id) {
    $userFields = $this->userFieldRepository->getAllFields();
    return $this->repository->getBooking($id, $userFields);
  }

  public function getTodayBookings(){
    $userFields = $this->userFieldRepository->getAllFields();
    return $this->repository->getTodayBookings($userFields);
  }

  public function getDayBookings($date){
    $userFields = $this->userFieldRepository->getAllFields();
    return $this->repository->getDayBookings($userFields, $date);
  }

  public function getLastThreeBookings(){
    $userFields = $this->userFieldRepository->getAllFields();
    return $this->repository->getLastThreeBookings($userFields);
  }
}
 ?>
