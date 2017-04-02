<?php

class CalendarEntity{

  public $beginDate;
  public $elementId;
  public $count;
  public $days;

  function __construct($beginDate, $elementId, $count, $days){
    $this->beginDate = $beginDate;
    $this->elementId = $elementId;
    $this->count = $count;
    $this->days = $days;
  }
}

 ?>
