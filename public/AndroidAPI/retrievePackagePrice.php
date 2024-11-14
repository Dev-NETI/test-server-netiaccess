<?php
include ('dbcon.php');

$courseid = (empty($_GET["courseid"])) ? "":$_GET["courseid"];
$packagecolumn = (empty($_GET["packagecolumn"])) ? "":$_GET["packagecolumn"];

$query = "SELECT * FROM tblcourses where courseid = ".$courseid." ";
        

$query_result = mysqli_query($con , $query );

while($row = mysqli_fetch_array($query_result))
{
            $package = $row[$packagecolumn];

            echo $package;
}




?>