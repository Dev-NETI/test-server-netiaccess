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

        <p>Hi {{$name}},</p>

        <p>You are receiving this email because we received a password reset request for your account.</p>

        <br>
        <br>

        <div style="text-align: center;">
            <a href="{{ route('t.confirm-password', ['token' => $token]) }}" style="display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; border-radius: 20px;">
                Reset Password
            </a>
        </div>

        <br>
        <br>

        <p>This password reset link will expire in 60 minutes.</p>

        <p>If you did not request a password reset, no further action is required.</p>

        <p>Thanks,<br>
            The OESX Team</p>
    </div>
</body>

</html>