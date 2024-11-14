<?php
include ('dbcon.php');

$query = "select * from tblnationality where deletedid = 0  order by nationality ASC ";
$query_result = mysqli_query($con , $query );




$allNationality["nationality"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $nationality = array();
        $nationality["nationalityid"] = $row["nationalityid"];
        $nationality["nationality"] = $row["nationality"];
        array_push($allNationality["nationality"] , $nationality);
}

echo json_encode($allNationality);



?>