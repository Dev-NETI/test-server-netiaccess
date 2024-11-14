<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daily/Weekly Reports Form</title>
    <style>
        #footer div {
            margin-top: 2.5em;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Style the table */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* Style the table header row */
        th {
            background-color: #a1a1a1;
            font-weight: bold;
            text-align: center;
            border: 1px solid #000;
            padding: 2px;
            font-size: 9px;
        }

        /* Style the table cells */
        td {
            text-align: center;
            border: 1px solid #000;
            padding: 2px;
            font-size: 8px;
        }

        /* Add a border to the last row of the table (optional) */
        tr:last-child td {
            border-bottom: 2px solid #000;
        }
    </style>
</head>

<body>
    <strong style="margin-left: 42%;">{{ strtoupper($type) }} REPORTS</strong>
    <br>
    <p style="margin-top: 1em; font-size: 12px;">Date Range: {{ $name }} <br>
        Status: @if ($status == 2)
        {{ strtoupper('Check Out') }}
        @elseif ($status == 1)
        {{ strtoupper('Check In') }}
        @elseif ($status == 4)
        {{ strtoupper('No Show') }}
        @else
        {{ strtoupper($status) }}
        @endif
    </p>

    @if ($type == 'daily')
    @php
    $count = 0;
    @endphp
    @foreach ($newDataCollection as $key1 => $value1)
    <table style="margin-top: 2em;">
        <thead>
            <tr>
                <th colspan="14">{{ date("l - F d, y", strtotime($totalCollection[$key1]['date'])) }}</th>
            </tr>
            <tr>
                <th>Room Type</th>
                <th>Room</th>
                <th>Name</th>
                <th>Company</th>
                <th>Mode of Payment</th>
                <th>Rank</th>
                <th>Course</th>
                <th>Training Date</th>
                <th>Check In Date</th>
                <th>Check Out Date</th>
                <th># of Days</th>
                <th>Status</th>
                <th>Room Rate</th>
                <th>Food Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($value1 as $data)
            <tr>
                <td>{{ $data['roomtype'] }}</td>
                <td>{{ $data['roomname'] }}</td>
                <td>{{ $data['trainee'] }}</td>
                <td>{{ $data['company'] }}</td>
                <td>{{ $data['paymentmode'] }}</td>
                <td>{{ $data['rank'] }}</td>
                <td>{{ $data['course'] }}</td>
                <td>{{ $data['schedule'] }}</td>
                <td>{{ $data['checkindate'] }}</td>
                <td>{{ $data['checkoutdate'] }}</td>
                <td>{{ $data['countdays'] }}</td>
                <td>{{ $data['status'] }} </td>
                <td>{{ $data['dormprice'] }}</td>
                <td>{{ $data['mealprice'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="14" style="text-align: left;">
                    <div style="display: inline-block; margin-top: 10px;">
                        <h3>Total Dorm: USD {{$totalCollection[$key1]['usdDormTotal']}}</h3>
                        <h3>Total Meal: USD {{$totalCollection[$key1]['usdMealTotal']}} </h3>
                        <h3>Total: USD {{$totalCollection[$key1]['usdMealTotal'] +
                            $totalCollection[$key1]['usdDormTotal']}} </h3>
                    </div>
                    <div style="display: inline-block; margin-left: 25px; margin-top: 10px;">
                        <h3>Total Dorm: PHP {{$totalCollection[$key1]['phpDormTotal']}} </h3>
                        <h3>Total Meal: PHP {{$totalCollection[$key1]['phpMealTotal']}} </h3>
                        <h3>Total: PHP {{$totalCollection[$key1]['phpDormTotal'] +
                            $totalCollection[$key1]['phpMealTotal']}} </h3>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
    @endforeach

    <div id="footer" style="margin-top: 2em; background: rgb(197, 197, 197);">
        @foreach ($totalCollection as $value)
        @php
        $usdDormTotal += $value['usdDormTotal'];
        $usdMealTotal += $value['usdMealTotal'];
        $phpMealTotal += $value['phpMealTotal'];
        $phpDormTotal += $value['phpDormTotal'];
        @endphp
        @endforeach
        <div style="font-size: 11px; font-weight: bold; display: inline-block; margin-left: 5px;"> Overall Total
            Lodging Rate: USD {{
            $usdDormTotal }} <br>
            Overall Total Meal Rate: USD {{ $usdMealTotal }} <br>
            Overall Total: USD {{ $usdDormTotal + $usdMealTotal }} </div>

        <div style="font-size: 11px; font-weight: bold; display: inline-block; margin-left: 23px;"> Overall Total
            Lodging Rate: PHP {{
            $phpDormTotal }} <br>
            Overall Total Meal Rate: PHP {{ $phpMealTotal }} <br>
            Overall Total: PHP {{ $phpMealTotal + $phpDormTotal }} </div>
    </div>




    @else
    <table>
        <thead>
            <tr>
                <th colspan="18">{{ $datefrom->format("F d, Y") }} - {{ $dateto->format("F d, Y") }}</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Reservation ID</th>
                <th>Room Type</th>
                <th>Room</th>
                <th>Name</th>
                <th>Company</th>
                <th>Mode of Payment</th>
                <th>Rank</th>
                <th>Course</th>
                <th>Training Date</th>
                <th># of Days</th>
                <th>Room Rate</th>
                <th>Food Rate</th>
                <th>Check In Date</th>
                <th>Check Out Date</th>
                <th>Status</th>
                <th>Total Lodging Rate</th>
                <th>Total Food Rate</th>
            </tr>
        </thead>
        <tbody>
            @php
            $x = 1;
            $totallodgingrateweekly = 0;
            $totalmealrateweekly = 0;
            @endphp
            @foreach ($weeklydatatable as $data)
            @if ($data['deletedid'] != 1)
            <tr style="font-size: 9px;">
                <td>{{ $data['x'] }}</td>
                <td>{{ $data['id'] }}</td>
                <td>{{ $data['roomtype'] }}</td>
                <td>{{ $data['roomname'] }}</td>
                <td>{{ $data['trainee'] }}</td>
                <td>{{ $data['company'] }}</td>
                <td>{{ $data['paymentmode'] }}</td>
                <td>{{ $data['rank'] }}</td>
                <td>{{ $data['course'] }}</td>
                <td>{{ $data['schedule'] }}</td>
                <td>{{ $data['days'] }}</td>
                <td>{{ $data['roomprice'] }}</td>
                <td>{{ $data['mealprice'] }}</td>
                <td>{{ $data['checkindate'] }}</td>
                <td>{{ $data['checkoutdate'] }}</td>
                <td>{{ $data['dormstatus'] }}</td>

                <td> {{ $data['totaldorm'] }}</td>
                <td> {{ $data['totalmeal'] }}</td>

            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div style="">
        <div style="display: inline-block; margin-top: .5em;">
            <p style="font-size: 11px; font-weight: bold;">
                Total Lodging Rate: USD {{ $overalltotaldormusd }} <br>
                Total Meal Rate: USD {{ $overalltotalmealusd }} <br>
                Overall Total: USD {{ $total = $overalltotaldormusd + $overalltotalmealusd }}
            </p>
        </div>

        <div style="display: inline-block; margin-top: .5em; margin-left: 45px;">
            <p style="font-size: 11px; font-weight: bold;">
                Total Lodging Rate: PHP {{ $overalltotaldormphp }} <br>
                Total Meal Rate: PHP {{ $overalltotalmealphp }} <br>
                Overall Total: PHP {{ $total = $overalltotaldormphp + $overalltotalmealphp }}
            </p>
        </div>
    </div>
    @endif
</body>

</html>