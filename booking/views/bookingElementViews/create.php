<?php

require_once '../../services/BookingElementService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$name = "";
$count = "";
$access = "";
$error = "";
$accessVal1 = "";
$accessVal2 = "";

if(isset($_POST['submit-form'])) {

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

$service = new BookingElementService();
$isOk = $service->addElement($name, $count, $access);

if (!$isOk){
  $error = $GLOBALS['error'];
}
else {
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
  <div class="headerLabel">Booking Element</div>
</div>

<div class="inputDiv">
  <form action="create.php" method="post">
    <div class="field"><div class="label">Name: </div><input class="input" type="text" value="<?php echo $name; ?>" name="name" /></div>
    <div class="field"><div class="label">Count: </div><input class="input" type="number" value="<?php echo $count; ?>" name="count" /></div>
    <div class="field"><div class="label">Access: </div><select class="select" name="access">
      <option <?php echo $accessVal1 ?> value=1>Available</option>
      <option <?php echo $accessVal2 ?> value=0>Hidden</option>
    </select></div>
    <input class="headerButtonInput" type="submit" value="Create" name="submit-form" />
  </form>
  <div class="error"><?php echo $error; ?></div>
<div>
</body>
</html>
