1) open booking/views/bookingViews/create.php

2) find var path = ""

3) change to correct path

4) use this code to print addBooking form:

<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once '../BookingHelper.class.php';

$h = new BookingHelper();
echo $h->GetBookingForm();

?>


open booking/services/test.php to see example

5) open booking/global.inc.php

6) fill $adminEmail, $smtp_from, $smtp_password, $smtp_username, $smtp_host

7) smtp_username must me from mail.yandex.ru
