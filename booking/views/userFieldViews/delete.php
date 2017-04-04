<?php

require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$label = $_REQUEST['label'];
$id = $_REQUEST['id'];

$service = new UserFieldService();
$service->deleteField($id, $label);

echo 1;

?>
