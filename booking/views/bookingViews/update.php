<?php

require_once '../../services/BookingService.class.php';
require_once '../../services/BookingElementService.class.php';
require_once '../../services/UserFieldService.class.php';
require_once dirname(__FILE__).'/../../global.inc.php';

if (!defined('SITE_ADMIN')){
  header('Location: ../../permissionDenied.php');
  exit;
}

$elementId = "";
$username = "";
$email = "";
$phone = "";
$sendDate = "";
$service = new BookingService();
$userFieldService = new UserFieldService();
$idBefore = "";
$id = "";
$userFields = $userFieldService->getAllFields();
$userFieldsBooking = array();
date_default_timezone_set('UTC');
$error = "";


if(isset($_POST['updateFromIndex'])) {
  $id = $_POST['id'];
  $entity = $service->getBooking($id);
  $idBefore = $id;
  $elementId = $entity->elementId;
  $username = $entity->username;
  $email = $entity->email;
  $phone = $entity->phone;
  $sendDate = date('m/d/Y', $entity->sendDate);
  foreach ($entity->userFields as $key => $value) {
    $userFieldsBooking[$key] = $value;
  }

}


if(isset($_POST['update'])) {
  $id = $_POST['id'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $sendDate = $_POST['sendDate'];

  foreach ($userFields as $field) {
    $userFieldsBooking[$field->label] = isset($_POST[$field->label]) ? $_POST[$field->label] : "";
  }


  $isOk = $service->updateBooking($id, $username, $email, $phone, $sendDate, $userFieldsBooking);
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
<title>Bookings</title>
<link type="text/css" rel="stylesheet" href="../../styles/style.css"/>
<link type="text/css" rel="stylesheet" href="../../styles/styleInputs.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
  </script>
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
    <div class="headerLabel">Booking Id: <?php echo $idBefore; ?></div>
  </div>
  <div class="inputDiv">
    <form action="update.php" method="post">
      <div class="field"><div class="label">Username: </div><input class="input" type="username" value="<?php echo $username; ?>" name="username" /></div>
      <div class="field"><div class="label">Email: </div><input class="input" type="text" value="<?php echo $email; ?>" name="email" /></div>
      <div class="field"><div class="label">Phone: </div><input class="input" type="text" value="<?php echo $phone; ?>" name="phone" /></div>
      <div class="field"><div class="label">Send Date: </div><input type="text" class="datepicker input" value="<?php echo $sendDate; ?>" name="sendDate"></div>
      <?php foreach ($userFields as $field) {
        if ($field->type != 'date'){
          echo '<div class="field"><div class="label">'.$field->label.': </div><input class="input" type='.$field->type.' value="'.$userFieldsBooking[$field->label].'" name='.$field->label.'></div>';
        } else {
          echo '<div class="field"><div class="label">'.$field->label.': </div><input type="text" value="'.$userFieldsBooking[$field->label].'" class="datepicker input" name='.$field->label.'></div>';
        }
      } ?>
      <input hidden type="text" value="<?php echo $id; ?>" name="id" />
      <input class="headerButtonInput" type="submit" value="Update" name="update" />
    </form>
    <div class="error"><?php echo $error; ?></div>
  </div>
</body>
</html>
