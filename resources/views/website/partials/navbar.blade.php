<!-- sidenav -->
<style>
    /* Navbar CSS */

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 40px 75px 25px;
    }

    .menu {
        display: flex;
        align-items: center;
        font-size: var(--font-size-menu);
    }

    .menu a {
        text-decoration: none;
        color: var(--color-primary);
    }

    .free-trial a {
        text-decoration: none;
        color: white;
    }

    .open-side-menu {
        display: none;
    }

    @media (max-width: 820px) {
        header {
            padding: 25px 10px;
        }

        .menu {
            display: none;
        }

        .open-side-menu {
            display: block;
            cursor: pointer;
        }
    }

    .menu-item {
        margin-left: 30px;
        border: none;
        cursor: pointer;
        color: var(--color-text);
    }

    .free-trial {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 7px 30px;
        color: var(--color-white);
        border-radius: 25px;
        font-weight: 600;
        overflow: hidden;
        z-index: 1;
        border: 2px solid var(--color-primary);
    }

    .free-trial::before,
    .free-trial::after,
    .free-trial-button::before,
    .free-trial-button::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        transition: opacity var(--transition-duration) ease-in-out;
    }

    .free-trial::before,
    .free-trial-button::before {
        background: linear-gradient(to bottom right, var(--color-primary), var(--color-secondary));
        opacity: 1;
    }

    .free-trial::after,
    .free-trial-button::after {
        background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
        opacity: 0;
    }

    .free-trial:hover::before,
    .free-trial-button:hover::before {
        opacity: 0;
    }

    .free-trial:hover::after,
    .free-trial-button:hover::after {
        opacity: 1;
    }

    .arrow {
        font-weight: 900;
        font-size: 20px;
        opacity: 0;
        visibility: hidden;
        margin-left: 10px;
        transition: opacity var(--transition-duration) ease, visibility var(--transition-duration) ease;
    }

    .free-trial:hover .arrow,
    .free-trial-button:hover .arrow {
        opacity: 1;
        visibility: visible;
    }

    .logo {
        height: 50px;
        width: 185px;
    }

    .border {
        border-bottom: none;
        padding-bottom: 3px;
        transition: border-bottom 0.3s ease-in;
    }

    .border:hover {
        border-bottom: 2px solid var(--color-primary);
    }

    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 2;
        top: 0;
        left: 0;
        background-color: var(--color-primary);
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: white;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        color: #f1f1f1;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {
            padding-top: 15px;
        }

        .sidenav a {
            font-size: 18px;
        }
    }



    /* .side-free-trial{
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 7px 30px;
        background-color: var(--color-white);
        color:var(--color-primary)!important;
        border-radius: 25px;
        font-weight: 600;
        overflow: hidden;
        z-index: 1;
        margin: 10px 15px;

    } */
</style>
<header>
    <a href="{{ url('/') }}">
        <x-application-logo class="w-auto h-20 fill-current text-gray-500 logo" alt="Healthshade" />
    </a>

    <div class="menu">
        <div class="menu-item border"><a href="{{ url('contact') }}">Talk to Sales</a></div>
        <div class="menu-item border"><a href="{{ url('login') }}">Login</a></div>
        {{-- <div class="menu-item free-trial">Free Trail<span class="arrow">&rarr;</span></div> --}}
        <div class="menu-item free-trial"><a href="{{ url('register') }}">Free Trial</a></div>
    </div>
    <div class="open-side-menu">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; </span>
    </div>
</header>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="{{ url('contact') }}">Talk to sales</a>
    <a href="{{ url('login') }}">Login</a>
    <a class="side-free-trial" href="https://healthshade.com/register">Free Trial</a>
</div>

<script>
    function openNav() {
        console.log("openNav");
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>