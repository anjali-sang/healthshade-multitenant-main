<style>
    /* Hero section starts */

    .hero {
        padding-left: var(--padding-section);
        padding-right: var(--padding-section);
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 85vh;
        animation: heroSectionAnimate 1s linear;
    }

    @keyframes heroSectionAnimate {
        0% {
            transform: translateX(-150px);
            opacity: 0;
        }

        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .main-heading {
        padding: 10px 30px;
        margin-top: 65px;
        font-size: var(--font-size-main-heading);
        display: flex;
        flex-direction: column;
        font-weight: 600;
        font-family: var(--font-heading);
    }

    .main-heading>h1 {
        text-align: center;
    }

    .main-heading .heading-1 {
        margin-bottom: 15px;
        white-space: nowrap;
        /* Prevents text from wrapping */
    }

    .main-heading>h1>span {
        color: var(--color-primary);
        margin-left: 10px;
    }

    .main-heading>h1:nth-child(2),
    h1:nth-child(3) {
        margin-top: -20px;
    }

    .sub-heading {
        font-size: var(--font-size-sub-heading);
        font-weight: 300;
        display: flex;
        align-items: center;
        justify-content: center;

        text-align: center;
        margin-top: 10px;
    }


    @media (max-width: 820px) {
        .hero {
            padding-left: 10px !important;
            padding-right: 10px !important;
            height: 70vh;
        }

        .main-heading {
            padding: 35px 5px;
            width: 100%;
            margin-top: 35px;
            font-size: 34px;
            /* font-weight: 400; */
            align-items: center;
            font-family: var(--font-heading);
        }

        .sub-heading {
            font-size: 16px;
            font-weight: 200;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            margin-top: 10px;
        }

        .sign-easily {
            margin-top: 2px;
            padding: 4px;
            font-size: var(--font-size-trial-button);
            text-align: center;
            color: var(--color-text);
            opacity: 0.7;
        }


    }

    .typewriter {
        /* display: inline-block; */
        border-right: 1px solid;
        white-space: wrap;
        overflow: hidden;
        animation: blink 0.7s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            border-color: transparent;
        }

        50% {
            border-color: transparent;
        }
    }

    .slowFading {
        animation: fadeIn 3s linear;
        animation-timeline: view();
        animation-range: entry 0% cover 40%;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            clip-path: inset(100% 100% 0 0);
        }

        to {
            opacity: 1;
            clip-path: inset(0 0 0 0);
        }
    }

    .free-trial-button {
        position: relative;
        color: var(--color-white);
        padding: 10px 25px 10px 35px;
        border-radius: 25px;
        overflow: hidden;
        z-index: 1;
    }
</style>
<div class="hero">
    <div class="main-heading">
        <h1 class="heading-1">Managing your <span class="add-break"></span> Inventory</h1>
        <h1><span>made</span><span class="typewriter" id="typewriter"></span><span class="blink">_</span></h1>
    </div>
    <div class="sub-heading">
        Streamline Your Clinic's Inventory Management <br> with a Robust Solution for Optimized <br>Supply Control.
    </div>
    <div class="flex flex-col items-center justify-center mb-3 mt-6">
        <button class="free-trial-button"><a href="{{url('register')}}">Try Inventrafor
                free</a><span class="arrow">&rarr;</span></button>
        <p class="sign-easily text-xs">No credit card required. Sign up now !</p>
    </div>
</div>
<script>
    const phrases = ["effective", "efficient", "easy"];
    let index = 0;
    let charIndex = 0;

    function type() {
        const currentPhrase = phrases[index];
        const typewriterElement = document.getElementById("typewriter");

        if (charIndex < currentPhrase.length) {
            typewriterElement.textContent += currentPhrase.charAt(charIndex);
            charIndex++;
            setTimeout(type, 100);
        } else {
            setTimeout(erase, 2500);
        }
    }

    function erase() {
        const typewriterElement = document.getElementById("typewriter");

        if (charIndex > 0) {
            typewriterElement.textContent = typewriterElement.textContent.slice(0, -1);
            charIndex--;
            setTimeout(erase, 50);
        } else {
            index = (index + 1) % phrases.length;
            setTimeout(type, 500);
        }
    }
    type();
</script>
<script>
    function toggleLineBreak() {
        const inventoryText = document.querySelector('.add-break');
        const br = document.querySelector('.after-inventory br');
        if (window.innerWidth <= 800) {
            if (!br) {
                const newBr = document.createElement('br');
                inventoryText.appendChild(newBr);
            }
        } else {
            if (br) {
                br.remove();
            }
        }
    }
    toggleLineBreak();
</script>