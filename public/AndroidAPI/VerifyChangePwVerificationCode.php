<?php
include ('dbcon.php');
$traineeid = (empty($_POST["traineeid"])) ? "":$_POST["traineeid"];
$verificationcode = (empty($_POST["verificationcode"])) ? "":$_POST["verificationcode"];


if(empty($traineeid) or empty($verificationcode))
{
        echo "required";
}
else
{
        $query = "select * FROM `tblChangePWVerificationCode` where  
        traineeid = ".$traineeid." and verificationcode = '".$verificationcode."' AND deletedid = 0 ";
        
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