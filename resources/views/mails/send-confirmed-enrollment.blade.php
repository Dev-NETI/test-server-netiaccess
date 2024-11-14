<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Approved</title>
    <style>
        /* Add your email styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Center the container vertically */
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #093b72;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body style="background-color: #f5f5f5; margin: 0; padding: 0; background-image: url('https://netiaccess.com/assets/images/oesximg/card.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="container" style="margin-top: 10px;">
        <div style="text-align: center;">
            <img src="https://netiaccess.com/assets/images/oesximg/logo.png" width="150" height="auto" alt="">
        </div>
        <h1>Your Enrollment Has Been Approved!</h1>
        <p>We are excited to welcome you to our training at NYK-FIL MARITIME E-TRAINING INC. You are now officially enrolled and ready to begin your journey with us.</p>
        <p>Here are some important details:</p>
        <ul>
            <li>Course name: {{$enrol->course->coursecode}} - {{$enrol->course->coursename}}</li>
            <li>Training Schedule: {{date('d F, Y', strtotime($enrol->schedule->startdateformat))}} - {{date('d F, Y', strtotime($enrol->schedule->enddateformat))}}</li>
            @if ($enrol->schedule->dateonlinefrom != null && $enrol->schedule->dateonlinefrom != '0000-00-00')
            <li>Online Date: {{date('d F, Y', strtotime($enrol->schedule->dateonlinefrom))}} - {{date('d F, Y', strtotime($enrol->schedule->dateonlineto))}}</A> </li>
            @endif

            @if ($enrol->schedule->dateonsitefrom != null && $enrol->schedule->dateonsitefrom != '0000-00-00')
            <li>Onsite Date: {{date('d F, Y', strtotime($enrol->schedule->dateonsitefrom))}} - {{date('d F, Y', strtotime($enrol->schedule->dateonsiteto))}}</li>
            @endif

            <li>Location: {{optional($enrol->schedule->course->location)->courselocation}}</li>
        </ul>

        @if ($enrol->schedule->dateonlinefrom != null && $enrol->schedule->dateonlinefrom != '0000-00-00')
        <p>Zoom link (applicable for online training only): <a href="{{$enrol->schedule->course->meetingcredentials}}" target="_blank">{{$enrol->schedule->course->meetingcredentials}}</a></p>
        @endif
        <p>If you have any questions or need further information, please don't hesitate to contact our support team.</p>
        <p>Thank you for choosing NYK-FIL MARITIME E-TRAINING INC. We look forward to helping you succeed!</p>
        <p>Best regards,</p>
        <p>NETI</p>
        <p>
            <a class="btn btn-secondary" href="https://netiaccess.com/" target="_blank"> <span style="color: white; text-decoration: none;">Visit Our Website</span> </a>
        </p>
    </div>
</body>

</html>