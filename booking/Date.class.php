<?php
class Date {
  public static function compareDate($d1, $d2){
    $date1 = explode("/", $d1);
    $date2 = explode("/", $d2);
    if ($date1[2] != $date2[2]){
      return $date1[2] - $date2[2];
    }
    if ($date1[1] != $date2[1]){
      return $date1[1] - $date2[1];
    }
    if ($date1[0] != $date2[0]){
      return $date1[0] - $date2[0];
    }
    return 0;
  }
}
 ?>
