<?php
include ('dbcon.php');

$getquery = (empty($_GET["getquery"])) ? "":$_GET["getquery"];
$column = (empty($_GET["column"])) ? "":$_GET["column"];

$query = $getquery;
        

$query_result = mysqli_query($con , $query );


while($row = mysqli_fetch_array($query_result))
{
        echo $row[$column];
}





?>