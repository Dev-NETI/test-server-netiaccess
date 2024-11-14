<?php
include ('dbcon.php');
$mobile = (empty($_POST["mobilenumber"])) ? "":$_POST["mobilenumber"];
$email = (empty($_POST["email"])) ? "":$_POST["email"];
$verificationcode = (empty($_POST["verificationcode"])) ? "":$_POST["verificationcode"];


if(empty($verificationcode))
{
        echo "required";
}
else
{
        $query = "select * FROM 
        `tblsignupverificationcode` 
        WHERE 
        `verificationcode` = '".$verificationcode."' AND 
        `mobile` = '".$mobile."' AND 
        `email` = '".$email."' 
        AND `IsDeleted` = 0";
        
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