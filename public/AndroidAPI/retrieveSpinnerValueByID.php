<?php
include ('dbcon.php');

$getquery = (empty($_GET["getquery"])) ? "":$_GET["getquery"];
$column = (empty($_GET["column"])) ? "":$_GET["column"];
$columnid = (empty($_GET["columnid"])) ? "":$_GET["columnid"];

$query = $getquery;
        

$query_result = mysqli_query($con , $query );




$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data[$columnid] = $row[$columnid];
        $Data[$column] = $row[$column];
        array_push($allData["Data"] , $Data);
}

echo json_encode($allData);



?>