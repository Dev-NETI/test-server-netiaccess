<?php
$tar_msg = "test";
$tar_mode = "text";
$label = "";
$priority = "1";
$clientid = "";

$username = "phdemosmsapi";
$password = "Mwt4nq8hr3kl2";
$callerid = "TXMSG";
$route_to = "api_send_sms";
  

   $tar_num = "639509098702";
   
    // create a new cURL resource
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,str_replace(" ","%20",  "https://www.sendquickasp.com/client_api/index.php?route_to=".$route_to."&username=".$username."&passwd=".$password."&tar_num=".$tar_num."&tar_msg=".$tar_msg."&callerid=".$callerid));
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // grab URL and pass it to the browser
    curl_exec($ch);
    

    // close cURL resource, and free up system resources
    curl_close($ch);

?>