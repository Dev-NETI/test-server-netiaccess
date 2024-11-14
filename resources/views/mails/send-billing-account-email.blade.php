<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid black;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h4>Dear {{$user->certificate_name()}},</h4>

    <p>This email confirms that you are enrolled in {{$enrol->course->coursename}} on {{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}. To complete your payment, please find the relevant information below: </p>

    <!-- <h4>
        Bank Name: {{$billing->bankname}} <br>
        Account Holder's Name: {{$billing->accountname}} <br>
        Account Number: {{$billing->accountnumber}} <br>
    </h4> -->

    <table>
        <tr>
            <td colspan="10" class="center">
                <b>SUMMARY</b>
            </td>
        </tr>
        <tr>
            <td colspan="3"><b>Training Course:</b> <b><i><u>{{$enrol->course->coursename}}</u></i></b></td>
            <td colspan="2">Training Dates: <i>{{$enrol->schedule->startdateformat}} - {{$enrol->schedule->enddateformat}}</i> </td>
            <td colspan="5">Reference #: <i>{{$enrol->registrationcode}}</i> </td>
        </tr>
        <tr>
            <td colspan="2"><b>Package: </b> </td>
            <td colspan="3"><i> The selected package is #{{$enrol->t_fee_package}}
                    @if ($enrol->t_fee_package == 1)
                    (Training fee only)
                    @elseif ($enrol->t_fee_package == 2)
                    (Training schedule with Lunch Meal and Polo Shirt.)
                    @elseif ($enrol->t_fee_package == 3)
                    (Training schedule with Lunch Meal, Polo Shirt & Bus Round Trip.)
                    @elseif ($enrol->t_fee_package == 4)
                    (Training schedule with Lunch Meal, Polo Shirt and Daily Bus Round Trip.)
                    @endif
                </i>
            </td>
            <td colspan="2">Package Price: </td>
            <td colspan="3">₱ {{number_format($enrol->t_fee_price,2,'.',',')}}</td>
        </tr>
        <tr>
            <td colspan="10" class="center"> <b>DORMITORY BILL & MEAL</b> </td>
        </tr>
        <tr>
            <td colspan="3"><b>Room type:</b></td>
            @if ($enrol->dorm)
            <td colspan="2">{{$enrol->dorm->dorm}}</td>
            @else
            <td colspan="2"><i> N/A </i></td>
            @endif
            <td colspan="3"> <b>Dorm & Meal fee:</b></td>
            @if ($enrol->dorm_price)
            <td colspan="2">₱{{number_format($enrol->dorm_price + $enrol->meal_price, 2, '.', ',')}}</td>
            @else
            <td colspan="2"><i> N/A </i></td>
            @endif
        </tr>
        <tr>
            <td colspan="3"><b>Check in:</b></td>
            @if ($enrol->checkindate)
            <td colspan="2">{{$enrol->checkindate}}</td>
            @else
            <td colspan="2"><i> N/A </i></td>
            @endif
            <td colspan="3"><b>Check out:</b></td>
            @if ($enrol->checkindate)
            <td colspan="2">{{$enrol->checkoutdate}}</td>
            @else
            <td colspan="2"><i> N/A </i></td>
            @endif
        </tr>
        <tr>
            <td colspan="5"> <b>Transporation:</b> </td>
            <td colspan="5"> <i>
                    @if ($enrol->busmodeid == 1)
                    Round trip
                    @elseif ($enrol->busmodeid == 2)
                    Daily Round Trip
                    @else
                    None
                    @endif </i> </td>
        </tr>

        <tr>
            <td colspan="5"> <b>Payment Mode: </b> </td>
            <td colspan="5"> <i> @if ($enrol->paymentmodeid == 2)
                    Own Pay
                    @elseif ($enrol->paymentmodeid == 3)
                    Salary Deduction
                    @elseif ($enrol->paymentmodeid == 4)
                    NTIF Boarding Loan
                    @else
                    None
                    @endif </i>
            </td>
        </tr>

        <tr>
            <td colspan="5"><b> TOTAL AMOUNT TO BE PAID:</b> </td>
            <td colspan="5"><i>₱ {{ number_format($enrol->total, 2, '.', ',') }}</i></td>
        </tr>
    </table>

    <i>
        Kindly upload proof of payment to your netiaccess account. Please ensure that you accurately note our account details as any discrepancy can delay the processing of your payment. If you have any questions, please do not hesistate to call us at (049) 508-8600 or email at registrar@neti.com.ph. Our team will be more than happy to assist you.
        <br>
        Thank you for choosing {{$billing->accountname}} We look forward to serving you in your future training needs.
    </i>

    <h4>
        Best regards,
        <br>
        <br>
        Business Operation Department <br>
        {{$billing->accountname}}
    </h4>


    <small>Note: This email contains confidential information intended only for the recipient. If you have received this email in error, please notify the sender immediately and delete it from your system.</small>
</body>

</html>