<?php
include ('dbcon.php');
$traineeid = (empty($_POST["traineeid"])) ? "":$_POST["traineeid"];
$otp = (empty($_POST["otp"])) ? "":$_POST["otp"];


if(empty($traineeid) or empty($otp))
{
        echo "required";
}
else
{
        $query = "select * FROM `tblotp` where  
        traineeid = ".$traineeid." and OTP = '".$otp."' AND deletedid = 0 ";
        
        $query_result = mysqli_query($con , $query );
        $row = mysqli_num_rows($query_result);
        
        if($row > 0)
        {
                echo "true";
        }
        else
        {
                echo "false";
        }
        
        $con->close();
}





?>