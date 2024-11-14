<?php
include ('dbcon.php');

$contact = (empty($_POST["contact"])) ? "":$_POST["contact"];

if(empty($contact))
{
        echo "required";
}
else
{
        $query = "select * FROM `tbltraineeaccount`  
        WHERE  
        email = '".$contact."' or contact_num = '".$contact."' LIMIT 1  ";
        
        $query_result = mysqli_query($con , $query );
        $row = mysqli_num_rows($query_result);
        
        if($row > 0)
        {
            while($row = mysqli_fetch_array($query_result))
            {
                        $traineeid = $row["traineeid"];

                        echo $traineeid;
                
            }
        }
        else
        {
                echo "false";
        }
        
        $con->close();
}




?>