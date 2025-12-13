<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
        <a class="b-brand">
            <img src="{{ asset('images/logo.png') }}" alt="My Lapo QR System" style="width: 140px;height: auto;">
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="javascript:">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse panel-color">
        <div class="d-flex align-items-end flex-column w-100 title-head">
            <p class="fs-5 mb-0" style="font-weight: 500;">{{ auth()->user()->name ?? 'Admin' }}</p>
            <p class="mb-0" style="font-size: 14px;font-weight: 400;">
                {{ auth()->user()->email ?? '' }}</p>
        </div>
    </div>
</header>
