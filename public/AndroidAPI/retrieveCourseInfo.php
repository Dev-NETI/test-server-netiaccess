<?php
include ('dbcon.php');
$courseid = (empty($_GET["courseid"])) ? "":$_GET["courseid"];

$query = "SELECT 
coursetypeid,courselocationid,modeofdeliveryid,trainingdays
FROM 
`tblcourses` 
WHERE 
courseid = ".$courseid." ";

$query_result = mysqli_query($con , $query );

$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data["coursetypeid"] = $row["coursetypeid"];
        $Data["courselocationid"] = $row["courselocationid"];
        $Data["modeofdeliveryid"] = $row["modeofdeliveryid"];
        $Data["trainingdays"] = $row["trainingdays"];
        array_push($allData["Data"] , $Data);
}

echo json_encode($allData);



?>