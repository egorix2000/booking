<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
class BookingHelper {

  function GetBookingForm() {
    return implode("", file(dirname(__FILE__).'/views/bookingViews/create.php'));
  }

}
 ?>
