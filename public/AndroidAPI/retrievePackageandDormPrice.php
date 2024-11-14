<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];
$packagecolumn = (empty($_GET["packagecolumn"])) ? "":$_GET["packagecolumn"];
$checkindate = (empty($_GET["checkindate"])) ? "":$_GET["checkindate"];
$checkoutdate = (empty($_GET["checkoutdate"])) ? "":$_GET["checkoutdate"];

    if(empty($checkindate) && empty($checkoutdate))
    {
        $days = 0;
    }
    else
    {
        $date1 = new DateTime($checkindate);
        $date2 = new DateTime($checkoutdate);
        //exclude saturday and sunday
        $interval = $date1->diff($date2);
        $days = $interval->d + 1  ;
    }
            

            

$query = "select 
a.".$packagecolumn.",
b.atddormprice
FROM 
`tblcourses` as a inner join tblcourseschedule as x 
on a.courseid=x.courseid 
inner join tblenroled as y 
on y.scheduleid=x.scheduleid 
inner join tbltraineeaccount as z 
on z.traineeid=y.traineeid 
inner join tbldorm as b 
on b.dormid=y.dormid
WHERE 
y.enroledid = (select enroledid from tblenroled where traineeid = ".$traineeid." order by enroledid DESC limit 1)";
        

$query_result = mysqli_query($con , $query );

$allData["Data"] = array();
while($row = mysqli_fetch_array($query_result))
{
            $Data = array();

            $Data[$packagecolumn] = $row[$packagecolumn];
            $Data["atddormprice"] = $row["atddormprice"];
            $Data["numberofdays"] = $days ;

            array_push($allData["Data"] , $Data);
}

echo json_encode($allData);


?>