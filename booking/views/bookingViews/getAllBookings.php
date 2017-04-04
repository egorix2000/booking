<?php

  require_once '../../services/BookingService.class.php';
  require_once '../../services/UserFieldService.class.php';
  require_once dirname(__FILE__).'/../../global.inc.php';

  if (!defined('SITE_ADMIN')){
    header('Location: ../../permissionDenied.php');
    exit;
  }


  date_default_timezone_set('UTC');
  $service = new BookingService();
  $entities = $service->getAllBookings();

  $userFieldService = new UserFieldService();
  $allFields = $userFieldService->getAllFields();

  $allPages = ceil(count($entities)/30);

  $headers = array('id', 'elementId', 'username', 'email', 'phone', 'sendDate', 'beginDate', 'bookingDays');
  foreach ($allFields as $value) {
    array_push($headers, $value->label);
  }
  array_push($headers, 'actions');

  $allBookings = array();

  foreach ($entities as $entity) {
    $booking = array($entity->id, $entity->elementId, $entity->username, $entity->email, $entity->phone, date("m/d/Y", $entity->sendDate), date("m/d/Y", $entity->beginDate), $entity->bookingDays);

    foreach ($allFields as $value) {
      array_push($booking, $entity->userFields[$value->label]);
    }

    array_push($allBookings, $booking);
  }

  $response = array($allPages, $headers, $allBookings);
  echo json_encode($response);



  /*$headers = "";
  foreach ($allFields as $value) {
    $headers .= "<td class='headerTable'>".$value->label."</td>";
  }
  $table = "<table><tr><td class='headerTable'>id</td><td class='headerTable'>elementId</td>
  <td class='headerTable'>username</td><td class='headerTable'>email</td><td class='headerTable'>phone</td>
  <td class='headerTable'>sendDate</td><td class='headerTable'>beginDate</td>
  <td class='headerTable'>bookingDays</td>".$headers."<td class='headerTable'>actions</td></tr>";


  foreach ($entities as $entity) {
    $userFieldsValues = "";
    foreach ($allFields as $value) {
      $userFieldsValues .= "<td>".$entity->userFields[$value->label]."</td>";
    }
    $table .= "<tr><td>".$entity->id."</td><td>".$entity->elementId."</td><td>".$entity->username."</td><td>".$entity->email."</td><td>".$entity->phone."</td><td>".date("m/d/Y", $entity->sendDate)."</td>
    <td>".date("m/d/Y", $entity->beginDate)."</td>
    <td>".$entity->bookingDays."</td>".$userFieldsValues."
    <td><div class='tableActions' style='display: inline-block;'><form class='tableActions' action='update.php' method='post'><input hidden name='id' value=".$entity->id.">
    <input class='tableButtonUpdate' type='submit' value='Update' name='updateFromIndex' /></form></div>
    <div class='tableActions' style='display: inline-block;'>
    <button class='tableButtonDelete submit' id=".$entity->id.">Delete</button></div></td></tr>";
  }
  $table .= "</table>";*/
  ?>
