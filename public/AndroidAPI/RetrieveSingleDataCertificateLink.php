<?php
include ('dbcon.php');

$getquery = (empty($_GET["getquery"])) ? "":$_GET["getquery"];
$column = (empty($_GET["column"])) ? "":$_GET["column"];

$query = $getquery;
        

$query_result = mysqli_query($con , $query );


while($row = mysqli_fetch_array($query_result))
{
        // echo $row[$column];

        $returnValue = substr($row[$column], 0, 3);
        if($returnValue == "../")
        {
            // echo "https://netionline.neti.com.ph/EnrolmentSystem2018/".substr($row[$column], 3);
        }
}





?>