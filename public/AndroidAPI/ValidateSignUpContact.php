<?php
include ('dbcon.php');
$mobile = (empty($_POST["mobilenumber"])) ? "":$_POST["mobilenumber"];
$email = (empty($_POST["email"])) ? "":$_POST["email"];


if(empty($mobile) or empty($email))
{
        echo "required";
}
else
{
        //check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
                $query = "select * from tbltraineeaccount 
                where contact_num = '".$mobile."' or email = '".$email."'  ";
                
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
        else
        {
                // Invalid email
                echo "invalidemail";
        }

        
}





?>