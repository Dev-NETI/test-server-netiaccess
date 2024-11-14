<?php
include ('dbcon.php');
$regiondesc = (empty($_GET["regiondesc"])) ? "":$_GET["regiondesc"];

$query = "select a.id,a.provDesc 
        FROM 
        `refprovince` as a inner join `refregion` as b 
        on a.regCode=b.regCode  
         where 
        b.regDesc = '".$regiondesc."' ";

$query_result = mysqli_query($con , $query );




$allProvince["province"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Province = array();
        $Province["id"] = $row["id"];
        $Province["provDesc"] = utf8_encode($row["provDesc"]);
        array_push($allProvince["province"] , $Province);
}

echo json_encode($allProvince);



?>