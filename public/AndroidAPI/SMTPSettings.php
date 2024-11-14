<?php
//function to get smtp settings
//function to get smtp settings
//function to get smtp settings

$query = "select * from tblemailsettings where id = 1 ";

$query_result = mysqli_query($con , $query );

while($row = mysqli_fetch_array($query_result))
{
    $retsmtpsecure = $row["SMTPSecure"];
    $rethost = $row["HOST"];
    $retport = $row["PORT"];
    $retusername = $row["Username"];
    $retpassword = $row["Password"];
}
//function to get smtp settings
//function to get smtp settings
//function to get smtp settings
?>