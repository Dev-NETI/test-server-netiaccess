<?php
include ('dbcon.php');

$email = (empty($_POST["email"])) ? "":$_POST["email"];
$password = (empty($_POST["password"])) ? "":$_POST["password"];

if(empty($password) or empty($email))
{
        echo "required";
}
else
{
        // $query = "select * FROM `tbltraineeaccount` 
        // WHERE 
        // `emailaddress` = '".$email."' LIMIT 1  ";
        $query = "select * FROM `tbltraineeaccount`  
        WHERE  
        email = '".$email."' LIMIT 1  ";
        
        $query_result = mysqli_query($con , $query );
        $row = mysqli_num_rows($query_result);
        
        if($row > 0)
        {
            while($row = mysqli_fetch_array($query_result))
            {
                    
                $retrievedpassword = $row["password"];

                if (verifyString($password, $retrievedpassword)) 
                {
                        $traineeid = $row["traineeid"];
                        echo $traineeid;
                } 
                else 
                {
                        echo "false";
                }
            }
        }
        else
        {
                echo "false";
        }
        
        $con->close();
}




function verifyString($string, $hashedString) {
        return password_verify($string, $hashedString);
    }

?>