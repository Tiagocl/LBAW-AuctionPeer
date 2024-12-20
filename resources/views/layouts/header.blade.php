<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="header-container">
        <div class="header-info">
            <div class="left-about">
                <a href="{{ url('/auction') }}">
                    <span>Home</span>
                </a>
                <span id="service">Service</span>
                <a href="{{ route('faq') }}"><span>Questions</span></a>
                <a href="{{ route('misc.about') }}"><span>About Us</span></a>
            </div>

            <div class="logo">
                <a href="{{ url('/auction') }}" class="logo">
                    <span>AuctionPeer.</span>
                </a>
            </div>
            <div class="about">
                @auth
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="admin-panel">Admin Panel</a>
                @endif
                <a href="{{route('user.balance', auth()->user())}}">{{auth()->user()->balance}}€</a>
                <a href="{{ route('inbox') }}">
                    <div class="select-wrappe" style="position: relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox">
                            <path d="M4 4h16v16H4z" />
                            <path d="M22 12H2" />
                            <path d="M7 12l5 5 5-5" />
                        </svg>
                        @if($notificationCount > 0)
                        <span class="notification-count">{{ $notificationCount }}</span>
                        @endif
                    </div>
                </a>

                

                
                <div class="dropdown-wrapper" id="dropdownWrapper">
                    <div class="dropdown-toggle" id="dropdownToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round">
                            <circle cx="12" cy="8" r="5"></circle>
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down">
                            <path d="m6 9 6 6 6-6"></path>
                        </svg>
                    </div>

                    <!-- Dropdown menu -->
                    <ul class="dropdown-menu" id="dropdownMenu">
                        @if(!auth()->user()->isAdmin())
                        <li>
                            <a id="drop-a" href="{{ route('user.show', auth()->user()) }}">See Profile</a>
                        </li>
                        <li>
                            <a id="drop-a" href="{{ route('user.followed', auth()->user()) }}">Followed</a>
                        </li>
                        
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="GET" id="logout-form">
                                @csrf
                                <span class="logout-link" onclick="document.getElementById('logout-form').submit();">Logout</span>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

                @guest
                <a href="{{ route('login') }}" class="login-button">Login</a>
                @endguest
            </div>

        </div>



        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif


    </div>
    <div class="header-border"></div>
</body>

</html>