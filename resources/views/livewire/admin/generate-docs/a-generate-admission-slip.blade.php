<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMISSION SLIP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            font-family: 'Times New Roman', serif;
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
            <small>F-NETI-023</small>
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

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="text-align: center; font-weight: bold; margin-top:100px; font-size:medium;">
                    GUIDELINES FOR ON-SITE <br> TRAINING
                </div>
                <div style="font-size:small; margin-top:40px;">
                    <b>TRAINEES INFORMATION</b> <br>
                    <table style="width: 100%; font-size: small;">
                        <tr>
                            <td style="width: 50%; text-transform:uppercase;">
                                <b>NAME:</b> {{$enrol->trainee->formal_name()}} <br>
                                <b>RANK:</b> {{$enrol->trainee->rank->rank}} <br>
                                <b>CONTACT NO:</b> {{$enrol->trainee->contact_num}} <br>
                                <b>COMPANY:</b>{{$enrol->trainee->company->company}}
                            </td>
                            <td style="width: 50%;">
                                <div style="text-align: center;">
                                    @if ($enrol->trainee->imagepath)
                                    <img src="{{storage_path('app/public/traineepic/'. $enrol->trainee->imagepath)}}" width="75" alt="avatar">
                                    @else
                                    <img src="{{asset('assets/images/avatar/avatar.jpg')}}" width="100" alt="avatar">
                                    @endif
                                    <!-- <img src="{{ public_path('assets/images/avatar/avatar.jpg') }}" alt="" width="100" height="auto"> -->
                                    <p>{{ $enrol->enroledid }}</p>

                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG(strval($enrol->enroledid), 'C128') }}" alt="Barcode" style="display: block; ">
                                </div>

                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </table>
                    <table class="border-td" style="width: 90%; font-size: xx-small;">
                        <tr>
                            <th colspan="4">
                                Admission Slip #: {{$enrol->enroledid}}
                            </th>
                        </tr>
                        <tr>
                            <th style="width: 20%;">CODE</th>
                            <th style="width: 60%;">
                                <b>COURSES ENROLLED / TRAINING DATE </b>
                            </th>
                            <th style="width: 20%;"> TYPE </th>
                            <th>LOCATION</th>
                        </tr>
                        <tbody>
                            <tr style="text-align: center;">
                                <td class="border-td" style="width: 20%;"> {{$enrol->course->coursecode}}</td>
                                <td class="border-td" style="width: 50%;">
                                    {{$enrol->course->coursename}}
                                </td>
                                <td class="border-td" style="width: 20%;">{{$enrol->course->mode->modeofdelivery}}</td>
                                <td class="border-td" style="width: 10%;">{{$enrol->course->location->courselocation}}</td>
                            </tr>
                            <th colspan="4" style="text-align: center;">TRAINING SCHEDULE</th>
                            <tr style="text-align: center;">
                                <td colspan="4" class="border-td">
                                    Days of Online:
                                    @if ($enrol->schedule->dateonlinefrom)
                                    @if ($enrol->schedule->dateonlinefrom === $enrol->schedule->dateonlineto)
                                    <i>{{$enrol->schedule->dateonlinefrom}}</i>
                                    @else
                                    <i>{{$enrol->schedule->dateonlinefrom}} - {{$enrol->schedule->dateonlineto}}</i>
                                    @endif
                                    @else
                                    <i> --no schedule for online--</i>
                                    @endif
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td colspan="4" class="border-td">
                                    Days of Onsite:
                                    @if ($enrol->schedule->dateonsitefrom)
                                    @if ($enrol->schedule->dateonsitefrom === $enrol->schedule->dateonsiteto)
                                    <i>{{$enrol->schedule->dateonsitefrom}}</i>
                                    @else
                                    <i>{{$enrol->schedule->dateonsitefrom}} - {{$enrol->schedule->dateonsiteto}}</i>
                                    @endif
                                    @else
                                    <i> --no schedule for onsite--</i>
                                    @endif
                                </td>
                            </tr>
                            <th colspan="4" style="text-align: center;">
                                INCLUDE
                            </th>
                            <tr style="text-align: center;">
                                <td colspan="4">
                                    @if($enrol->course->courseid === 113)
                                    NO RECORD FOUND
                                    @elseif ($enrol->meal_price && $enrol->dorm_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price && $enrol->dorm_price)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->dorm_price && $enrol->busid)
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->busid && $enrol->schedule->dateonsitefrom)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    <img src="{{ public_path('assets/images/oesximg/bus.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->dorm_price)
                                    <img src="{{ public_path('assets/images/oesximg/dorm.png') }}" alt="" width="50" height="auto">
                                    @elseif ($enrol->meal_price)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    @elseif($enrol->schedule->dateonsitefrom)
                                    <img src="{{ public_path('assets/images/oesximg/meal.png') }}" alt="" width="50" height="auto">
                                    @else
                                    NO RECORD FOUND
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
            <td style="width: 50%;">
                <div style="margin-top: 80px; padding: 10px; border: 1px solid black;">
                    <div style="text-align: justify; font-size:xx-small;">
                        <p><b>IMPORTANT REMINDERS:</b>
                            <br>1. Our training hours are from 0800H to 1700H. <br>
                            <br>2. Please observe the proper dress code (i.e., polo shirt, dark slacks, black leather shoes). Wearing maong pants and rubber shoes is strictly prohibited. <br>
                            <br>3. Discipline, good manners, and right conduct should be observed at all times. Littering, loitering, sleeping during class, smoking outside of the designated area, and displaying a lack of self-control due to the influence of alcohol are grounds for termination of training. <br>
                            <br>4. If you wish to use the shuttle service, please arrive on time at NYK-FIL Intramuros; the bus departs at exactly 0600H every Monday through Friday. <br>
                            <br>5. We have a 'No admission slip - no boarding of the bus’ policy. For those who will go directly to NETI, you are also required to present a digital copy of your admission slip at the main gate.<br>
                            <br>6. Upon arrival at the training center, please proceed to the FDC 1st Floor for the NETI Safety Briefing and Orientation.<br>
                            <br>7. 'No photo, no issuance of training certificate' is strictly observed to avoid delay. For your convenience, you may upload your photos to your NETI Online Enrollment System (OES) account or have them taken on-site at our photo center.<br>
                            <br>8. Enrollment cancellations must be made through the OES at least seven (7) working days before the start of your training. If you cannot cancel online due to unavoidable circumstances, please contact the Registrar at (02) 8-908-4900 or (02) 8-554-3888.<br>
                            <br>9. For walk-in trainees, payment should be settled in full at least one day before the start of training. Please upload your proof of payment to your NETI OES account.<br>
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>