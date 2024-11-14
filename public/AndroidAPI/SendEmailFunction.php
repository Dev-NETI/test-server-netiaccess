<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function sendmail($message,$emailto,$subject)
{
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        // $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        // $mail->Host = "smtp.hostinger.com";
        // $mail->Port = 465; // or 587
        // $mail->IsHTML(true);
        // $mail->Username = "OesXDev@oesxdev.neti.com.ph";
        // $mail->Password = "2023@OesXDev";                                      // TCP port to connect to
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; // or 587
        $mail->IsHTML(true);
        $mail->Username = "mailer@neti.com.ph";
        $mail->Password = "eqbyonrcwjmdvbhn";    

        //Recipients
        $mail->setFrom('mailer@neti.com.ph', 'NETI ENROLLMENT');
        //$mail->addAddress('sherwin.roxas@neti.com.ph', '');
        $mail->addAddress($emailto, '');
        $mail->addReplyTo('mailer@neti.com.ph', 'NETI ENROLLMENT');
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