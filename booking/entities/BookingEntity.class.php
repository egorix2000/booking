<?php

class BookingEntity{

  public $id;
  public $elementId;
  public $username;
  public $email;
  public $phone;
  public $sendDate;
  public $beginDate;
  public $bookingDays;
  public $userFields; //associative array (field => value)

  function __construct($id, $elementId, $username, $email, $phone, $sendDate, $beginDate, $bookingDays, $userFields){
    $this->id = $id;
    $this->elementId = $elementId;
    $this->username = $username;
    $this->email = $email;
    $this->phone = $phone;
    $this->sendDate = $sendDate;
    $this->beginDate = $beginDate;
    $this->bookingDays = $bookingDays;
    $this->userFields = $userFields;
  }
}

 ?>
