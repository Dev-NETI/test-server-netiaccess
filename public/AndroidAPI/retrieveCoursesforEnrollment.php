<?php
include ('dbcon.php');

$ranklevelid = (empty($_GET["ranklevelid"])) ? "":$_GET["ranklevelid"];
$rankdepartmentid = (empty($_GET["rankdepartmentid"])) ? "":$_GET["rankdepartmentid"];
$coursetypeid = (empty($_GET["coursetypeid"])) ? "":$_GET["coursetypeid"];



switch($coursetypeid)
{
        case 1: $query = "select 
        a.courseid,
        a.coursecode,a.coursename,
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursetypeid = 1 and a.deletedid = 0 
        order by a.coursecode asc"; break;
        case 2: $query = "select 
        a.courseid,
        a.coursecode,a.coursename, 
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursetypeid = 2  and a.deletedid = 0
        order by a.coursecode asc"; break;
        case 3: $query = "select 
        a.courseid,
        a.coursecode, a.coursename,
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursedepartmentid = ".$rankdepartmentid."  
        and 
        a.coursetypeid = 3  and a.deletedid = 0
        order by a.coursecode asc"; break;
        case 4: $query = "select 
        a.courseid,
        a.coursecode,a.coursename, 
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursedepartmentid = ".$rankdepartmentid."  
        and 
        a.coursetypeid = 4  and a.deletedid = 0
        order by a.coursecode asc"; break;
        case 7: $query = "select 
        a.courseid,
        a.coursecode,a.coursename, 
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursetypeid = 7  and a.deletedid = 0
        order by a.coursecode asc"; break;
        default: break;
}

        

$query_result = mysqli_query($con , $query );




$allData["Data"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Data = array();
        $Data["courseid"] = $row["courseid"];
        $Data["coursecode"] = $row["coursecode"]." / ".$row["coursename"];
        $Data["modeofdelivery"] = $row["modeofdelivery"];
        $Data["numberofdayonline"] = $row["numberofdayonline"];
        $Data["numberofdayonsite"] = $row["numberofdayonsite"];
        array_push($allData["Data"] , $Data);
}




if($coursetypeid == 3 || $coursetypeid == 4)
{
        $query2 = "select 
        a.courseid,
        a.coursecode, a.coursename,
        a.numberofdayonline,a.numberofdayonsite,
        b.modeofdelivery
        FROM 
        tblcourses as a inner join tblmodeofdelivery as b 
        on a.modeofdeliveryid=b.id
        where   
        a.coursedepartmentid = 4  
        and 
        a.coursetypeid = ".$coursetypeid."  and a.deletedid = 0
        order by a.coursecode asc";
        $query_result2 = mysqli_query($con , $query2 );
        while($row2 = mysqli_fetch_array($query_result2))
        {
                $Data = array();
                $Data["courseid"] = $row2["courseid"];
                $Data["coursecode"] = $row2["coursecode"]." / ".$row2["coursename"];
                $Data["modeofdelivery"] = $row2["modeofdelivery"];
                $Data["numberofdayonline"] = $row2["numberofdayonline"];
                $Data["numberofdayonsite"] = $row2["numberofdayonsite"];
                array_push($allData["Data"] , $Data);
        }
}

// sort($allData);

echo json_encode($allData);



?>