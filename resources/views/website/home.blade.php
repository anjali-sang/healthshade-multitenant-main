@extends('website.main')
@section('content')
    @include('website.partials.herosection')

    @include('website.partials.videoSection')

    @include('website.partials.qr')

    @include('website.partials.features')


    <div class="integrations">
        <div class="integrations-heading">Simplify your everyday tasks.</div>
        <div class="integrations-sub-text">by seamlessly connecting your tools and streamline your workflow.</div>
        <div class="integration-features">
            <div class="integration-feature integration-feature-1">
                <div>
                    <span class="material-symbols-outlined">
                        lab_profile
                    </span>
                </div>
                <div class="heading">
                    Reporting
                </div>
                <div class="tag-line">
                    Stay on top of things with always up-to-date reporting features.
                </div>
                <div class="summary">
                    Generate customized reports on inventory usage, costs, and trends. These reports provide valuable
                    insights for strategic decision-making and continuous improvement in inventory management practices.
                </div>
            </div>
            <div class="integration-feature integration-feature-1">
                <div>
                    <span class="material-symbols-outlined">
                        inventory
                    </span>
                </div>
                <div class="heading">
                    Inventory
                </div>
                <div class="tag-line">
                    Never lose of track of what's in stock with accurate inventory tracking.
                </div>
                <div class="summary">
                    Serving as the central hub for all your medical inventory activities, our Inventory System allows
                    users to promptly check stock quantities and issues alerts for timely supply reorders and
                    facilitates effortless monitoring for accurate inventory
                    levels.
                </div>
            </div>
            <div class="integration-feature integration-feature-1">
                <div>
                    <span class="material-symbols-outlined">
                        folder_limited
                    </span>
                </div>
                <div class="heading">
                    Minimizing Errors
                </div>
                <div class="tag-line">
                    Always ready to full fill the demand with Healthshade.
                </div>
                <div class="summary">
                    Our foremost commitment is meeting the medical supply requirements of every business. Addressing the
                    intricate demands of clinics, our efficient inventory management software delivers unparalleled
                    barcoding and reporting capabilities.
                </div>
            </div>
        </div>
        <div class="integrations-message">Get Integration with Suppliers like </div>
        <div class="suppliers">
            <img class="slowFading" src="{{ url('/suppliers/hs.png') }}" alt="henryschein">
            <img class="slowFading" src="{{ url('/suppliers/mck.png') }}" alt="henryschein">
            <img class="slowFading" src="{{ url('/suppliers/greer.png') }}" alt="greer">
            <img class="slowFading" src="{{ url('/suppliers/alk.png') }}" alt="alk">
            <img class="slowFading" src="{{ url('/suppliers/hollister.png') }}" alt="hollister">
        </div>
    </div>

    @include('website.partials.free-trial')

    @include('website.partials.why-us')

    @include('website.partials.sounds-like-plan')
@endsection
