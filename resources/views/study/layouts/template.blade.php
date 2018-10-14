<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/mint-ui/lib/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="{{ asset('./js/app.js') }}"></script>
</head>
<body>
<div id="app" class="container-fluid">
        <nav class="light-blue lighten-2">
            <div class="nav-wrapper row ">
                <div class="col s4 col-4">
                    <a href="{{ route('home') }}" class="brand-logo center"><i class="material-icons">home</i>Home</a>
                </div>
                <div class="col s8 col-8">
                    <ul class="right">
                        @auth
                            <li><a href="">{{ Auth::user()->name }}</a></li>
                        @else
                            <li><a href="{{ route('login') }}">登入</a></li>
                            <li><a href="{{ route('register') }}">註冊</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    <div class="row">
        <div class="col s3 col-md-3">
            <div class="container">
                <div class="section">
                    <ul>
                        <li>
                            <div class="collapsible-header"><a href="{{ route('show') }}"><h5>單字表</h5></a></div>
                        </li>
                        <li>
                            <ul class="collapsible z-depth-0" style="border: none;">
                                <li>
                                    <div class="collapsible-header"><a><h5>新增單字</h5></a></div>
                                    <div class="collapsible-body">
                                        <div class="section"><a href="{{ route('create') }}">輸入單字</a></div>
                                        <div class="section"><a href="{{ route('import') }}">上傳</a></div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="collapsible-header"><a href=""><h5>紀錄本</h5></a></div>
                        </li>
                        <li>
                            <div class="collapsible-header"><a href=""><h5>練習</h5></a></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col s9 col-md-9 row">
            <div class="col s10 col-10">                @yield('content')
            </div>
        </div>
    </div>
</div>

    <script src="https://unpkg.com/mint-ui/lib/index.js"></script>
    <script>
        $(document).ready(function(){
            $('.collapsible').collapsible();
            $('select').formSelect();
        });
    </script>
    @yield('js')
</body>
</html>
