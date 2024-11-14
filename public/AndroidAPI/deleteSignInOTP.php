<?php
include ('dbcon.php');

$traineeid = (empty($_POST["traineeid"])) ? "":$_POST["traineeid"];

$query = "update tblotp set deletedid = 1 where traineeid = ? ";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt , "i" ,  $traineeid);

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


?>