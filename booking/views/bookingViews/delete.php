<?php

require_once '../../services/BookingService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$id = $_REQUEST['id'];

$service = new BookingService();
$service->deleteBooking($id);

echo 1;

?>
