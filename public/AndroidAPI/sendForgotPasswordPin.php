<?php
include ('dbcon.php');
include ('SendEmailFunction.php');

$otp = (empty($_POST["otp"])) ?"":$_POST["otp"];
$email = (empty($_POST["email"])) ?"":$_POST["email"];

ob_start();
$html = include 'ForgotPasswordEmailFormat.php';
$content = ob_get_clean();

//echo $content;

//send email
sendmail($content,$email,"Forgot Password Pin");

?>