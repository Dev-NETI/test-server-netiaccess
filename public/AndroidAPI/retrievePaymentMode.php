<?php
include ('dbcon.php');

$courseid = (empty($_GET["courseid"])) ? "":$_GET["courseid"];
$traineeid = (empty($_GET["traineeid"])) ? "":$_GET["traineeid"];


$query = "SELECT coursetypeid FROM `tblcourses` WHERE courseid = ".$courseid." ";
$query_result = mysqli_query($con , $query );
while($row = mysqli_fetch_array($query_result))
{
    $coursetypeid = $row["coursetypeid"];
}

$query2 = "SELECT fleet_id,company_id FROM `tbltraineeaccount` WHERE traineeid = ".$traineeid." ";
$query_result2 = mysqli_query($con , $query2 );
while($row2 = mysqli_fetch_array($query_result2))
{
    $fleetid = $row2["fleet_id"];
    $companyid = $row2["company_id"];
}

$allPaymentmode["Paymentmode"] = array();


                                          if($courseid == "113" || $courseid == "66" || $courseid == "65" || $courseid == "114")//company sponsored pag pdos at ap
                                          {
                                            $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 1  ";
                                          }
                                          else
                                          {
                                                        if($companyid != "1")// kung hindi nyk company sponsored at own pay lang
                                                        {
                                                               $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 1 OR paymentmodeid = 2";
                                                        }
                                                        else
                                                        {
                                                                  if($coursetypeid == "3" || $coursetypeid == "4")
                                                                  {
                                                                    $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 1  ";
                                                                  }
                                                                  else if($coursetypeid == "2")
                                                                  {
                                                                          if($fleetid == 10 || $fleetid == 17)
                                                                          {
                                                                            $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 4 OR paymentmodeid = 2 ";
                                                                          }
                                                                          else
                                                                          {
                                                                            $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 1 OR paymentmodeid = 2 
                                                                            OR paymentmodeid = 3 ";
                                                                          }
                                                                  }
                                                                  else//mandatory
                                                                  {
                                                                          if($fleetid == 10 || $fleetid == 17)
                                                                          {
                                                                            $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 4 OR paymentmodeid = 2 ";
                                                                          }
                                                                          else
                                                                          {
                                                                            $query3 = "SELECT * FROM `tblpaymentmode` where paymentmodeid = 3 OR paymentmodeid = 2 ";
                                                                          }
                                                                  }
                                                        }
                                          }







$query_result3 = mysqli_query($con , $query3 );
while($row3 = mysqli_fetch_array($query_result3))
{
    $Paymentmode = array();
    $Paymentmode["paymentmodeid"] = $row3["paymentmodeid"];
    $Paymentmode["paymentmode"] = $row3["paymentmode"];
    array_push($allPaymentmode["Paymentmode"] , $Paymentmode);
}







// 
echo json_encode($allPaymentmode);



?>