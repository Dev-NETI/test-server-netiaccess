<?php
date_default_timezone_set('Asia/Manila');
include ('dbcon.php');

$mobilenumber = $_POST["mobilenumber"];
$email = $_POST["email"];
$firstname = $_POST["firstname"];
$middlename = $_POST["middlename"];
$lastname = $_POST["lastname"];
$suffix = $_POST["suffix"];
$dateofbirth = $_POST["dateofbirth"];
$nationality = $_POST["nationality"];
$nationalityid = getValue("Select * from tblnationality where nationality =
 '".$nationality."' ","nationalityid");
$Birthplace = $_POST["Birthplace"];
$AdrsRegion = $_POST["AdrsRegion"];
// $AdrsRegionid = getValue("Select * from tblregion where regDesc = '".$AdrsRegion."' ","id");
$AdrsRegionid = getValue("Select * from refregion where regDesc = '".$AdrsRegion."' ","regCode");
$AdrsState = $_POST["AdrsState"];
// $AdrsStateid = getValue("Select * from tblprovince where provDesc = '".$AdrsState."' ","id");
$AdrsStateid = getValue("Select * from refprovince where provDesc = '".$AdrsState."' ","provCode");
$AdrsCity = $_POST["AdrsCity"];
// $AdrsCityid = getValue("Select * from tblcitymunicipality where citymunDesc = '".$AdrsCity."' ","id");
$AdrsCityid = getValue("Select * from refcitymun where citymunDesc = '".$AdrsCity."' ","citymunCode");
$AdrsBrgy = $_POST["AdrsBrgy"];
// $AdrsBrgyid = getValue("Select * from tblbrgy where brgyDesc = '".$AdrsBrgy."' ","id");
$AdrsBrgyid = getValue("Select * from refbrgy where brgyDesc = '".$AdrsBrgy."' ","brgyCode");
$AdrsPostalCode = $_POST["AdrsPostalCode"];
$AdrsStrtAddrss = $_POST["AdrsStrtAddrss"];
$Rank = $_POST["Rank"];
$Rankid = getValue("Select * from tblrank where rank = '".$Rank."' ","rankid");
$Company = $_POST["Company"];
$Companyid = getValue("Select * from tblcompany where company = '".$Company."' ","companyid");
$Fleet = $_POST["Fleet"];
$Fleetid = getValue("Select * from tblfleet where fleet = '".$Fleet."' ","fleetid");
$Password = $_POST["Password"];
$Address = $_POST["Address"];
$imagepath = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png";

$designationid = "25";
// echo $nationalityid."<br>".$BPCountryid."<br>".$BPRegionid."<br>".$BPStateid."<br>".$BPCityid."<br>".$BPBrgyid
// ."<br>".$AdrsCountryid."<br>".$AdrsRegionid."<br>".$AdrsStateid."<br>".$AdrsCityid."<br>".$AdrsBrgyid."<br>".$Rankid."<br>".
// $Companyid."<br>".$Fleetid;
$currenttime = date("Y-m-d H:i:s");
$hashid = getHashid();

$validationmsg = validatePassword($Password);
if($validationmsg === true)
{
            $query = "insert into tbltraineeaccount(contact_num,email,l_name,f_name,m_name,suffix,birthday,birthplace,nationalityid,regCode,
            provCode,citynumCode,brgyCode,postal,street,
            rank_id,company_id,fleet_id,password,address,designationid,imagepath,created_at,updated_at,hash_id) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "ssssssssiiiiiisiiississss" , $mobilenumber , $email , $lastname , $firstname , $middlename , $suffix , $dateofbirth , 
            $Birthplace , $nationalityid , $AdrsRegionid , $AdrsStateid , $AdrsCityid , $AdrsBrgyid , $AdrsPostalCode , $AdrsStrtAddrss , 
            $Rankid , $Companyid , $Fleetid , laravelHash($Password), $Address , $designationid ,  $imagepath , $currenttime , $currenttime , $hashid);
            
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
function getHashid()
{
    include ('dbcon.php');
    $query = "select traineeid+1 from tbltraineeaccount 
    order by traineeid desc LIMIT 1";
    $query_result = mysqli_query($con , $query );

    while($row = mysqli_fetch_array($query_result))
    {
           $traineeid = $row["traineeid+1"];
           $hashid = laravelHash($traineeid);
    }
    return $hashid;
}
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