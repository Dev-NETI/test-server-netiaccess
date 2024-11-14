<?php
date_default_timezone_set('Asia/Manila'); 
include ('dbcon.php'); 
$traineeid = $_POST["traineeid"];
$courseid = $_POST["courseid"];
$scheduleid = $_POST["scheduleid"];
$paymentmode = $_POST["paymentmodeid"];
$transportation = $_POST["transportationid"];
$transportationmode = $_POST["transportationmodeid"];
$room = $_POST["roomid"];
$srn = $_POST["srn"];
$checkindate = $_POST["checkindate"];
$checkoutdate = $_POST["checkoutdate"];
$currenttime = date("Y-m-d H:i:s");

//
$paymentmodeid = getValue("Select * FROM `tblpaymentmode` WHERE paymentmode = '".$paymentmode."' ", "paymentmodeid");
$transportationid = getValue("Select * FROM `tblbus` where bus = '".$transportation."' ", "busid");
$transportationmodeid = getValue("Select * FROM `tblbusmode` where busmode = '".$transportationmode."' ", "id");
$roomid = getValue("Select * FROM `tbldorm` WHERE dorm = '".$room."' ", "dormid");

$year = date("Y");
$month = date("m");
$day = date("d");
$randomdigit = rand(10000, 99999);
// $registrationcode = date("Ym")."-".date("d")."-".rand(10000, 99999);
$registrationcode = $year.$month."-".$day."-".$randomdigit;



            //dorm and meal fee
            if($roomid == "1")//none
            {
                $meal_price = "0";
                $dorm_price = "0";
            }
            else
            {
                $mealfee = getValue("select atdmealprice FROM `tblatdmealprice` WHERE atdmealpriceid = 1", "atdmealprice");
                // $dormfee = getValue("select atddormprice FROM `tbldorm` WHERE dormid = ".$roomid." ", "atddormprice");
                $dormfee = 0;
                $days = number_of_working_days($checkindate, $checkoutdate);
                
                $meal_price = $days * $mealfee;
                $dorm_price = $days * $dormfee;
            }
            

            //package
            if($transportationmodeid == "0")//package 1
            {
                $t_fee_package = "1";
                $t_fee_price = getValue("select atdpackage1 FROM `tblcourses` WHERE courseid = ".$courseid." ", "atdpackage1");

                
            }
            else if($transportationmodeid == "1")//Package 3
            {
                $t_fee_package = "3";
                $t_fee_price = getValue("select atdpackage3 FROM `tblcourses` WHERE courseid = ".$courseid." ", "atdpackage3");
                
            }
            else if($transportationmodeid == "2")//package 2
            {
                $t_fee_package = "2";
                $t_fee_price = getValue("select atdpackage2 FROM `tblcourses` WHERE courseid = ".$courseid." ", "atdpackage2");
            }

            $totalprice = $t_fee_price + $meal_price + $dorm_price;
            
            $query = "insert into tblenroled (enroledid,scheduleid,courseid,traineeid,pendingid,deletedid,
            busid,paymentmodeid,dormid,busmodeid,checkindate,checkoutdate,created_at,updated_at,registrationcode,
            total,t_fee_package,t_fee_price,dorm_price,meal_price)
            values (NULL,?,?,?,1,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt , "iiiiiiisssssiiiii" , $scheduleid , $courseid , $traineeid , $transportationid , $paymentmodeid , $roomid , $transportationmodeid , 
            $checkindate , $checkoutdate , $currenttime , $currenttime , $registrationcode , $totalprice , $t_fee_package , $t_fee_price , $dorm_price , $meal_price);
            
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



//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------//
function generateRegistrationCode()
{
    
}
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
function number_of_working_days($from, $to) {
    $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
    $holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays)) continue;
        if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
        if (in_array($period->format('*-m-d'), $holidayDays)) continue;
        $days++;
    }
    return $days;
}
?>