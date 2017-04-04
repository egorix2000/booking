<?php

class BookingHelper {

  function GetBookingForm() {
    return implode("", file(dirname(__FILE__).'/views/bookingViews/create.php'));
  }

}
 ?>
