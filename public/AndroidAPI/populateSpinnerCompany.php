<?php
include ('dbcon.php');

$query = "Select companyid,company FROM tblcompany
ORDER BY 
  CASE 
    WHEN company = 'NYK-FIL SHIP MANAGEMENT INC.' THEN 0 
    WHEN company = 'WALK-IN' THEN 1 
    ELSE 2 
  END,
  company ASC";
        

$query_result = mysqli_query($con , $query );




$allCompany["Company"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Company = array();
        $Company["id"] = $row["companyid"];
        $Company["company"] = $row["company"];
        array_push($allCompany["Company"] , $Company);
}

echo json_encode($allCompany);



?>