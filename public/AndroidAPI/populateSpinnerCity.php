<?php
include ('dbcon.php');
$statedesc = (empty($_GET["statedesc"])) ? "":$_GET["statedesc"];

$query = "select  
        a.id,a.citymunDesc 
        FROM 
        `refcitymun` as a inner join `refprovince` as b 
        on a.provcode=b.provCode  
        where 
        b.provDesc = '".$statedesc."' ";
        

$query_result = mysqli_query($con , $query );




$allCity["City"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $City = array();
        $City["id"] = $row["id"];
        $City["citymunDesc"] = utf8_encode($row["citymunDesc"]);
        array_push($allCity["City"] , $City);
}

echo json_encode($allCity);



?>