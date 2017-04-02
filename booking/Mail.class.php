<?php
class Mail {

public static function smtpmail($to='', $mail_to, $subject, $message, $headers='') {
	$config = array();
  $config['smtp_username'] = $GLOBALS['smtp_username'];
  $config['smtp_port'] = $GLOBALS['smtp_port'];
  $config['smtp_host'] =  $GLOBALS['smtp_host'];
  $config['smtp_password'] = $GLOBALS['smtp_password'];
  $config['smtp_debug'] = $GLOBALS['smtp_debug'];
  $config['smtp_charset'] = $GLOBALS['smtp_charset'];
  $config['smtp_from'] = $GLOBALS['smtp_from'];

	$SEND =	"Date: ".date("D, d M Y H:i:s") . " UT\r\n";
	$SEND .= 'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
	if ($headers) $SEND .= $headers."\r\n\r\n";
	else
	{
			$SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
			$SEND .= "To: \"=?".$config['smtp_charset']."?B?".base64_encode($to)."=?=\" <$mail_to>\r\n";
			$SEND .= "MIME-Version: 1.0\r\n";
			$SEND .= "Content-Type: text/html; charset=\"".$config['smtp_charset']."\"\r\n";
			$SEND .= "Content-Transfer-Encoding: 8bit\r\n";
			$SEND .= "From: \"=?".$config['smtp_charset']."?B?".base64_encode($config['smtp_from'])."=?=\" <".$config['smtp_username'].">\r\n";
			$SEND .= "X-Priority: 3\r\n\r\n";
	}
	$SEND .=  $message."\r\n";
	 if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
		if ($config['smtp_debug']) echo $errno."<br>".$errstr;
		return false;
	 }

	if (!self::server_parse($socket, "220", __LINE__)) return false;

	fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");
	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>I can not send HELO!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "AUTH LOGIN\r\n");
	if (!self::server_parse($socket, "334", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>I can not find the answer to the authorization request.</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
	if (!self::server_parse($socket, "334", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Login authorization was not accepted by the server!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
	if (!self::server_parse($socket, "235", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>The password was not accepted by the server as true! Authorisation Error!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>I can not send the MAIL FROM command: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");

	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>I can not send the RCPT TO command: </p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "DATA\r\n");

	if (!self::server_parse($socket, "354", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>I can not send the DATA command</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, $SEND."\r\n.\r\n");

	if (!self::server_parse($socket, "250", __LINE__)) {
		if ($config['smtp_debug']) echo '<p>Could not send the body of the letter. The letter was not sent!</p>';
		fclose($socket);
		return false;
	}
	fputs($socket, "QUIT\r\n");
	fclose($socket);
	return TRUE;
}

public static function server_parse($socket, $response, $line = __LINE__) {
	global $config;
	while (@substr($server_response, 3, 1) != ' ') {
		if (!($server_response = fgets($socket, 256))) {
			if ($config['smtp_debug']) echo "<p>Problems with sending mail!</p>$response<br>$line<br>";
 			return false;
 		}
	}
	if (!(substr($server_response, 0, 3) == $response)) {
		if ($config['smtp_debug']) echo "<p>Problems with sending mail!</p>$response<br>$line<br>";
		return false;
	}
	return true;
}
}
 ?>
