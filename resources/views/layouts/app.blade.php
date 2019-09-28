<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="{{ asset('images/vasudev_favicon.png') }}" sizes="11x11">
        <title>Vasudev</title>

        <!-- Scripts -->
        <script src="{{ asset('js/jquery-3.4.1.min.js') }}" ></script>
        <script src="{{ asset('js/custom.js') }}" defer></script>

        <script src="{{ asset('js/app.js') }}" defer></script>        
        <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>        
        
        <script src="{{ asset('js/jquery.dataTables.min.js') }}" defer></script>                
        <script src="{{ asset('js/dataTables.buttons.min.js') }}" defer></script>
        <script src="{{ asset('js/jszip.min.js') }}" defer></script>
        <script src="{{ asset('js/pdfmake.min.js') }}" defer></script>
        <script src="{{ asset('js/vfs_fonts.js') }}" defer></script>
        <script src="{{ asset('js/buttons.html5.min.js') }}" defer></script>
        <script src="{{ asset('js/buttons.print.min.js') }}" defer></script>
        <script src="{{ asset('js/buttons.colVis.min.js') }}" defer></script>
        <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}" defer></script>       
        
        <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet"> 
            
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
        <script src="{{ asset('js/select2.min.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" >
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" >
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{url('images/logo.png') }}" alt="{{ config('app.name', 'Vasudev') }}" width="125">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        @auth
                        <ul class="navbar-nav mr-auto">
                            <li class="{{ (request()->is('companies')) ? 'active' : '' }}"><a href="{{ route('companies-list') }}">Companies</a></li>
                            <li class="{{ (request()->is('tags')) ? 'active' : '' }}"><a href="{{ route('tags-list') }} ">Tags</a></li>
                            <li class="{{ (request()->is('customer')) ? 'active' : '' }}"><a href="{{ route('customer-list') }}">Customer</a></li>                            
                            <li class="{{ (request()->is('customer/assigntags')) ? 'active' : '' }}"><a href="{{ route('customer-assign-tags') }}">Assign Tags</a></li>                            
                            <li class="{{ (request()->is('import')) ? 'active' : '' }}"><a href="{{ route('import-list') }}">Import Log</a></li>                            

                        </ul>
                        @endguest
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>

            <footer>
                <div class="container"> 
                    <p>&copy  Copyright {{date("Y")}} Vasudev.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
