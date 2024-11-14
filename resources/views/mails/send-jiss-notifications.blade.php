<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
        }

        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #080032;
            color: #ffffff;
        }

        .content {
            padding: 20px;
            color: #333333;
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            background-color: #f4f4f4;
            color: #777777;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>NYK-Fil E-Maritime Training Inc. - NETIOEX</h1>
        </div>
        <div class="content">
            <h2>Hi!</h2>
            <p>Please check the billing statement below.</p>
            <p>{{$content}}</p>
        </div>
        <div class="footer">
            <p>If you have concern please email inquiry@neti.com.ph</p>
            <p>This is system generated email. Please do not reply.</p>
        </div>
    </div>
</body>

</html>