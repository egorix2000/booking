<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$type = "";
$isRequired = "";
$access = "";
$placeholder = "";
$label = "";
$service = new UserFieldService();
$labelBefore = "";
$id = "";
$error = "";
$accessVal1 = "";
$accessVal2 = "";
$typeVal1 = "";
$typeVal2 = "";
$typeVal3 = "";
$requiredVal1 = "";
$requiredVal2 = "";

if(isset($_POST['updateFromIndex'])) {
  $id = $_POST['id'];
  $entity = $service->getField($id);
  $labelBefore = $entity->label;
  $type = $entity->type;
  $isRequired = $entity->isRequired;
  $access = $entity->access;
  $placeholder = $entity->placeholder;
  $label = $entity->label;
  if ($access == 1){
    $accessVal1 = "selected";
    $accessVal2 = "";
  } else {
    $accessVal1 = "";
    $accessVal2 = "selected";
  }
  if ($type == "number"){
    $typeVal1 = "selected";
    $typeVal2 = "";
    $typeVal3 = "";
  } else if ($type == "text") {
    $typeVal1 = "";
    $typeVal2 = "selected";
    $typeVal3 = "";
  } else {
    $typeVal1 = "";
    $typeVal2 = "";
    $typeVal3 = "selected";
  }
  if ($isRequired == 1){
    $requiredVal1 = "selected";
    $requiredVal2 = "";
  } else {
    $requiredVal1 = "";
    $requiredVal2 = "selected";
  }
}


if(isset($_POST['update'])) {
  $id = $_POST['id'];
  $entity = $service->getField($id);
  $labelBefore = $entity->label;
  $type = $_POST['type'];
  $isRequired = $_POST['isRequired'];
  $access = $_POST['access'];
  $placeholder = $_POST['placeholder'];
  $label = $_POST['label'];
  if ($access == 1){
    $accessVal1 = "selected";
    $accessVal2 = "";
  } else {
    $accessVal1 = "";
    $accessVal2 = "selected";
  }
  if ($type == "number"){
    $typeVal1 = "selected";
    $typeVal2 = "";
    $typeVal3 = "";
  } else if ($type == "text") {
    $typeVal1 = "";
    $typeVal2 = "selected";
    $typeVal3 = "";
  } else {
    $typeVal1 = "";
    $typeVal2 = "";
    $typeVal3 = "selected";
  }
  if ($isRequired == 1){
    $requiredVal1 = "selected";
    $requiredVal2 = "";
  } else {
    $requiredVal1 = "";
    $requiredVal2 = "selected";
  }
  $isOk = $service->updateField($id, $type, $access, $isRequired, $placeholder, $label);
  if (!$isOk){
    $error = $GLOBALS['error'];
  } else {
    header('Location: index.php');
    exit;
  }
}

?>

<html>
<head>
<title>User Fields</title>
<link type="text/css" rel="stylesheet" href="../../styles/style.css"/>
<link type="text/css" rel="stylesheet" href="../../styles/styleInputs.css"/>
</head>
<body>
  <div class="menu">
    <div class="item"><form class="buttonItem" action="../bookingViews/viewPage.php" method="post"><input class="buttonItem" type="submit" value="View Page" name="menu" /></form></div>
    <div class="item"><form class="buttonItem" action="../bookingViews/index.php" method="post"><input class="buttonItem" type="submit" value="Bookings" name="menu" /></form></div>
    <div class="item"><form class="buttonItem" action="../bookingElementViews/index.php" method="post"><input class="buttonItem" type="submit" value="Booking elements" name="menu" /></form></div>
    <div class="item"><form class="buttonItem" action="../calendar/index.php" method="post"><input class="buttonItem" type="submit" value="Calendar" name="menu" /></form></div>
    <div class="item"><form class="buttonItem" action="../userFieldViews/index.php" method="post"><input class="buttonItem" type="submit" value="User Fields" name="menu" /></form></div>
  </div>
  <div class="header">
    <div class="headerLabel">User Field: <?php echo $labelBefore; ?></div>
  </div>
  <div class="inputDiv">
    <form action="update.php" method="post">
      <div class="field"><div class="label">Type: </div><select class="select" name="type">
        <option <?php echo $typeVal1 ?> value="number">Number</option>
        <option <?php echo $typeVal2 ?> value="text">Text</option>
        <option <?php echo $typeVal3 ?> value="date">Date</option>
      </select></div>
      <div class="field"><div class="label">Required: </div><select class="select" name="isRequired">
        <option <?php echo $requiredVal1 ?> value=1>Yes</option>
        <option <?php echo $requiredVal2 ?> value=0>No</option>
      </select></div>
      <div class="field"><div class="label">Access: </div><select class="select" name="access">
        <option <?php echo $accessVal1 ?> value=1>Available</option>
        <option <?php echo $accessVal2 ?> value=0>Hidden</option>
      </select></div>
      <div class="field"><div class="label">Placeholder: </div><input class="input" type="text" value="<?php echo $placeholder; ?>" name="placeholder" /></div>
      <div class="field"><div class="label">Label: </div><input class="input" type="text" value="<?php echo $label; ?>" name="label" /></div>

      <input hidden type="text" value="<?php echo $id; ?>" name="id" />
      <input class="headerButtonInput" type="submit" value="Update" name="update" />
    </form>
    <div class="error"><?php echo $error; ?></div>
  </div>
</body>
</html>
