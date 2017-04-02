<?php

class BookingElementEntity{

  public $id;
  public $name;
  public $count;
  public $access;

  function __construct($id, $name, $count, $access){
    $this->id = $id;
    $this->name = $name;
    $this->count = $count;
    $this->access = $access;
  }
}

 ?>
