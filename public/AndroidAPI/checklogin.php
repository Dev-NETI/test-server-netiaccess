<?php
include 'dbcon.php';

$username = (empty($_POST["username"])) ? "":$_POST["username"];
$password = (empty($_POST["password"])) ? "":$_POST["password"];

if(empty($username) or empty($password))
{
        echo "required";
}
else
{
          $query = "Select * from tbltraineeaccount where email='".$username."' and password='".$password."' and deletedid = 0 ";
          $result = mysqli_query($con , $query);
          if(mysqli_num_rows($result) > 0 )
          {
                echo "true";
          }
          else
          {
                echo "false";
          }

}

?>
