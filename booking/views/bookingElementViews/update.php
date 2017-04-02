<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/BookingElementService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$name = "";
$count = "";
$access = "";
$service = new BookingElementService();
$nameBefore = "";
$id = "";
$error = "";
$accessVal1 = "";
$accessVal2 = "";

if(isset($_POST['updateFromIndex'])) {
  $id = $_POST['id'];
  $entity = $service->getElement($id);
  $name = $entity->name;
  $count = $entity->count;
  $access = $entity->access;
  $nameBefore = $entity->name;
  if ($access == 1){
    $accessVal1 = "selected";
    $accessVal2 = "";
  } else {
    $accessVal1 = "";
    $accessVal2 = "selected";
  }
}


if(isset($_POST['update'])) {
  $id = $_POST['id'];
  $id = $_POST['id'];
  $entity = $service->getElement($id);
  $nameBefore = $entity->name;
  $name = $_POST['name'];
  $count = $_POST['count'];
  $access = $_POST['access'];
  if ($access == 1){
    $accessVal1 = "selected";
    $accessVal2 = "";
  } else {
    $accessVal1 = "";
    $accessVal2 = "selected";
  }

  $isOk = $service->updateElement($id, $name, $count, $access);
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
<title>Booking Elements</title>
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
    <div class="headerLabel">Booking Element: <?php echo $nameBefore; ?></div>
  </div>
  <div class="inputDiv">
    <form action="update.php" method="post">
      <div class="field"><div class="label">Name: </div><input class="input" type="text" value="<?php echo $name; ?>" name="name" /></div>
      <div class="field"><div class="label">Count: </div><input class="input" type="number" value="<?php echo $count; ?>" name="count" /></div>
      <div class="field"><div class="label">Access: </div><select class="select" name="access">
        <option <?php echo $accessVal1 ?> value=1>Available</option>
        <option <?php echo $accessVal2 ?> value=0>Hidden</option>
      </select></div>
      <input hidden type="text" value="<?php echo $id; ?>" name="id" />
      <input class="headerButtonInput" type="submit" value="Update" name="update" />
    </form>
    <div class="error"><?php echo $error; ?></div>
  </div>
</body>
</html>
