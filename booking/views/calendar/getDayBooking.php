<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/BookingService.class.php';
require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$month = $_REQUEST['month'];
$year = $_REQUEST['year'];
$day = $_REQUEST['day'];


date_default_timezone_set('UTC');
$bookingService = new BookingService();
$dayBookings = $bookingService->getDayBookings(strtotime($day.'-'.$month.'-'.$year));


$userFieldService = new UserFieldService();
$allFields = $userFieldService->getAllFields();

$headers = "";
foreach ($allFields as $value) {
  $headers .= "<td class='headerTable'>".$value->label."</td>";
}
$table = "<table><tr><td class='headerTable'>id</td><td class='headerTable'>elementId</td><td class='headerTable'>username</td><td class='headerTable'>email</td><td class='headerTable'>phone</td><td class='headerTable'>sendDate</td><td class='headerTable'>beginDate</td><td class='headerTable'>bookingDays</td>".$headers."</tr>";
foreach ($dayBookings as $entity) {
  $userFieldsValues = "";
  foreach ($allFields as $value) {
    $userFieldsValues .= "<td>".$entity->userFields[$value->label]."</td>";
  }
  $table .= "<tr><td>".$entity->id."</td><td>".$entity->elementId."</td><td>".$entity->username."</td><td>".$entity->email."</td><td>".$entity->phone."</td><td>".date("m/d/Y", $entity->sendDate)."</td><td>".date("m/d/Y", $entity->beginDate)."</td><td>".$entity->bookingDays."</td>".$userFieldsValues."</tr>";
}
$table .= "</table>";
echo $table;

?>
