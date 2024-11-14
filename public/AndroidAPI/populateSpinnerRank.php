<?php
include ('dbcon.php');

$query = "select * FROM `tblrank` order by rank asc";
        

$query_result = mysqli_query($con , $query );




$allRank["Rank"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Rank = array();
        $Rank["id"] = $row["rankid"];
        $Rank["rank"] = $row["rank"];
        array_push($allRank["Rank"] , $Rank);
}

echo json_encode($allRank);



?>