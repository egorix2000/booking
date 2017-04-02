<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/BookingService.class.php';
require_once '../../services/BookingElementService.class.php';
require_once '../../services/UserFieldService.class.php';


$service = new BookingService();
$userFieldService = new UserFieldService();
$userFields = $userFieldService->getAllFields();
$error = "";

$userFieldsBooking = array();
foreach ($userFields as $field) {
  $userFieldsBooking[$field->label] = "";
}


$elementId = $_REQUEST['elementId'];
$username = $_REQUEST['username'];
$email = $_REQUEST['email'];
$phone = $_REQUEST['phone'];
date_default_timezone_set('UTC');
$sendDate = strtotime(date('d-m-Y'));
$dateFrom = $_REQUEST['dateFrom'];
$bookingDays = $_REQUEST['bookingDays'];

foreach ($userFields as $field) {
  $userFieldsBooking[$field->label] = isset($_REQUEST[$field->label]) ? $_REQUEST[$field->label] : "";
}


$isOk = $service->addBooking($elementId, $username, $email, $phone, $sendDate, $dateFrom, $bookingDays, $userFieldsBooking);
if (!$isOk){
  echo $GLOBALS['error'];
} else {
  echo 'Your reservation has been accepted';
}

?>
