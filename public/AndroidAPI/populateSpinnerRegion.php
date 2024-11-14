<?php
include ('dbcon.php');

$query = "select * from refregion   order by id ASC ";
$query_result = mysqli_query($con , $query );




$allRegion["region"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Region = array();
        $Region["id"] = $row["id"];
        $Region["regDesc"] = utf8_encode($row["regDesc"]);
        array_push($allRegion["region"] , $Region);
}

echo json_encode($allRegion);



?>