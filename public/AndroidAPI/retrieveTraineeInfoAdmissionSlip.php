<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];

$query = "select 
a.traineeid,concat(a.f_name,' ',substring(a.m_name, 1, 1),'. ',a.l_name,' ',a.suffix) as traineename ,
a.imagepath,
b.rank,
c.company
from 
tbltraineeaccount as a inner join tblrank as b 
on a.rank_id=b.rankid
inner join tblcompany as c 
on c.companyid=a.company_id 
where 
a.traineeid = ".$traineeid." ";
        
$query_result = mysqli_query($con , $query );

$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data["traineeid"] = $row["traineeid"];
        $Data["traineename"] = $row["traineename"];
        $Data["imagepath"] = $row["imagepath"];
        $Data["rank"] = $row["rank"];
        $Data["company"] = $row["company"];
        array_push($allData["Data"] , $Data);
}

echo json_encode($allData);



?>