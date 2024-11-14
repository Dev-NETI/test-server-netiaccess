<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEEKLY TRAINING SCHEDULE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin-bottom: 20px;
            margin-top: 135px;
            /* Increase or decrease as needed */
            font-size: xx-small;
            /* background-image: url('assets/img/background.png'); */
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }

        table {
            font-size: xx-small;
            margin-bottom: 10px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 90px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }


        .logo-container .left {
            text-align: left;
        }

        .logo-container img {
            max-width: 60%;
            height: auto;
            display: inline-block;
            /* Use one of the following options to move the image up */
            /* Option 1: Use vertical-align */
            vertical-align: middle;
            /* Option 2: Use margin-top */
            margin-top: -40px;
        }

        .company-info pre {
            margin: 0;
        }

        table {
            font-size: 6px;
            width: 100%;
            table-layout: fixed;
        }

        th,
        .border-td {
            border: 1px solid black;
            padding: 2px;
        }

        th {
            text-align: center;
        }

        td {
            padding: 2px;
            background-color: white;
        }

        .indent {
            padding-left: 20px;
        }

        .column {
            float: left;
            width: 33.33%;
            padding: 10px;
            box-sizing: border-box;
        }

        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 90px;
            /* Adjust the height as needed */
            background-color: white;
            /* Set a background color for the header */
            z-index: 100;
            /* Ensure it's above other content */
        }
    </style>
</head>

<body>
    <header class="fixed-header">
        <div class="text-right" style="margin-top: -10px;">
            <small>Y-PRPD-007C</small>
        </div>
        <div class="row">
            <div class="column">
                <div class="logo-container">
                    <div class="left">
                        <img src="{{ public_path('assets/images/oesximg/nyk_line.png') }}" alt="" width="300" height="auto">
                    </div>
                    <br>
                </div>
            </div>
            <div class="column">
                <h6 class="font-weight-bold" style="line-height: 1.2;">NYK-TDG TRAINING CENTER<br>
                    NYK-FIL MARITIME E-TRAINING INC.<br>
                    WEEKLY TRAINING SCHEDULE <br> <br>
                    WEEK COVERED: {{ $formattedDateRange }} <br>
                </h6>
            </div>
            <div class="column">
                <div class="logo-container right">
                    <div class="left" style="margin-left: 1in; margin-top: 30px;">
                        <img src="{{ public_path('assets/images/oesximg/trans.png') }}" alt="" width="300" height="auto">
                    </div>
                    <br>
                </div>
            </div>
    </header>

    <footer class="w-100">
        <table class="w-100">
            <tr>
                <td style="width:70%">
                    <small><i>Revision: 13 as of 2023 June 14 (This document is system generated.)</i></small>
                </td>
                <td class="text-right" style="width:30%">
                    <small><i>Page <span class="pagenum"></span></i></small>
                </td>
            </tr>
        </table>
    </footer>

    <br>
    <br>

    <div class="table-container">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 2%;">#</th>
                    <th class="text-center" style="width: 5%;">BATCH NO.</th>
                    <th class="text-center" style="width: 8%;">FULL NAME</th>
                    <th class="text-center" style="width: 6%;">POSITION</th>
                    <th class="text-center" style="width: 6%;">COMPANY</th>
                    <th class="text-center" style="width: 5%;">BUS</th>
                    <th class="text-center" style="width: 5%;">ROOM TYPE</th>
                    <th class="text-center" style="width: 5%;">COURSE</th>
                    <th class="text-center" style="width: 6%;">START</th>
                    <th class="text-center" style="width: 6%;">END</th>
                    <th class="text-center" style="width: 5%;">FLEET</th>
                    <th class="text-center" style="width: 5%;">BIRTHDATE</th>
                    <th class="text-center" style="width: 5%;">MOBILE NUMBER</th>
                    <th class="text-center" style="width: 8%;">Email Address</th>
                    <th class="text-center" style="width: 6%;">ADDRESS</th>
                    <th class="text-center" style="width: 6%;">DATE ENROLLED</th>
                    <th class="text-center" style="width: 5%;">T-SHIRT</th>
                    <th class="text-center" style="width: 6%;">CHECK IN</th>
                    <th class="text-center" style="width: 6%;">CHECK OUT</th>
                    <th class="text-center" style="width: 6%;">MODE OF PAYMENT</th>
                    <th class="text-center" style="width: 6%;">ATTENDING</th>
                    <th class="text-center" style="width: 5%;">PEME</th>
                    <th class="text-center" style="width: 5%;">COP</th>
                    <th class="text-center" style="width: 8%;">ENROLLED BY</th>
                    <th class="text-center" style="width: 5%;">PLACE OF BIRTH</th>
                    <th class="text-center" style="width: 5%;">NATIONALITY</th>
                </tr>
            </thead>

            <tbody class="" style="font-size: 6px;">
                @if ($query)
                @if ($query->count())
                @php
                $counter = 1;
                @endphp

                <!-- inactive if 1 -->
                @foreach ($query as $enroll )
                <tr>
                    <td class="border-td text-center">
                        {{ $counter }}
                    </td>
                    <td class="border-td text-center">
                        <b>{{$enroll->schedule->batchno}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b class="text-uppercase">{{$enroll->trainee->formal_name()}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{$enroll->trainee->rank->rank}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{optional($enroll->trainee->company)->company}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{$enroll->bus->busmode}}</b>
                    </td>
                    @if (!is_null(optional($enroll->dorm)->dorm))
                    <td class="border-td text-center">
                        <b>{{ $enroll->dorm->dorm }}</b>
                    </td>

                    @else
                    <td class="border-td text-center">
                        <b>None</b>
                    </td>
                    @endif

                    <td class="border-td text-center">
                        <b>{{ $enroll->course->coursename }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->schedule->startdateformat }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->schedule->enddateformat }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->fleet->fleet }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->birthday }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->contact_num }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->email }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->address }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->dateconfirmed }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->tshirt->tshirt }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->checkindate }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->checkoutdate }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->payment->paymentmode }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b> @if ($enroll->isAttending == 1)
                            Yes
                            @else
                            No response
                            @endif
                        </b>
                    </td>

                    <td class="border-td text-center">
                        <b>N/A</b>
                    </td>

                    <td class="border-td text-center">
                        <b>N/A</b>
                    </td>

                    <td class="border-td text-center">
                        <b>Trainee</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->birthplace }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{ $enroll->trainee->nationality->nationality }}</b>
                    </td>
                </tr>
                @php
                $counter += 1;
                @endphp
                @endforeach
                @endif
                @endif

            </tbody>
        </table>
    </div>
</body>

</html>