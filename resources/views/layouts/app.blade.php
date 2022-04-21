<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="csrf-param" content="_token" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Анализатор страниц</title>

        <!-- Styles -->

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    </head>
    <body class="min-vh-100 d-flex flex-column" style="margin-bottom:100px">
        <header class="flex-shrink-0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark px-3">
                <a class="navbar-brand" href="{{  route('index')  }}">Анализатор страниц</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{  route('index')  }}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{  route('urls.index')  }}">Сайты</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        @if ($errors->any())
            <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
            </div>
        @endif
        @include('flash::message')

        @yield('content')
    </body>
</html>
