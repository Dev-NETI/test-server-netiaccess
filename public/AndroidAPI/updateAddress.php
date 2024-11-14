<?php
include ('dbcon.php');

$traineeid = $_POST["traineeid"];
$Region = $_POST["Region"];
// $Regionid = getValue("Select * from tblregion where regDesc = '".$Region."' ","id");
$Regionid = getValue("Select * from refregion where regDesc = '".$Region."' ","regCode");
$State = $_POST["State"];
// $Stateid = getValue("Select * from tblprovince where provDesc = '".$State."' ","id");
$Stateid = getValue("Select * from refprovince where provDesc = '".$State."' ","provCode");
$City = $_POST["City"];
// $Cityid = getValue("Select * from tblcitymunicipality where citymunDesc = '".$City."' ","id");
$Cityid = getValue("Select * from refcitymun where citymunDesc = '".$City."' ","citymunCode");
$Brgy = $_POST["Brgy"];
// $Brgyid = getValue("Select * from tblbrgy where brgyDesc = '".$Brgy."' ","id");
$Brgyid = getValue("Select * from refbrgy where brgyDesc = '".$Brgy."' ","brgyCode");
$Postalcode = $_POST["Postalcode"];
$StrtAddrss = $_POST["StrtAddrss"];

if(empty($Postalcode) or empty($StrtAddrss) )
{
            echo "missing";
}
else
{
            // $query = "update tbltraineeaccount 
            // set 
            // AdrsCountryid = ? , AdrsRegionid = ? , AdrsStateid = ? , 
            // AdrsCityid = ? , AdrsBrgyid = ? , AdrsPostalCode = ? , 
            // AdrsStrtAdrs = ? 
            // where 
            // traineeid = ? ";
            $query = "update tbltraineeaccount 
            set 
            regCode = ? , provCode = ? , 
            citynumCode = ? , brgyCode = ? , postal = ? , 
            address = ? 
            where 
            traineeid = ? ";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "iiiissi" , $Regionid , $Stateid , $Cityid , 
            $Brgyid , $Postalcode , $StrtAddrss , $traineeid);
            
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

            


//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//

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


?>