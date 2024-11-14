<?php
include ('dbcon.php');

$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];
$query = "select * from tbltrainingcertificaterepo where traineeid = ".$traineeid." ";
        

$query_result = mysqli_query($con , $query );




$allCertificate["Certificate"] = array();

while($row = mysqli_fetch_array($query_result))
{
        $Certificate = array();
        $Certificate["id"] = $row["id"];
        $Certificate["coursecode"] = $row["coursecode"];
        $Certificate["trainingdate"] = $row["start"]." - ".$row["end"];
        $Certificate["path"] = $row["certificatePath"];

        array_push($allCertificate["Certificate"] , $Certificate);
}

echo json_encode($allCertificate);



?>