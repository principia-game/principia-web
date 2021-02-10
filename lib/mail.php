<?php

/**
 * Simple mail wrapper.
 *
 * @param string $address Email adress to send to.
 * @param string $subject Email subject.
 * @param string $body Email body.
 * @return bool Whether it was successful or not.
 */
function sendMail($address, $subject, $body) {
	global $email;

	if (!isset($email)) return;

	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = $email['host'];
	$mail->Port = $email['port'];
	$mail->isHTML();
	$mail->Username = $email['username'];
	$mail->Password = $email['password'];
	$mail->SetFrom($email['username'],'principia-web');
	$mail->Subject = $subject;
	$mail->Body = $body;

	$mail->AddAddress($address);

	return $mail->Send();
}