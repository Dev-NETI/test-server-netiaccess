<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENDANCE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        body {
            margin-bottom: 100px;
            /* Increase this value as needed */
            margin-top: 30px;
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
            height: auto;
            /* Changed from fixed height to auto */
            text-align: center;
            padding-bottom: 10px;
            /* Add some padding at the bottom */
        }

        .pagenum:before {
            content: counter(page);
        }


        .logo-container {
            text-align: center;
        }

        .logo-container img {
            max-width: 40%;
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
            border-collapse: collapse;
            width: 100%;
        }

        th,
        .border-td {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            text-align: left;
            background-color: lightgray;
        }

        td {
            background-color: white;
        }

        td:nth-child(1) {
            width: 30%;
        }

        td:nth-child(2) {
            width: 5%;
        }

        td:nth-child(3) {
            width: 60%;
        }

        td:nth-child(4) {
            width: 23%;
        }

        td:nth-child(5),
        td:nth-child(6) {
            width: 10%;
        }

        td:nth-child(7) {
            width: 30%;
        }

        td:nth-child(8),
        td:nth-child(9) {
            width: 15%;
        }

        .indent {
            padding-left: 20px;
        }

        .am-pm-header {
            font-size: 5pt;
            border-top: 1px solid black;
        }
    </style>
</head>

<body>
    @for ($page = 1; $page <= $totalPages; $page++)
        @if ($page> 1)
        <div style="page-break-before: always;"></div>
        @endif

        <header>
            <div class="text-right" style="margin-top: -10px;">
                <small>F-NETI-026</small>
                <br>
                <small>S.N. ____</small>
            </div>
            <div class="logo-container">
                <img src="{{ public_path('assets/images/oesximg/NETI.png') }}" alt="" width="270" height="auto">
                <br>
            </div>
        </header>

        <footer class="w-100">
            @if($page == $totalPages)
            @if($schedule->course->coursetypeid == 3 || $schedule->course->coursetypeid == 4)
            <table class="w-70" style="margin: 0 auto;">
                <tr>
                    <td style="width:30%; border: 1px solid black; padding: 2px; text-align: center;" colspan="3">
                        <i>--- Document properties ---</i>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%; border: 1px solid black; padding: 2px; text-align: center;">
                        <i>Frequency of usage = Each Training</i>
                    </td>
                    <td style="width:30%; border: 1px solid black; padding: 2px; text-align: center;">
                        <i>Retention Period
                            = 5 years (electronic)</i>
                    </td>
                    <td style="width:30%; border: 1px solid black; padding: 2px; text-align: center;">
                        <i>Method of Retention = Upload to e -
                            TOM</i>
                    </td>
                </tr>
            </table>
            @endif
            @endif
            <table class="w-100" style="margin-top: 5px;">
                <tr>
                    <td style="width:70%">
                        <small><i>Revision: 31 as of 2024 September 30 (This document is system generated.)</i></small>
                    </td>
                    <td class="text-right" style="width:30%">
                        <small><i>Page {{ $page }} of {{ $totalPages }}</i></small>
                    </td>
                </tr>
            </table>
        </footer>

        <br>
        <br>
        <div class="header-container" style="margin-top: -25px;">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th colspan="7" class="text-center">TRAINING ATTENDANCE SHEET</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-td" style="width: 50%;">
                            <b>Course Code/Name: {{ $schedule->course->coursecode }} -
                                {{ $schedule->course->coursename }}</b>
                            <br>
                            <b>Training Schedule: {{ date('d F, Y', strtotime($schedule->startdateformat)) }} -
                                {{ date('d F, Y', strtotime($schedule->enddateformat)) }}</b>
                            <br>
                            @if ($schedule->instructor->user)
                            @if ($schedule->instructor->user->user_id != 93)
                            <b>Instructor: {{ $schedule->instructor->user->formal_name() }} </b>
                            @endif
                            @endif
                            <br>
                            <i class="text-danger">Non-disclosure Obligation: The undersigned individual acknowledges and
                                agrees that the training material utilized during the course is strictly confidential and
                                shall not be disclosed to any third party or posted on any social media platform. </i>
                            <br>
                            @if ($schedule->assessor->user)
                            @if ($schedule->assessor->user->user_id != 93)
                            <b>Assessor: {{ $schedule->assessor->user->formal_name() }}</b>
                            @endif
                            @endif
                        </td>
                        <td colspan="6" class="border-td" style="width: 50%;">
                            <b>LEGEND: </b>
                            <br>
                            <b>P - present </b> <b> A - absent</b> <b> C - cancelled</b> <b>N - no show </b> <b> D -
                                dropped</b>
                            <br>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <table>
                <thead style="font-size: xx-small;">
                    <tr>
                        <th class="border-td text-center" colspan="8" style="width: 50%;">
                            TRAINEES INFORMATION
                        </th>
                        <th class="border-td text-center" colspan="{{ count($dateRange) * 2 }}" style="width: 50%;">
                            DATE
                        </th>
                    </tr>
                    <tr>
                        <th class="border-td text-center" scope="rowgroup" rowspan="2">#</th>
                        <th class="border-td text-center" scope="colgroup" colspan="4">FULL NAME</th>
                        <th class="border-td text-center" scope="rowgroup" rowspan="2">
                            <b>BIRTHDATE</b>
                        </th>
                        <th class="border-td text-center" scope="rowgroup" rowspan="2">
                            <b>RANK</b>
                        </th>
                        <th class="border-td text-center" scope="rowgroup" rowspan="2">
                            <b>COMPANY</b>
                        </th>
                        @foreach ($dateRange as $date => $day)
                        <th style="font-size: 5pt;" colspan="2" scope="colgroup" class="text-center border-td">
                            <div>{{ $day }} <br>
                                <div style="font-size: 4pt;">
                                    ({{ $date }})
                                </div>
                            </div>
                        </th>
                        @endforeach

                    <tr>
                        <th class="border-td text-center" scope="col">First</th>
                        <th class="border-td text-center" scope="col">M.I</th>
                        <th class="border-td text-center" scope="col">Last</th>
                        <th class="border-td text-center" scope="col">Suffix</th>

                        @foreach ($dateRange as $date => $day)
                        <th class="border-td text-center" style="font-size: 5pt;" scope="col">AM</th>
                        <th class="border-td text-center" style="font-size: 5pt;" scope="col">PM</th>
                        @endforeach
                    </tr>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($att_trainees->forPage($page, $traineesPerPage) as $key => $trainee)
                    <tr style="font-size: 7.5pt;">
                        <td class="border-td" style="width: 2%;">{{ $key += 1 }}</td>
                        <td class="border-td text-center" style="width: 15%;">
                            <small class="text-inherit text-uppercase"> {{ $trainee->trainee->f_name }}</small>
                        </td>
                        <td class="border-td text-center" style="width: 1%;">
                            <small
                                class="text-inherit text-uppercase">{{ substr($trainee->trainee->m_name, 0, 1) }}</small>
                        </td>
                        <td class="border-td text-center" style="width: 15%;">
                            <small class="text-inherit text-uppercase"> {{ $trainee->trainee->l_name }}</small>
                        </td>
                        <td class="border-td text-center" style="width: 2%;">
                            <small>{{ $trainee->trainee->suffix }}</small>
                        </td>
                        <td class="border-td text-center" style="width: 10%;">
                            @if ($schedule->course->coursetypeid != 7)
                            <small>{{ date('F d, Y', strtotime($trainee->trainee->birthday)) }}</small>
                            @else
                            <small>N/A</small>
                            @endif
                        </td>
                        <td class="border-td text-center" style="width: 7%; font-size: 7pt;">
                            {{ $trainee->trainee->rank->rankacronym }}
                        </td>
                        <td class="border-td text-center" style="width: 15%; font-size: 7pt;">
                            {{ $trainee->trainee->company->company }}
                        </td>
                        @foreach ($dateRange as $date => $day)
                        <td class="border-td text-center" style="width: 3%;">
                            <div>
                                @if ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('absent_am', 1)->count() == 1)
                                <b class="text-center"> A </b>
                                @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('cancel_am', 1)->count() == 1)
                                <b class="text-center"> C </b>
                                @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('noshow_am', 1)->count() == 1)
                                <b class="text-center"> N </b>
                                @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('drop_am', 1)->count() == 1)
                                <b class="text-center">D </b>
                                <!-- AM -->
                                @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('present_am', 1)->count() == 1)
                                P
                                <!-- <img src="{{ public_path('assets/images/oesximg/check1.png') }}" alt="" width="10" height="auto"> -->
                                @else
                                <small>-</small>
                                @endif
                            </div>
                        </td>
                        <td class="border-td text-center" style="width: 3%;">
                            @if ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('absent_pm', 1)->count() == 1)
                            <b class="text-center"> A </b>
                            @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('cancel_pm', 1)->count() == 1)
                            <b class="text-center"> C </b>
                            @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('noshow_pm', 1)->count() == 1)
                            <b class="text-center"> N </b>
                            @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('drop_pm', 1)->count() == 1)
                            <b class="text-center">D </b>
                            <!-- pm -->
                            @elseif ($attendanceData->where('traineeid', $trainee->traineeid)->where('date', $date)->where('present_pm', 1)->count() == 1)
                            P
                            <!-- <img src="{{ public_path('assets/images/oesximg/check1.png') }}" alt="" width="10" height="auto"> -->
                            @else
                            <small>-</small>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    <td class="border-td fs-bold text-center" colspan="8">INSTRUCTOR'S SIGNATURE AM/PM</td>
                    @foreach($dateRange as $date => $day)
                    <td class="border-td" style="width: 3%;"></td>
                    <td class="border-td" style="width: 3%;"></td>
                    @endforeach
                    <!-- <td class="border-td" colspan="{{count($dateRange)*2}}"></td> -->
                </tbody>
            </table>
        </div>
        @endfor
</body>

</html>