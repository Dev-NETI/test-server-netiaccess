<?php
include ('dbcon.php');

$traineeid = (empty($_POST["traineeid"])) ? "":$_POST["traineeid"];
$Password = (empty($_POST["password"])) ? "":$_POST["password"];


// echo $nationalityid."<br>".$BPCountryid."<br>".$BPRegionid."<br>".$BPStateid."<br>".$BPCityid."<br>".$BPBrgyid
// ."<br>".$AdrsCountryid."<br>".$AdrsRegionid."<br>".$AdrsStateid."<br>".$AdrsCityid."<br>".$AdrsBrgyid."<br>".$Rankid."<br>".
// $Companyid."<br>".$Fleetid;

$validationmsg = validatePassword($Password);
if($validationmsg === true)
{
            $query = "update 
            tbltraineeaccount set  password = ?  
            where 
            traineeid = ?  ";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "si" , laravelHash($Password) , $traineeid);
            
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
else
{
            echo $validationmsg;
}


//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
function laravelHash($password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    return $hashedPassword;
}
function getValue($query,$columnid)
{
    include ('dbcon.php');
    $query_result = mysqli_query($con , $query );

    while($row = mysqli_fetch_array($query_result))
    {
           $columnvalue = $row[$columnid];
    }
    return $columnvalue;
}

function validatePassword($password) {
    // Check password length
    if (strlen($password) < 8) {
        $validationmsg = "Password must be greater than 8 characters!";
        return $validationmsg;
    }

    // Check for at least one uppercase letter
    else if (!preg_match('/[A-Z]/', $password)) {
        $validationmsg = "Password must contain at least one uppercase letter";
        return $validationmsg;
    }

    // Check for at least one lowercase letter
    else if (!preg_match('/[a-z]/', $password)) {
        $validationmsg = "Password must contain at least one lowercase letter";
        return $validationmsg;
    }

    // Check for at least one digit
    else if (!preg_match('/\d/', $password)) {
        $validationmsg = "Password must contain at least one numerical character";
        return $validationmsg;
    }

    // Check for at least one special character
    else if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $validationmsg = "Password must contain at least one special character";
        return $validationmsg;
    }
    
    // Password is valid
    else{
        return true;
    }
    
}

?>