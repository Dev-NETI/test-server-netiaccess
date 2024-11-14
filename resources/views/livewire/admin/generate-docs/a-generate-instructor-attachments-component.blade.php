<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <title>Instructor Attachment Summary - NETI (@php echo date('Y', strtotime(now('Asia/Manila'))); @endphp)</title>
    <style>
        /* Add your primary table styles here */
        .primary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .primary-table th, .primary-table td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        .primary-table th {
            background-color: #575757;
            color: white;
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        }

        .primary-table td {
            background-color: #e6e6e6;
            color: rgb(0, 0, 0);
            font-size: 10px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            text-align: center;
        }

        .capitalize-first {
            text-transform: capitalize;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="news">
            <table class="primary-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Rank</th>
                        <th>Status</th>
                        <th>Availability</th>
                        <th>COC</th>
                        <th>COP</th>
                        <th>IMO Licenses</th>
                        <th>SRIB Entries</th>
                        <th>Sea Service Card</th>
                        <th>Training Certificates</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $x = 1;
                    @endphp
                    @foreach ($data as $insdata)
                        @php
                            $name = ucwords(strtolower($insdata->l_name.", ".$insdata->f_name));
                        @endphp
                        <tr>
                            <td>@php echo $x; @endphp</td>
                            <td class="capitalize-first">@php echo $name; @endphp</td>
                            <td>{{$insdata->rankacronym}} - {{$insdata->rank}}</td>
                            @if ($insdata->regularid)
                                <td>Regular</td>
                            @else
                                <td>Guest</td>
                            @endif
                            @if ($insdata->is_Deleted)
                                <td>Inactive</td>
                            @else
                                <td>Active</td>
                            @endif

                            @php
                                // $attachmentdetails = getAttachment($insdata->userid, 1);
                                // if (!empty($attachmentdetails)) {
                                //     $COC = '<img src="storage/uploads/esign/check1.png" width="15px" height="15px" alt="">';
                                // }else{
                                //     $COC = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }

                                // $attachmentdetails = getAttachment($insdata->userid, 2);

                                // if ($attachmentdetails) {
                                //     $COP = '<img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">';
                                // }else{
                                //     $COP = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }

                                // $attachmentdetails = getAttachment($insdata->userid, 3);

                                // if ($attachmentdetails) {
                                //     $IMO = '<img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">';
                                // }else{
                                //     $IMO = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }

                                // $attachmentdetails = getAttachment($insdata->userid, 4);

                                // if ($attachmentdetails) {
                                //     $SRIB = '<img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">';
                                // }else{
                                //     $SRIB = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }

                                // $attachmentdetails = getAttachment($insdata->userid, 5);

                                // if ($attachmentdetails) {
                                //     $SSC = '<img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">';
                                // }else{
                                //     $SSC = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }

                                // $attachmentdetails = getAttachment($insdata->userid, 6);

                                // if ($attachmentdetails) {
                                //     $TC = '<img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">';
                                // }else{
                                //     $TC = '
                                //     <img src="storage/uploads/esign/wrong.png" width="10px" height="10px" alt="">
                                // ';
                                // }
                                
                            @endphp

                            {{-- <td>@php echo $COC; @endphp</td>
                            <td>@php echo $COP; @endphp</td>
                            <td>@php echo $IMO; @endphp</td>
                            <td>@php echo $SRIB; @endphp</td>
                            <td>@php echo $SSC; @endphp</td>
                            <td>@php echo $TC; @endphp</td> --}}
                        </tr>
                        @php
                            $x++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
