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
            /* background-color: lightgray; */
        }

        td {
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
                    WEEK COVERED: {{$week->batchno}} <br>
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
            <thead style="font-size: xx-small;">
                <tr>
                    <th class="text-center" scope="rowgroup" rowspan="4" scope="colgroup" colspan="2" style="width: 25%;">COURSE</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 2% !important; ">TRAINING DAYS</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 2% !important;">MIN</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 2% !important;">MAX</th>
                    <th colspan="7" scope="colgroup" class="text-center border-td" style="width: 5%;">{{$week->batchno}}</th>
                    <th class="text-center" scope="rowgroup" rowspan="3" scope="colgroup" colspan="2" class="text-center" style="width: 10%;">INSTRUCTORS</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 5%;">VALIDTY</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 10%;">ASSESSOR</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 5%;">VALIDTY</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 10%;">ROOM NO.</th>
                    <th class="text-center" scope="rowgroup" rowspan="4" style="width: 5%;">NO. OF TRAINEES</th>
                </tr>
                <tr>
                    <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col">SU</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col">M</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col">T</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col">W</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col">TH</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col">F</th>
                    <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col">SA</th>
                </tr>
                <tr>
                    <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col">{{$previousSundayFormatted}}</th>
                    @foreach ($date_array as $key => $date )
                    @if ($key == 5)
                    <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col">{{$date}}</th>
                    @else
                    <th class="text-center" style="font-size: 7pt;" scope="col">{{$date}}</th>
                    @endif
                    @endforeach
                    <!-- <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col"></th>
                    <th class="text-center" style="font-size: 7pt; color: white; background-color: red;" scope="col"></th> -->

                </tr>
                <tr>
                    <th class="text-center" style="font-size: 7pt;" scope="col" colspan="7"></th>
                    <th class="text-center" style="font-size: 7pt;" scope="col" style="width: 10%;">1</th>
                    <th class="text-center" style="font-size: 7pt;" scope="col" style="width: 10%;">2</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($training_schedules_type as $index => $training_schedules)
                <tr>
                    <th class="text-center text-uppercase" colspan="19">{{$course_type->where('coursetypeid', $index)->first()->coursetype}}</th>
                </tr>

                @foreach ($training_schedules as $key => $training_schedule)
                <tr>
                    <td class="border-td" style="width: 5%; font-style: bold;">{{$training_schedule->course->coursecode}}</td>
                    <td class="border-td" style="width: 20%; font-style: bold;">{{$training_schedule->course->coursename}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->trainingdays}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->minimumtrainees}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->maximumtrainees}}</td>
                    <td class="border-td text-center" style="font-size: 7pt; color: black;"> </td>


                    @foreach ($show_sched[$index][$key] as $sched)
                    @if ($sched == 'P')
                        <td class="border-td text-center" style="font-size: 7pt; color: black; font-style: bold; background-color: yellow;" scope="col">{{$sched}}</td>
                    @elseif ($sched == 'O')
                        <td class="border-td text-center" style="font-size: 7pt; color: white; font-style: bold; background-color: blue;" scope="col">{{$sched}}</td>
                    @else
                        <td class="border-td text-center" style="font-size: 7pt;" scope="col"></td>
                    @endif
                    @endforeach

                    @if ($index == 1)

                    @if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="2">{{$training_schedule->instructor->rank->rankacronym}} {{$training_schedule->instructor->user->formal_name()}}</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="2">TBA</td>
                    @endif

                    @if ($training_schedule->ins_license)
                    <td class="border-td text-center"> {{ date('d - m - Y', strtotime($training_schedule->ins_license->expirationdate)) }}</td>
                    @else
                    <td class="border-td text-center"></td>
                    @endif

                    @if ($training_schedule->assessor->user && $training_schedule->assessor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;">{{$training_schedule->assessor->rank->rankacronym}} {{$training_schedule->assessor->user->formal_name()}}</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;">TBA</td>
                    @endif

                    @if ($training_schedule->asses_license)
                    <td class="border-td text-center">{{ date('d - m - Y', strtotime($training_schedule->asses_license->expirationdate)) }}</td>
                    @else
                    <td class="border-td text-center"></td>
                    @endif

                    @else

                    @if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="5">{{$training_schedule->instructor->rank->rankacronym}} {{$training_schedule->instructor->user->formal_name()}}</td>
                    @elseif ($training_schedule->enrolled_pending_count == 0)
                    <td class="border-td text-center" style="width: 5%; font-style: bold; color:white; background-color: red;" colspan="5">TRAINING DISSOLVED; NO ENROLLES</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="5">TBA</td>
                    @endif

                    @endif

                    <td class="border-td text-center" style="width: 10%;">{{$training_schedule->course->mode->modeofdelivery}} / {{ substr($training_schedule->room->room, 0, 3) }}
                    <td class="border-td text-center" style="width: 5%;">{{$training_schedule->enrolled_pending_count}}</td>
                </tr>
                @endforeach
                @endforeach
                <tr>
                    <th class="text-center text-uppercase" colspan="19">SPECIAL SCHEDULE</th>
                </tr>

                @if ($special_schedules->count() != 0)
                @foreach ($special_schedules as $key => $training_schedule)
                <tr>
                    <td class="border-td" style="width: 5%; font-style: bold;">{{$training_schedule->course->coursecode}}</td>
                    <td class="border-td" style="width: 20%; font-style: bold;">{{$training_schedule->course->coursename}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->trainingdays}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->minimumtrainees}}</td>
                    <td class="border-td text-center" style="width: 2% !important; font-style: bold;">{{$training_schedule->course->maximumtrainees}}</td>
                    <td class="border-td text-center" style="font-size: 7pt; color: black;"> </td>


                    @foreach ($s_show_sched[$key] as $sched)
                    @if ($sched == 'P')
                        <td class="border-td text-center" style="font-size: 7pt; color: black; font-style: bold; background-color: yellow;" scope="col">{{$sched}}</td>
                    @elseif ($sched == 'O')
                        <td class="border-td text-center" style="font-size: 7pt; color: white; font-style: bold; background-color: blue;" scope="col">{{$sched}}</td>
                    @else
                        <td class="border-td text-center" style="font-size: 7pt;" scope="col"></td>
                    @endif
                    @endforeach

                    @if ($index == 1)

                    @if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="2">{{$training_schedule->instructor->rank->rankacronym}} {{$training_schedule->instructor->user->formal_name()}}</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="2">TBA</td>
                    @endif

                    @if ($training_schedule->ins_license)
                    <td class="border-td text-center"> {{ date('d - m - Y', strtotime($training_schedule->ins_license->expirationdate)) }}</td>
                    @else
                    <td class="border-td text-center"></td>
                    @endif

                    @if ($training_schedule->assessor->user && $training_schedule->assessor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;">{{$training_schedule->assessor->rank->rankacronym}} {{$training_schedule->assessor->user->formal_name()}}</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;">TBA</td>
                    @endif

                    @if ($training_schedule->asses_license)
                    <td class="border-td text-center">{{ date('d - m - Y', strtotime($training_schedule->asses_license->expirationdate)) }}</td>
                    @else
                    <td class="border-td text-center"></td>
                    @endif

                    @else

                    @if ($training_schedule->instructor->user && $training_schedule->instructor->rank->rankid != 449)
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="5">{{$training_schedule->instructor->rank->rankacronym}} {{$training_schedule->instructor->user->formal_name()}}</td>
                    @elseif ($training_schedule->enrolled_pending_count == 0)
                    <td class="border-td text-center" style="width: 5%; font-style: bold; color:white; background-color: red;" colspan="5">TRAINING DISSOLVED; NO ENROLLES</td>
                    @else
                    <td class="border-td text-center" style="width: 5%; font-style: bold;" colspan="5">TBA</td>
                    @endif

                    @endif

                    <td class="border-td text-center" style="width: 10%;">{{$training_schedule->course->mode->modeofdelivery}} / {{ substr($training_schedule->room->room, 0, 3) }}
                    <td class="border-td text-center" style="width: 5%;">{{$training_schedule->enrolled_pending_count}}</td>
                </tr>
                @endforeach

                @else
                <tr>
                    <td class="border-td text-center" style="font-style: italic;" colspan="19">NO SPECIAL SCHEDULE</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>
</body>

</html>