<?php
include ('dbcon.php');

$mobilenumber = (empty($_POST["mobilenumber"])) ? "":$_POST["mobilenumber"];
$email = (empty($_POST["email"])) ?"":$_POST["email"];
//$mobilenumber = "test";
//$email = "test";
//$verificationcode = "test";

$query = "update tblsignupverificationcode set IsDeleted = 1 where mobile = AND or email = ? ";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt , "ss" ,  $mobilenumber , $email);

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