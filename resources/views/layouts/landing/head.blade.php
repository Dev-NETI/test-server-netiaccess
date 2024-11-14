<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{env('SECURE_HEADER_DESCRIPTION')}}">
<meta name="keywords" content="{{env('SECURE_HEADER_KEYWORDS')}}">
<meta name="author" content="NETI">
<link rel="icon" type="image/x-icon" href="{{asset('assets\images\favicon\net-faviicon.ico')}}">
<script>
    if (localStorage.theme) document.documentElement.setAttribute("data-theme", localStorage.theme);
</script>

{{--
<link rel="preload" as="image" href="assets/videos/landing_page_poster-dark.jpg"> --}}


<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif !important;
    }

    .animated-text {
        opacity: 0;
        transform: translateX(-20px);
        animation: revealText 1.5s forwards ease-out
    }

    @keyframes revealText {
        to {
            opacity: 1;
            transform: translateX(0)
        }
    }

    .card-hover {
        position: relative;
        overflow: hidden
    }

    .card-hover::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: transform 0.3s ease;
        z-index: -1
    }

    .card-hover:hover::before {
        transform: scale(1.1)
    }

    .gradient-background1 {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: linear-gradient(to left, transparent, rgba(0, 0, 128, 0.5));
        z-index: -1;
        pointer-events: none
    }

    .py-md-20::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: linear-gradient(to left, transparent, rgba(0, 0, 128, 0.5));
        z-index: -1;
        pointer-events: none
    }

    .contact-section {
        position: relative;
        background-image:url('{{asset(' assets/images/oesximg/landing-4-bg.jpg')}}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5)
    }

    .contact-content {
        position: relative;
        z-index: 2;
        color: white
    }

    .loader {
        animation: rotate 1s infinite;
        height: 50px;
        width: 50px;
    }

    .loader:before,
    .loader:after {
        border-radius: 50%;
        content: '';
        display: block;
        height: 20px;
        width: 20px;
    }

    .loader:before {
        animation: ball1 1s infinite;
        background-color: #cb2025;
        box-shadow: 30px 0 0 #f8b334;
        margin-bottom: 10px;
    }

    .loader:after {
        animation: ball2 1s infinite;
        background-color: #00a096;
        box-shadow: 30px 0 0 #97bf0d;
    }

    @keyframes rotate {
        0% {
            -webkit-transform: rotate(0deg) scale(0.8);
            -moz-transform: rotate(0deg) scale(0.8);
        }

        50% {
            -webkit-transform: rotate(360deg) scale(1.2);
            -moz-transform: rotate(360deg) scale(1.2);
        }

        100% {
            -webkit-transform: rotate(720deg) scale(0.8);
            -moz-transform: rotate(720deg) scale(0.8);
        }
    }

    @keyframes ball1 {
        0% {
            box-shadow: 30px 0 0 #f8b334;
        }

        50% {
            box-shadow: 0 0 0 #f8b334;
            margin-bottom: 0;
            -webkit-transform: translate(15px, 15px);
            -moz-transform: translate(15px, 15px);
        }

        100% {
            box-shadow: 30px 0 0 #f8b334;
            margin-bottom: 10px;
        }
    }

    @keyframes ball2 {
        0% {
            box-shadow: 30px 0 0 #97bf0d;
        }

        50% {
            box-shadow: 0 0 0 #97bf0d;
            margin-top: -20px;
            -webkit-transform: translate(15px, 15px);
            -moz-transform: translate(15px, 15px);
        }

        100% {
            box-shadow: 30px 0 0 #97bf0d;
            margin-top: 0;
        }
    }
</style>

<title>NETI: Online Enrollment X</title>
@livewireStyles()