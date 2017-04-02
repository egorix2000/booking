<?php

class UserFieldEntity{
  public $id;
  public $type;
  public $access;
  public $isRequired;
  public $placeholder;
  public $label;

  function __construct($id, $type, $access, $isRequired, $placeholder, $label){
    $this->id = $id;
    $this->type = $type;
    $this->access = $access;
    $this->isRequired = $isRequired;
    $this->placeholder = $placeholder;
    $this->label = $label;
  }
}

 ?>
