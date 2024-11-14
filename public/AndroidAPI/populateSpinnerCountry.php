<?php
include ('dbcon.php');

$query = "SELECT * FROM `tblcountry` where IsDeleted = 0 order by country asc ";
        

$query_result = mysqli_query($con , $query );




$allCountry["Country"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Country = array();
        $Country["id"] = $row["id"];
        $Country["country"] = utf8_encode($row["country"]);
        array_push($allCountry["Country"] , $Country);
}

echo json_encode($allCountry);



?>