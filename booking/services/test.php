<?php

ini_set("SMTP", "ssl://smtp.mail.ru");
ini_set("sendmail_from", "test@mail.ru");

/*require_once '../BookingHelper.class.php';

$h = new BookingHelper();
echo $h->GetBookingForm();*/

$result = mail('test@mail.ru', 'subject', 'message');

if($result)
{
	echo 'aaa';
}
else
{
	echo 'error';
}

?>
