<?php
include ('dbcon.php');

$query = (empty($_GET["query"])) ? "":$_GET["query"];
$column = (empty($_GET["column"])) ? "":$_GET["column"];
        
$query_result = mysqli_query($con , $query );

while($row = mysqli_fetch_array($query_result))
{
        echo $row[$column];
}





?>