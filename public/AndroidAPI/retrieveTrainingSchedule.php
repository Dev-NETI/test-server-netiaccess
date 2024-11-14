<?php
include ('dbcon.php');
date_default_timezone_set('Asia/Manila');

$courseid = (empty($_GET["courseid"])) ? "":$_GET["courseid"];

$query = "select * FROM 
`tblcourseschedule` 
WHERE 
courseid = ".$courseid." and startdateformat > '".date('Y-m-d')."' 
and batchno != 'Batch No.' 
and deletedid = 0 and cutoffid = 0 
order by 
startdateformat ASC ";

$query_result = mysqli_query($con , $query);

$allSchedule["Schedule"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Schedule = array();
        $Schedule["scheduleid"] = $row["scheduleid"];
        $Schedule["trainingdate"] = date('M. d, Y', strtotime($row["startdateformat"]))." to ".date('M. d, Y', strtotime($row["enddateformat"]));

        array_push($allSchedule["Schedule"] , $Schedule);
}

echo json_encode($allSchedule);



?>