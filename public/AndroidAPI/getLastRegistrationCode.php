<?php
include ('dbcon.php');

$query = "Select registrationcode FROM `tblenroled` order by enroledid desc limit 1 ";

$query_result = mysqli_query($con , $query );

while($row = mysqli_fetch_array($query_result))
{
    echo $row["registrationcode"];
}

?>