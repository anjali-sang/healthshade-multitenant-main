<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Healthshade - Inventory Management Software</title>
    <link rel="icon" href="{{ url('icon.PNG') }}" type="image/x-icon">
    <link href="/css/app.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />


    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <style>
        :root {
            --font-main: "Inter", sans-serif;
            --font-heading: "Lexend", sans-serif;
            --color-primary: #009cf6;
            --color-secondary: #00ccff;
            --color-text: #334155;
            --color-white: #ffffff;
            --border-radius: 20px;
            --padding-section: 200px;
            --font-size-main-heading: 72px;
            --font-size-sub-heading: 25px;
            --font-size-details-heading: 48px;
            --font-size-details-sub-heading: 20px;
            --font-size-menu: 16px;
            --font-size-trial-button: 14px;
            --transition-duration: 0.5s;
        }

        body {
            margin: 0;
            font-family: var(--font-main);
        }



        /* details section CSS starts   */

        .details-section {
            background: linear-gradient(to bottom right, var(--color-primary), var(--color-secondary));
            padding-left: var(--padding-section);
            padding-right: var(--padding-section);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 100px;
        }

        .details-heading {
            margin-top: 85px;
            font-size: var(--font-size-details-heading);
            color: var(--color-white);
            text-align: center;
            font-weight: 600;
            font-family: var(--font-heading);

        }

        .leftToRightAnimation {
            animation: leftToRight 1s linear;
            animation-timeline: view();
            animation-range: entry 0% cover 40%;
        }

        .rightToLeftAnimation {
            animation: rightToLeft 1s linear;
            animation-timeline: view();
            animation-range: entry 0% cover 40%;
        }



        @keyframes leftToRight {
            0% {
                transform: translateX(-150px);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes rightToLeft {
            0% {
                transform: translateX(150px);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .incSizeAnimation {
            animation: incSize 1s linear;
            animation-timeline: view();
            animation-range: entry 0% cover 40%;
        }

        @keyframes incSize {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .details-sub-heading {
            margin-top: 15px;
            font-size: var(--font-size-details-sub-heading);
            color: var(--color-white);
            text-align: center;
        }

        @media (max-width: 820px) {
            .details-section {
                padding-left: 15px;
                padding-right: 15px;
                padding-bottom: 74px;
            }

            .details-heading {
                padding-left: 15px;
                padding-right: 15px;
                margin-top: 35px;
                font-size: 35px;
                font-weight: 400;
            }

            .details-sub-heading {
                margin-top: 10px;
                font-size: 16px;
                color: var(--color-white);
                text-align: center;
            }
        }

        .features {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 60px;
            gap: 25px;
        }

        .feature {
            border: 1px solid rgba(255, 255, 255, 0.398);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            box-shadow: inset 0 0 2000px rgba(255, 255, 255, .5);
            color: var(--color-white);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        .feature:hover {
            transform: translateZ(15px) scale(1.05);
            box-shadow: inset 0 0 2000px rgba(255, 255, 255, .7);
        }

        .feature h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--color-white);
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.398);
        }

        .feature p {
            font-size: 14px;
            margin-bottom: 10px;
            color: var(--color-white);
        }

        @media (max-width: 820px) {
            .features {
                padding: 5px 15px;
                flex-direction: column;
            }

            .feature h1 {
                font-size: 18px;
                margin-bottom: 8px;
                padding-bottom: 6px;
            }

        }

        .integrations {
            padding: 150px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: var(--color-white);
        }

        .integrations-message {
            font-size: 16px;
            font-family: var(--font-heading);
            padding-top: 100px;
            padding-bottom: 30px;
        }

        .suppliers {
            display: flex;
            justify-content: center;
            align-items: center;
            column-gap: 85px;
        }

        .suppliers>img {
            height: 100px;
            aspect-ratio: 3 / 2;
            object-fit: contain;
            /* mix-blend-mode: color-burn; */
            transition: transform 0.3s ease-in-out;
        }

        .suppliers>img:hover {
            transform: scale(1.05);
        }

        .integrations-heading,
        {
        font-size: 40px;
        font-family: var(--font-heading);
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .integrations-sub-text,
        {
        padding: 15px 100px;
        font-size: 18px;
        text-align: center;
        }


        .integration-features {
            margin-top: 60px;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: stretch;
        }

        .integration-features .heading {
            font-size: 14px;
            padding: 10px 0px;
        }

        .integration-features .summary {
            font-size: 14px;
            padding: 10px 0px;
            text-align: justify;
        }

        .integration-features .tag-line {
            font-size: 22px;
            padding: 10px 0px;
        }

        .integration-feature {
            flex: 1;
            padding: 20px;
        }

        .integration-features .material-symbols-outlined {
            font-size: 48px;
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 48
        }

        @media (max-width: 820px) {
            .integrations {
                padding: 45px 15px;
            }

            .integrations-message {
                font-size: 14px;
                font-family: var(--font-heading);
                padding-top: 50px;
                padding-bottom: 30px;
            }

            .integrations-heading {
                font-size: 32px;
                text-align: center;
            }

            .integrations-sub-text {
                padding: 10px 5px;
                font-size: 14px;
                text-align: center;
            }

            .integration-features {
                margin-top: 25px;
                padding: 10px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: stretch;
            }

            .integration-feature {
                text-align: center;
            }

            .suppliers {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                column-gap: 25px;
            }

            .suppliers>img {
                height: 80px;
                aspect-ratio: 3 / 2;
                object-fit: contain;
                /* mix-blend-mode: color-burn; */
                transition: transform 0.3s ease-in-out;
            }

        }


        .integrations-features {
            padding-top: 85px;
        }



        .integrations-sub-text {
            padding: 15px 100px;
            font-size: 18px;
            text-align: center;
        }


        .faq-section {
            margin-top: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #scrollToTopBtn {
            position: fixed;
            bottom: 80px;
            right: 0px;
            display: none;
            background-color: #737b83;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            z-index: 100;
        }

        .scrollToTop:hover {
            background-color: var(--color-primary);
        }

        .talk-to-sales {
            height: 0;
            position: fixed;
            z-index: 100;
            background-color: #ffffff;
            width: 100%;
            transition: height 0.5s ease;
            overflow: hidden;
        }

        /* Hover effect */
        /* .talk-to-sales{
            height: 100vh;
        } */

        .close-sales {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            cursor: pointer;
            display: none;
        }

        /* ///////////// */
        .sales-body {
            width: 100%;
            height: 100%;
            padding: 100px;
            background: linear-gradient(to bottom right, white, rgb(255, 255, 255));
        }

        @media screen and (max-width: 900px) {
            .wrapper .carousel {
                grid-auto-columns: calc((100% / 2) - 9px);

            }
        }

        @media screen and (max-width: 820px) {
            .wrapper .carousel {
                grid-auto-columns: 100%;
            }

            .change-slide>i {
                display: none;
            }
        }

        .about-heading .about-title {
            font-family: var(--font-heading);
            font-size: 40px;
            font-weight: 700;
            line-height: 1.2;
        }

        .about-underline {
            margin-top: 10px;
            width: 70%;
            border: 2px solid var(--color-primary);
        }

        .about-heading .about-text {
            font-family: var(--font-main);
            font-size: 20px;
            line-height: 1.2;
        }


        footer {
            width: 100%;
            height: 90px;
            background: #2b2b2d;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
    </style>
    <style>
        /* Inspired by: https://codepen.io/webstoryboy/pen/rrLdQX */
        :root {
            --loader-text-color: #ffffff;
            --loader-dot-color: #A51FF6;
            --loader-bg: #009cf6;
        }

        html {
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            line-height: 1.5;
        }

        .loader-container {
            position: fixed;
            inset: 0;
            z-index: 999;
            background-color: var(--loader-bg);
            display: grid;
            place-content: center;
            height: 100vh;
            transition: opacity .4s ease-in-out, visibility .4s ease-in-out;
        }

        svg {
            width: 100vw;
            height: auto;
            max-width: 1200px;
        }

        svg text {
            font-size: 15vw;
            /* Responsive font size */
            font-weight: 900;
            letter-spacing: -0.5vw;
        }

        .text-body {
            stroke: var(--loader-text-color);
            animation: animate-stroke 5s forwards;
        }

        .dot {
            stroke: var(--loader-text-color);
            animation: dot-stroke 5s forwards;
        }

        @keyframes animate-stroke {
            0% {
                stroke-dashoffset: 30%;
                stroke-dasharray: 0 40%;
                fill: transparent;
                stroke-width: 4;
            }

            50% {
                stroke-width: 4;
                fill: transparent;
            }

            80% {
                stroke-dashoffset: -30%;
                stroke-dasharray: 40% 0;
                fill: var(--loader-text-color);
            }

            100% {
                stroke-dashoffset: -30%;
                stroke-dasharray: 40% 0;
                fill: var(--loader-text-color);
            }
        }

        @keyframes dot-stroke {

            0%,
            50%,
            80% {
                opacity: 0;
            }

            100% {
                opacity: 1;
                fill: var(--loader-text-color);
            }
        }

        /* Media Queries for better responsiveness */
        @media (min-width: 1200px) {
            svg text {
                font-size: 180px;
                /* Max font size */
            }
        }

        @media (max-width: 768px) {
            svg text {
                font-size: 18vw;
                /* Larger font on smaller screens */
            }
        }
    </style>

    <!-- <div id="loader" class="loader-container">
        <svg viewBox="0 0 1200 300">
            <text x="50%" y="50%" dy="0.35em" text-anchor="middle" class="text-body">
                Healthshade
            </text>

        </svg>
    </div> -->

    @include('website.partials.navbar')
    <div class="fixed top-4 right-4 z-50">
        <button id="darkModeToggle"
            class="bg-gray-200 dark:bg-gray-700 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
            <svg id="sunIcon" class="w-6 h-6 text-gray-800 dark:hidden" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg id="moonIcon" class="w-6 h-6 text-gray-200 hidden dark:block" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    @yield('content')

    <footer>
        <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <a
                    href="https://healthshade.com/" class="hover:underline">Healthshade™</a>. All Rights Reserved.
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="{{ url('privacy-policies') }}" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                </li>

                <li>
                    <a href="{{ url('contact') }}" class="hover:underline me-4 md:me-6">Contact us</a>
                </li>

                <li>
                    <a href="{{ url('/') }}" class="hover:underline me-4 md:me-6">Home</a>
                </li>
            </ul>
        </div>
    </footer>


    <button id="scrollToTopBtn" class="scrollToTop">↑</button>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Your application script -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Additional custom scripts -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        window.onscroll = function () {
            if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        };
        scrollToTopBtn.onclick = function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        };
    </script>


    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('loader');
            const mainContent = document.getElementById('main-content');

            function stopLoader() {
                if (loader) {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';

                    // Remove event listeners after stopping
                    document.removeEventListener('click', stopLoader);
                    document.removeEventListener('mousemove', stopLoader);
                }
            }

            document.addEventListener('click', stopLoader);
            document.addEventListener('mousemove', stopLoader);

            setTimeout(function () {
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';

                // mainContent.style.display = 'block';
                setTimeout(() => {
                    mainContent.style.opacity = '1';
                }, 50);
            }, 4000);
        });
    </script>
    <script>
        // Dark mode toggle functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme === 'dark') {
            html.classList.add('dark');
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');

            // Save theme preference
            const theme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    </script>

    @yield('script')

</body>


</html>