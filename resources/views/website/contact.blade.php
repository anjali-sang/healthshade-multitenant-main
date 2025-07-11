<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Healthshade - Inventory Management Software</title>
    <link rel="icon" href="{{ url('iconhk.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
</head>


<body>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .backbutton {
            display: flex;
            column-gap: 5px;
            justify-content: space-between;
            align-items: center;
            color: #009cf6;
            border: 1px solid #009cf6;
            border-radius: 25px;
            font-weight: 600;
            padding: 8px 30px;
            background: white;
            transition: changeBackground 0.5s ease-in-out;
            z-index: 100;
        }
        @keyframes changeBackground {
            0% {
                background: linear-gradient(to bottom right, #009cf6, #00ccff);
            }

            100% {
                background: linear-gradient(to bottom right, #00ccff, #009cf6);
            }
        }

        @media (max-width:650px){
            .font-medium{
                display: none;
            }
            .backbutton {
            border: 1px solid #009cf6;
            border-radius: 25px;
            font-weight: 600;
            padding: 3px 15px;
            background: linear-gradient(to bottom right, #009cf6, #00ccff);
            color: white;

        }
        }
    </style>
    <a href="{{ url('/') }}" class="fixed top-4 left-4 backbutton">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="font-medium">Home</span>
    </a>
    <!-- Calendly inline widget begin -->
    <div class="calendly-inline-widget"
        data-url="https://calendly.com/shiv-healthshade/30min"
        style="min-width:320px;height:100vh; width:100%; background:linear-gradient(to bottom right, #009cf6, #00ccff);">
    </div>
    <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
    <script type="text/javascript">window.onload = function() { Calendly.initBadgeWidget({ url: 'https://calendly.com/shiv-healthshade/30min', text: 'Schedule time with me', color: '#0069ff', textColor: '#ffffff', branding: true }); }</script>

    <!-- Calendly inline widget end -->

    </div>



</body>

</html>
