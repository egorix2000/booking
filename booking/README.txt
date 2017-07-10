1) open booking/global.inc.php

2) fill $adminEmail

3) fill $path

4) use this code to print addBooking form:

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../BookingHelper.class.php';

$h = new BookingHelper();
echo $h->GetBookingForm();

?>


open booking/services/test.php to see example
