<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Scripts --}}
        @if(config('app.env') === 'production')
            @if(config('app.url') !== 'http://localhost' && \Illuminate\Support\Str::contains(config('app.url'), 'https://'))
                <script type="text/javascript" async>
                    if (location.protocol !== 'https:') {
                        location.replace(`https:${location.href.substring(location.protocol.length)}`);
                    }
                </script>
            @endif
        @endif
        <script src="{{ asset('js/app.js') }}" defer></script>

        {{-- Fonts --}}
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        {{-- Styles --}}
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            @auth
                                <li class="nav-item">
                                    <a href="#" class="nav-link">{{ trans('Clients') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">{{ trans('Rental') }}</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle"
                                       data-bs-toggle="dropdown">{{ trans('Vehicles') }}</a>
                                    <div class="dropdown-menu">
                                        <a href="#" class="dropdown-item">{{ trans('Cars') }}</a>
                                        <a href="{{ route('brands') }}" class="dropdown-item">{{ trans('Brands') }}</a>
                                        <a href="#" class="dropdown-item">{{ trans('Models') }}</a>
                                    </div>
                                </li>
                            @endauth
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            {{-- Breadcrumb --}}
            @auth
                <div class="d-flex justify-content-center align-items-center h-auto mt-2">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('home') }}">{{ trans('Home') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active"
                                            aria-current="page">{{ Route::currentRouteName() }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

            <main class="pt-2 pb-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>
