<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h4>Hi! {{ $emailuser }},</h4>
    <p>I hope this message finds you well. We wanted to bring to your attention that an attachment of {{ Str::upper($name) }} {{ $months }}.
    <strong>
        <br><br>ATTACHMENT DETAILS: <br>
        Name: {{ $attachment }} <br>
        Type: {{ $attachmenttype }} <br>
        Expiration Date: {{ date('Y F d', strtotime($date)) }}
    </strong>
    <br><br>
    We encourage you to act promptly to prevent any inconvenience caused by the attachment's expiration. Thank you for your attention to this matter
    <br>
    This is an automated message. PLEASE DO NOT REPLY.</p>
</body>
</html>
