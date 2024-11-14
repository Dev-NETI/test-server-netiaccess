<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];

$query = "select 
a.coursecode,a.coursename,
b.startdateformat,b.enddateformat 
from 
tblcourses as a inner join tblcourseschedule as b 
on a.courseid=b.courseid 
inner join tblenroled as x 
on x.scheduleid=b.scheduleid 
where 
x.enroledid = (select enroledid from tblenroled where traineeid = ".$traineeid." order by enroledid DESC limit 1) ";
        
$query_result = mysqli_query($con , $query );

$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data["coursecode"] = $row["coursecode"];
        $Data["coursename"] = $row["coursename"];
        $Data["trainingdate"] = date_format(date_create($row["startdateformat"]) , "d M Y")." - ".date_format(date_create($row["enddateformat"]) , "d M Y");
        array_push($allData["Data"] , $Data);
}

echo json_encode($allData);



?>