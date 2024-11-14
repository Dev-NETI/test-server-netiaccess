<?php
include ('dbcon.php');

$company = (empty($_GET["company"])) ? "":$_GET["company"];


switch($company){
    case 'NYK-FIL SHIP MANAGEMENT INC.' : 
         $query = "select * from tblfleet where deletedid = 0 and fleetid != 16 order by fleet asc";
         break;
    default: 
        $query = "select * from tblfleet where fleetid = 16 ";
}
        

$query_result = mysqli_query($con , $query );




$allFleet["Fleet"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Fleet = array();
        $Fleet["id"] = $row["fleetid"];
        $Fleet["fleet"] = $row["fleet"];
        array_push($allFleet["Fleet"] , $Fleet);
}

echo json_encode($allFleet);



?>