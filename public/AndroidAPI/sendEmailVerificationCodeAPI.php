<?php
include ('getSMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';


$mobilenumber = (empty($_POST["mobilenumber"])) ? "":$_POST["mobilenumber"];
$email = (empty($_POST["email"])) ?"":$_POST["email"];
$verificationcode = (empty($_POST["verificationcode"])) ?"":$_POST["verificationcode"];

$message = "Your OES verification code is $verificationcode, please do not share this with anyone.";

//$mobilenumber = "test";
//$email = "sherwin.roxas@neti.com.ph";
//$verificationcode = "test";

ob_start();
$html = include 'emailFormat.php';
$content = ob_get_clean();
echo $content."<br>";


sendmail($content,$email,$retsmtpsecure,$rethost,$retport,$retusername,$retpassword,"Sign Up Verification Code");

function sendmail($message,$emailto,$retsmtpsecure,$rethost,$retport,$retusername,$retpassword,$subject)
{




  $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
  try {
      //Server settings
      $mail->IsSMTP(); // enable SMTP
      $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
      $mail->SMTPAuth = true; // authentication enabled
      $mail->SMTPSecure = $retsmtpsecure; // secure transfer enabled REQUIRED for Gmail
      $mail->Host = $rethost;
      $mail->Port = $retport; // or 587
      $mail->IsHTML(true);
      $mail->Username = $retusername;
      $mail->Password = $retpassword;                                      // TCP port to connect to

      //Recipients
      $mail->setFrom('netionline@neti.com.ph', 'NETI ENROLLMENT');
      //$mail->addAddress('sherwin.roxas@neti.com.ph', '');
	  	$mail->addAddress($emailto, '');
      $mail->addReplyTo('netionline@neti.com.ph', 'NETI ENROLLMENT');
      //$mail->addCC('noc@neti.com.ph');
      $mail->addCC('sherwin.roxas@neti.com.ph');



      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body    = $message;
      //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if($mail->send())
            {
              echo 'Message has been sent';
            }

      }
      catch (Exception $e)
      {
      echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
      }
}

?>