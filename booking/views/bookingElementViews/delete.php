<?php

require_once '../../services/BookingElementService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$id = $_REQUEST['id'];

$service = new BookingElementService();
$isOk = $service->deleteElement($id);

if (!$isOk){
  echo "Error: This element booked, so you can not delete it";
}
else {
  echo 1;
}
?>
