<?php

require_once '../../services/BookingElementService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$month = $_REQUEST['month'];
$year = $_REQUEST['year'];
$days = $_REQUEST['days'];

date_default_timezone_set('UTC');
$bookingElementService = new BookingElementService();

$entityNames = $bookingElementService->getAllElementNames();
$entityIds = $bookingElementService->getAllElementIds();
$calendar = array();
$monthDays = array();

for ($i = 0; $i < count($entityNames); $i++) {
  $monthDays = array();
  for ($t = 1; $t <= $days; $t++){
    array_push($monthDays, $bookingElementService->getRemainingCountOfElements($entityIds[$i], strtotime($t.'-'.$month.'-'.$year)));
  }
  array_push($calendar, $monthDays);
}

$response = array($entityNames, $calendar);

echo json_encode($response);
?>
