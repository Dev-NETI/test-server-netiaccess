<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMISSION SLIP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        * {
            font-family: Tahoma, "Trebuchet MS", sans-serif;
            font-weight: bold;
            line-height: 1;
            font-size: 10px;
            /* font-family: 'Arial', arial, monospace; */
        }

        #headertitle{
            margin-top: 5.5em;
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

        tbody #ins td{
            border: solid black 1px;
        }

        .indent {
            padding-left: 20px;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body>
    <div class="row" id="headertitle">
        <div class="col-lg-12 text-center">
            NYK-FIL MARITIME E-TRAINING, INC
            <br>
            LIST OF APPROVED LECTURERS
            <br>
            as of {{ $datenow }}
        </div>
    </div>

    <table class="mt-3">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Name</th>
                <th>Rank Onboard</th>
                <th>Highest License</th>
                <th>Course Assignment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instructor as $instructordata)
                <tr id="ins">
                    <td>{{ $counter }}</td>
                    <td>{{ $instructordata->license }}</td>
                    <td>{{ $instructordata->license }}</td>
                    <td>{{ $instructordata->license }}</td>
                    <td></td>
                </tr>
                {{$counter++}}
            @endforeach
        </tbody>
    </table>

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

</body>

</html>
