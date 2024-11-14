<?php
include ('dbcon.php');

$traineeid = $_POST["traineeid"];
$Rank = $_POST["Rank"];
$Rankid = getValue("Select * from tblrank where rank = '".$Rank."' ","rankid");
$Company = $_POST["Company"];
$Companyid = getValue("Select * from tblcompany where company = '".$Company."' ","companyid");
$Fleet = $_POST["Fleet"];
$Fleetid = getValue("Select * from tblfleet where fleet = '".$Fleet."' ","fleetid");


            // $query = "update tbltraineeaccount 
            // set 
            // rankid = ? , companyid = ? , fleetid = ? 
            // where 
            // traineeid = ? ";
            $query = "update tbltraineeaccount 
            set 
            rank_id = ? , company_id = ? , fleet_id = ? 
            where 
            traineeid = ? ";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "iiii" , $Rankid , $Companyid , $Fleetid , $traineeid);
            
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