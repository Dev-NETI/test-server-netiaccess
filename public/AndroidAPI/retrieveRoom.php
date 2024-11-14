<?php
include ('dbcon.php');

$query = "SELECT * FROM `tbldorm` order by dormid ASC ";
        

$query_result = mysqli_query($con , $query );




$allDorm["Dorm"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Dorm = array();
        $Dorm["dormid"] = $row["dormid"];
        $Dorm["dorm"] = $row["dorm"];
        array_push($allDorm["Dorm"] , $Dorm);
}

echo json_encode($allDorm);



?>