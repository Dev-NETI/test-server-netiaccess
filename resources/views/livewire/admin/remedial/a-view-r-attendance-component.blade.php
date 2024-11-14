<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remedial Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
            font-family: 'arial';
        }

        body {
            margin-bottom: 20px;
            font-size: x-small;
            /* background-image: url('assets/img/background.png'); */
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }

        table {
            font-size: x-small;
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
            height: 70px;
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
            /* Adjust the value as needed */
        }
    </style>
</head>

<body>
    <header>
        <div class="text-right" style="margin-top: -10px;">
            <!-- <small>F-NETI-023</small> -->
        </div>
        <div class="logo-container">
            <img src="{{ public_path('assets/images/oesximg/NETI.png') }}" alt="" width="270" height="auto">
            <br>
            <small style="line-height: 1.2;">Knowledge Avenue, Carmeltown, Canlubang, Calamba City 4037, Laguna Philippines <br>
                Tel. No. : (+63)2 8908 - 4900 / (+63)2 8554 - 3888 * Fax No. : (049) 508 - 8679 <br>
                *Online enrollment url : www.netiaccess.com <br>
                *website url: www.neti.com.ph * email : neti@neti.com.ph <br></small>
        </div>
    </header>

    <footer class="w-100">
        <table class="w-100">
            <tr>
                <td style="width:70%">
                    <small><i>This document is system generated.</i></small>
                </td>
                <td class="text-right" style="width:30%">
                    <small><i>Page <span class="pagenum"></span></i></small>
                </td>
            </tr>
        </table>
    </footer>

    <table style="margin-top: .9in;">
        <thead>
            <th class="text-center" colspan="10">REMEDIAL REPORT</th>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="border-td">
                    Full Name: {{$enrol->trainee->formal_name()}}
                </td>
                <td colspan="5" class="border-td">
                    Enrolled Course: {{$enrol->course->coursecode}} - {{$enrol->course->coursename}}
                </td>
            </tr>
            <tr>
                <th class="text-center" colspan="10">
                    TRAINING SCHEDULE
                </th>
            </tr>
            <tr>
                <td colspan="5" class="border-td">
                    Instructor:
                    @if ($enrol->schedule->instructor && $enrol->schedule->instructor->user)
                    {{ $enrol->schedule->instructor->user->formal_name() }}
                    @else
                    N/A
                    @endif
                </td>
                <td colspan="5" class="border-td">
                    Assessor <i>(For mandatory courses)</i>:
                    @if ($enrol->schedule->assessor && $enrol->schedule->assessor->user)
                    {{ $enrol->schedule->assessor->user->formal_name() }}
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="5" class="border-td">
                    Start date of training: {{date('d F, Y', strtotime($enrol->old_schedule->schedule->startdateformat))}}
                <td colspan="5" class="border-td">
                    End date of training: {{date('d F, Y', strtotime($enrol->old_schedule->schedule->enddateformat))}}
                </td>
            </tr>
            <tr>
                <th colspan="10" class="text-center">ATTENDANCE</th>
            </tr>
            <tr>
                <th colspan="2">No.</th>
                <th colspan="3">Date</th>
                <th colspan="2">Status</th>
                <th colspan="3">Created Date</th>
            </tr>
            @foreach ($attendance as $key => $att)
            <tr>
                <td class="border-td" colspan="2">{{++$key}}</td>
                <td class="border-td" colspan="3">{{ date('F j, Y', strtotime($att->date)) }}</td>
                @if ($att->day == 1)
                <td class="border-td" colspan="2">Present</td>
                @elseif ($att->status == 4)
                <td class="border-td" colspan="2">Absent</td>
                @elseif ($att->status == 5)
                <td class="border-td" colspan="2">Cancelled</td>
                @elseif ($att->status == 6)
                <td class="border-td" colspan="2">No Show</td>
                @endif
                <td class="border-td" colspan="3">
                    {{ date('F j, Y', strtotime($att->created_at)) }}
                </td>
            </tr>
            @endforeach
            <tr>
                <td colspan="8"></td>
                <td colspan="2" class="float-end">
                    COUNT OF PRESENT: {{$count_present}}
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>