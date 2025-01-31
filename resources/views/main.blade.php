<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kerkalender</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

    <script src="https://kit.fontawesome.com/4e2504afbf.js" crossorigin="anonymous"></script>
</head>
<style>
    * {
        font-family: 'Montserrat', sans-serif;
    }
    .cc {
        width: 80%;
        margin-right: auto;
        margin-left: auto;
    }
    .nav-link {
        text-decoration: none;
        position: relative;
        color: black;
        font-weight: 200;

        &::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: currentColor;
            transform: scaleX(1);
            transform-origin: left;
            transition: transform 0.3s ease-in-out;
        }

        &:hover::after {
            transform: scaleX(0);
            transform-origin: right;
        }

        &:not(:hover)::after {
            transform: scaleX(1);
            transform-origin: left;
        }
    }
</style>
<body>
<nav class="bg-white px-5 py-3 mx-5 rounded-3xl absolute top-2 left-0 right-0 z-10">
    <div class="flex justify-between">
        <div class="px-5 py-3 bg-[#e0e0e0] rounded-full cursor-pointer">
            <i class="fa-solid fa-house"></i> Home
        </div>
        <div class="flex space-x-5 my-auto mr-5">
            <div class="cursor-pointer nav-link">Login</div>
            <div class="cursor-pointer nav-link">Profile</div>
        </div>
    </div>
</nav>
@yield('content')
</body>
</html>
