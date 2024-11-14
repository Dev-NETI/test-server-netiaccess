<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVAILABLE COURSE</title>
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
            <!-- <small>Y-PRPD-007C</small> -->
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
                    AVAILABLE COURSE<br> <br>
                    WEEK COVERED: {{ $course_schedule->batchno }} <br>
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
                    <small><i>(This document is system generated.)</i></small>
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
        <table style="width: 100%; font-size: 10px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 2%;">#</th>
                    <th class="text-center" style="width: 5%;">BATCH NO.</th>
                    <th class="text-center" style="width: 8%;">TRAINING DATE</th>
                    <th class="text-center" style="width: 6%;">COURSE NAME</th>
                    <th class="text-center" style="width: 6%;"># OF PENDING</th>
                    <th class="text-center" style="width: 5%;"># OF ENROLED</th>
                    <th class="text-center" style="width: 6%;">Capacity</th>
                    <th class="text-center" style="width: 6%;">Minimum</th>
                </tr>
            </thead>

            <tbody class="" style="font-size: 10px;">
                @if ($training_schedules)
                @if ($training_schedules->count())
                @php
                $counter = 1;
                @endphp

                @foreach ($training_schedules as $schedule )
                <tr>
                    <td class="border-td text-center">
                        {{ $counter }}
                    </td>
                    <td class="border-td text-center">
                        <b>{{$schedule->batchno}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b class="text-uppercase">{{ date('d, F, Y', strtotime($schedule->startdateformat)) }} to {{ date('d,
                                    F, Y', strtotime($schedule->enddateformat)) }}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{$schedule->course->coursecode}}</b> - {{$schedule->course->coursename}}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{ $schedule->slot_pending_count }}</b>
                    </td>
                    <td class="border-td text-center">
                        <b>{{ $schedule->enrolled_pending_count }}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{$schedule->course->maximumtrainees}}</b>
                    </td>

                    <td class="border-td text-center">
                        <b>{{$schedule->course->minimumtrainees}}</b>
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