<header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand w-auto fw-bold fs-3 text-success" href="{{ route('home') }}" style="letter-spacing:1px; font-family: 'Inter', sans-serif;">
            <img src="{{ asset('assets/images/logos/logo.png') }}" alt="Logo Kelapa Sawit" style="height:38px;width:auto;vertical-align:middle;margin-right:10px;">
            Kelapa Sawit <span class="text-dark">UKM</span>
        </a>
        <div class="ms-auto w-auto">
            <div class="header-social d-flex align-items-center gap-1">
                @auth
                    <a class="btn btn-primary py-2" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-success py-2 me-2" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-outline-success py-2" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </div>
</header> 