<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];
$column = (empty($_GET["column"])) ? "":$_GET["column"];


$query = "select ".$column." from tbltraineeaccount 
where 
traineeid = ".$traineeid."  LIMIT 1 ";
        

$query_result = mysqli_query($con , $query );




while($row = mysqli_fetch_array($query_result))
{
        echo $row[$column];
}





?>