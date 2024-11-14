<?php
include ('dbcon.php');

$traineeid = (empty($_POST["traineeid"])) ? "":$_POST["traineeid"];
$verificationcode = (empty($_POST["verificationcode"])) ?"":$_POST["verificationcode"];

//delete old verification code
deleteOldVerificationCode($traineeid);


$query = "insert into tblChangePWVerificationCode(traineeid,verificationcode) values (?,?)";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt , "is" , $traineeid , $verificationcode );

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


function deleteOldVerificationCode($traineeid)
{
    $query = "update 
    tblChangePWVerificationCode set deletedid = 1 where traineeid = ? ";
    include ('dbcon.php');

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt , "i" , $traineeid );
    
    mysqli_stmt_execute($stmt);
    
    if(mysqli_stmt_affected_rows($stmt) > 0 )
    {
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>