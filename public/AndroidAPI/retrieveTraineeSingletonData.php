<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];

// $query = "select 
//         a.rankid,a.ranklevelid,a.rankdepartmentid, b.companyid, c.fleetid 
//         FROM 
//         tblrank as a inner join tbltraineeaccount as x 
//         on a.rankid=x.rankid 
//         inner join tblcompany as b 
//         on b.companyid=x.companyid 
//         inner join tblfleet as c 
//         on c.fleetid=x.fleetid 
//         where 
//         x.traineeid = ".$traineeid." ";
$query = "select 
a.rankid,a.ranklevelid,a.rankdepartmentid, b.companyid, c.fleetid 
FROM 
tblrank as a inner join tbltraineeaccount as x 
on a.rankid=x.rank_id   
inner join tblcompany as b 
on b.companyid=x.company_id  
inner join tblfleet as c 
on c.fleetid=x.fleet_id 
where 
x.traineeid = ".$traineeid." ";  

$query_result = mysqli_query($con , $query );




$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data["rankid"] = $row["rankid"];
        $Data["ranklevelid"] = $row["ranklevelid"];
        $Data["rankdepartmentid"] = $row["rankdepartmentid"];
        $Data["companyid"] = $row["companyid"];
        $Data["fleetid"] = $row["fleetid"];
        array_push($allData["Data"] , $Data);
}

echo json_encode($allData);



?>