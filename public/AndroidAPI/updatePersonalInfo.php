<?php
include ('dbcon.php');

$traineeid = $_POST["traineeid"];
$firstname = $_POST["firstname"];
$middlename = $_POST["middlename"];
$lastname = $_POST["lastname"];
$suffix = $_POST["suffix"];
$dateofbirth = $_POST["dateofbirth"];
$nationality = $_POST["nationality"];
$nationalityid = getValue("Select * from tblnationality where nationality = '".$nationality."' ","nationalityid");


if(empty($firstname) or empty($middlename) or empty($lastname) or empty($dateofbirth))
{
            echo "missing";
}
else
{
            // $query = "update tbltraineeaccount 
            // set 
            // firstname = ? , middlename = ? , lastname = ? , suffix = ? , 
            // nationalityid = ? , birthday = ? 
            // where 
            // traineeid = ? ";
            $query = "update tbltraineeaccount 
            set 
            f_name = ? , m_name = ? , l_name = ? , suffix = ? , 
            nationalityid = ? , birthday = ? 
            where 
            traineeid = ? ";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "ssssisi" , $firstname , $middlename , $lastname , $suffix , 
                        $nationalityid , $dateofbirth , $traineeid);
            
            mysqli_stmt_execute($stmt);
            
            if(mysqli_stmt_affected_rows($stmt) > 0 )
            {
                echo "true";
            }
            else
            {
                echo "false";
            }
            
            mysqli_stmt_close($stmt);
            mysqli_close($con);
}

            


//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//

function getValue($query,$columnid)
{
    include ('dbcon.php');
    $query_result = mysqli_query($con , $query );

    while($row = mysqli_fetch_array($query_result))
    {
           $columnvalue = $row[$columnid];
    }
    return $columnvalue;
}


?>