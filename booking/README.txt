1) open booking/global.inc.php

2) fill $adminEmail with email, which will receive information about bookings

3) fill $path with path to the folder "booking". Last symbol must be "/";

4) use this code to print "add booking form" on your page:

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../BookingHelper.class.php';

$h = new BookingHelper();
echo $h->GetBookingForm();
?>

5) open booking/services/test.php to see example

6) you can change style of "add booking form" in booking/views/bookingViews/create.php
