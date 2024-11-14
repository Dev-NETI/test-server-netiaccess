<?php
include ('dbcon.php');

$query = "SELECT * FROM `tblbusmode` order by id DESC ";
        

$query_result = mysqli_query($con , $query );




$allBusmode["Busmode"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Busmode = array();
        $Busmode["id"] = $row["id"];
        $Busmode["busmode"] = $row["busmode"];
        array_push($allBusmode["Busmode"] , $Busmode);
}

echo json_encode($allBusmode);



?>