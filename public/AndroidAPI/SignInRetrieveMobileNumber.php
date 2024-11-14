<?php
include ('dbcon.php');
$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];

// $query = "select cellphonenumber FROM `tbltraineeaccount` WHERE `traineeid` = ".$traineeid;
$query = "select contact_num FROM `tbltraineeaccount` WHERE `traineeid` = ".$traineeid;

$query_result = mysqli_query($con , $query );

while($row = mysqli_fetch_array($query_result))
{
        echo $row["contact_num"];
}


?>