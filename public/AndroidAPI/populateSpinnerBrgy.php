<?php
include ('dbcon.php');
$citydesc = (empty($_GET["citydesc"])) ? "":$_GET["citydesc"];

$query = "select 
a.id,a.brgyDesc 
FROM  
`refbrgy` as a inner join refcitymun as b  
on a.citymunCode LIKE CONCAT('%', b.citymunCode , '%')
WHERE  
b.citymunDesc LIKE '".$citydesc."'  
order by 
a.id asc ";
        

$query_result = mysqli_query($con , $query );




$allBrgy["Brgy"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Brgy = array();
        $Brgy["id"] = $row["id"];
        $Brgy["brgyDesc"] = utf8_encode($row["brgyDesc"]);
        array_push($allBrgy["Brgy"] , $Brgy);
}

echo json_encode($allBrgy);



?>