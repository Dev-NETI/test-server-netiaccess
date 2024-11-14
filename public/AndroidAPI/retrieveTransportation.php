<?php
include ('dbcon.php');

$query = "SELECT * FROM `tblbus` order by busid asc ";
        

$query_result = mysqli_query($con , $query );




$allBus["Bus"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Bus = array();
        $Bus["busid"] = $row["busid"];
        $Bus["bus"] = $row["bus"];
        array_push($allBus["Bus"] , $Bus);
}

echo json_encode($allBus);



?>