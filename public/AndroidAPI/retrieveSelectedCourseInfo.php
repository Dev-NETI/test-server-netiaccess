<?php
include ('dbcon.php');

$courseid = (empty($_GET["courseid"])) ? "":$_GET["courseid"];
      
$query = "select 
        courselocationid,trainingdays,
        coursetypeid,modeofdeliveryid
        from 
        tblcourses 
        where 
        courseid = ".$courseid." ";

$query_result = mysqli_query($con , $query );




$allCourseInfo["CourseInfo"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $CourseInfo = array();
        $CourseInfo["courselocationid"] = $row["courselocationid"];
        $CourseInfo["trainingdays"] = $row["trainingdays"];
        $CourseInfo["coursetypeid"] = $row["coursetypeid"];
        $CourseInfo["modeofdeliveryid"] = $row["modeofdeliveryid"];

        array_push($allCourseInfo["CourseInfo"] , $CourseInfo);
}

echo json_encode($allCourseInfo);



?>