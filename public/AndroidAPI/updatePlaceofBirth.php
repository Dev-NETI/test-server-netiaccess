<?php
include ('dbcon.php');

$traineeid = $_POST["traineeid"];
$BPAddrss = $_POST["BPAddrss"];

if(empty($BPAddrss) )
{
            echo "missing";
}
else
{
            $query = "update tbltraineeaccount 
            set  
            birthplace = ? 
            where 
            traineeid = ? ";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "si" , $BPAddrss , $traineeid);
            
            mysqli_stmt_execute($stmt);
            
            if(mysqli_stmt_affected_rows($stmt) > 0 )
            {
                echo "true";
            }
            else
            {
                echo "false";
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
}

?>