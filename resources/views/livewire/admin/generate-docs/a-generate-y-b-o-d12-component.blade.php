<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Y-BOD-12</title>
    <style>
        .header-title {
            text-align: center;
            margin-top: 17px;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 2px;
        }

        .text-center {
            text-align: center;
        }

        .bg-yellow {
            background-color: rgb(255, 255, 0);
        }

        th {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
        }

        td {
            font-family: 'Century-Gothic', 'Gothic', sans-serif;
            font-size: 10px;
        }

        .strong {
            font-weight: bold;
        }

        td,
        th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid black;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .ybodheader {
            position: absolute;
            left: 640px;
            top: 10px;
            z-index: -1;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .footersign {
            margin-top: 4em;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-weight: lighter;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="ybodheader">
        <strong>Y-BOD-012</strong>
    </div>
    <div class="header-title">
        <h5>NYK-FIL MARITIME E-TRAINING INC.</h5>
        <h5>LIST OF APPROVED LECTURERS</h5>
        <h5>as of {{date('Y F d', strtotime($from)). ' - ' .date('Y F d', strtotime($to))}}</h5>
    </div>

    <div class="mt-10">
        <table style="">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NAME</th>
                    <th>RANK ONBOARD</th>
                    <th>HIGHEST LICENSE</th>
                    <th>COURSE ASSIGNMENT</th>
                    {{-- <th>CONTRACT EXPIRATION</th> --}}
                </tr>
            </thead>
            <tbody>
                @php
                $lastDepartment = null;
                $index = 1;
                $allcount = $data->count();
                @endphp

                @foreach ($data as $lecturer)
                @if ($lastDepartment !== $lecturer['coursedepartment'])
                <tr>
                    <td class="strong text-center" colspan="5">{{ $lecturer['coursedepartment'] }}</td>
                </tr>
                @php
                $lastDepartment = $lecturer['coursedepartment'];
                @endphp
                @endif

                <tr>
                    <td>{{ $index }}</td>
                    <td style="min-width: 10em;">{{ strtoupper($lecturer['f_name']) }} {{
                        strtoupper($lecturer['l_name']) }}</td>
                    <td>{{ $lecturer['rank'] }}</td>
                    <td>{{ $lecturer['license'] }}</td>
                    <td>{{ $lecturer['coursecode'] }}</td>
                </tr>

                @php
                $index++;
                @endphp
                @endforeach
            </tbody>
        </table>

        @if ($index > $allcount)
        <div style="display: inline-block;" class="footersign">
            <div style="display: inline-block; margin-right: 40px;">
                <label for="">Prepared By:</label>
                <br><br>
                <p style="margin-top: 30px;">MAMartinez</p>
            </div>
            <div style="display: inline-block; margin-right: 40px;">
                <label for="">Reviewed By:</label>
                <br><br>
                <p style="margin-top: 30px;">SAMACATANGAY/NDAGUILAR</p>
            </div>
            <div style="display: inline-block; margin-right: 40px;">
                <label for="">Noted/Approved By:</label>
                <br><br>
                <p style="margin-top: 30px;">EZClemente, JR.</p>
            </div>
        </div>
        @endif
    </div>
</body>

</html>