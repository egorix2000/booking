<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once dirname(__FILE__).'/../repositories/UserFieldRepository.class.php';
require_once dirname(__FILE__).'/../repositories/BookingRepository.class.php';
require_once dirname(__FILE__).'/../entities/UserFieldEntity.class.php';
require_once dirname(__FILE__).'/../global.inc.php';

class UserFieldService {

  public $repository = null;
  public $bookingRepository = null;

  public function __construct(){
    $this->repository = new UserFieldRepository();
    $this->bookingRepository = new BookingRepository();
  }

  public function validate($userFieldEntity){
    $isOk = true;
    $error = "Errors: ";
    if ($userFieldEntity->label == null || $userFieldEntity->label == ""){
      $isOk = false;
      $error .= "<br /> Label can't be empty";
    }
    if ($userFieldEntity->type != 'number' && $userFieldEntity->type != 'text' && $userFieldEntity->type != 'date'){
      $isOk = false;
      $error .= "<br /> Please, select type from list";
    }
    if ($userFieldEntity->access != 0 && $userFieldEntity->access != 1){
      $isOk = false;
      $error .= "<br /> Please, select access from list";
    }
    if ($userFieldEntity->isRequired != 0 && $userFieldEntity->isRequired != 1){
      $isOk = false;
      $error .= "<br /> Please, select yes or no in require field";
    }
    $GLOBALS['error'] = $error;
    return $isOk;
  }

  public function addField($type, $access, $isRequired, $placeholder, $label) {
    $entity = new UserFieldEntity(null, $type, $access, $isRequired, $placeholder, $label);
    if (!$this->validate($entity)){
      return false;
    }
    $this->repository->addField($entity);
    $entity->id = $this->repository->getLastId();
    $this->bookingRepository->addColumn($entity);
    return true;
  }

  public function updateField($id, $type, $access, $isRequired, $placeholder, $label) {
    $entity = new UserFieldEntity($id, $type, $access, $isRequired, $placeholder, $label);
    if (!$this->validate($entity)){
      return false;
    }
    $this->bookingRepository->renameColumn($this->getField($id)->label, $label);
    $this->repository->updateField($entity);
    return true;
  }

  public function getAllFields() {
    return $this->repository->getAllFields();
  }

  public function deleteField($id, $label) {
    $this->bookingRepository->deleteColumn($label);
    return $this->repository->deleteField($id);
  }

  public function getField($id) {
    return $this->repository->getField($id);
  }
}
 ?>
