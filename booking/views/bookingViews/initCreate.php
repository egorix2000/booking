<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../../services/BookingElementService.class.php';
require_once '../../services/UserFieldService.class.php';


$bookingElementService = new BookingElementService();
$bookingElements = $bookingElementService->getAllAvailableElements();

$bookingElementOptions = "";
foreach ($bookingElements as $value) {
  $bookingElementOptions .= "<option value=".$value->id.">".$value->name."</option>";
}

$userFieldService = new UserFieldService();
$userFields = $userFieldService->getAllFields();
$error = "";
$userFieldLabels = array();
$userFieldsBooking = array();
foreach ($userFields as $field) {
  array_push($userFieldLabels, $field->label);
  $userFieldsBooking[$field->label] = "";
}

$userFieldDivs = "";
foreach ($userFields as $field) {
  if ($field->access == 1){
    if ($field->type != 'date'){
      $userFieldDivs .= '<div class="field"><div class="label">'.$field->label.': </div><input class="input" type='.$field->type.' placeholder = '.$field->placeholder.' id="'.$field->label.'"></div>';
    } else {
      $userFieldDivs .= '<div class="field"><div class="label">'.$field->label.': </div><input type="text" placeholder = '.$field->placeholder.' class="datepicker input" id="'.$field->label.'"></div>';
    }
  } else {
    if ($field->type != 'date'){
      $userFieldDivs .= '<p><input type='.$field->type.' hidden placeholder = '.$field->placeholder.' id="'.$field->label.'"></p>';
    } else {
      $userFieldDivs .= '<p><input type="text" hidden placeholder = '.$field->placeholder.' class="datepicker" id="'.$field->label.'"></p>';
    }
  }
}

$response = array($bookingElementOptions, $userFieldDivs, $userFieldLabels);

echo json_encode($response);

 ?>
