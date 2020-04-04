<!DOCTYPE html>
<html lang=en>
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ url('../resources/bootstrap.min.css') }}">

        <title> @yield('title') - Gatekeep</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="navbar-brand" href="#">Gatekeep Admin</div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('admin.listUsers') }}">Users</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('admin.listTokens') }}">Tokens</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('admin.listGates') }}">Gates</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('admin.listGateManagers') }}">Gate Managers</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Events</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container mt-3">
            @isset($errors) @foreach ($errors as $a) @component('templates.error') {{ $a }} @endcomponent @endforeach @endisset
            @isset($warnings) @foreach ($warnings as $a) @component('templates.warning') {{ $a }} @endcomponent @endforeach @endisset
            @isset($successes) @foreach ($successes as $a) @component('templates.success') {{ $a }} @endcomponent @endforeach @endisset
            @yield('content')
        </div>
    </body>
</html>
