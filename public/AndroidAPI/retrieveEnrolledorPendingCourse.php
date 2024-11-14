<?php
include ('dbcon.php');

$query = (empty($_GET["query"])) ? "":$_GET["query"];
        

$query_result = mysqli_query($con , $query );




$allCourses["Courses"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Courses = array();
        $Courses["coursecode"] = $row["coursecode"];
        $Courses["startdateformat"] = $row["startdateformat"];
        $Courses["enddateformat"] = $row["enddateformat"];
        $Courses["bus"] = $row["bus"];
        $Courses["paymentmode"] = $row["paymentmode"];
        $Courses["dorm"] = $row["dorm"];

        array_push($allCourses["Courses"] , $Courses);
}

echo json_encode($allCourses);



?>