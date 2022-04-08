<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Анализатор страниц</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="min-vh-100 d-flex flex-column">
        <header class="flex-shrink-0">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark px-3">
                <a class="navbar-brand" href="{{route('urls.new')}}">Анализатор страниц</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('urls.new')}}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="{{route('urls.index')}}">Сайты</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <main class="flex-grow-1">
            <div class="container-lg mt-3">
                <h1 class="mt-5 mb-3">Сайты</h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-nowrap">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Последняя проверка</th>
                            <th>Код ответа</th>
                        </tr>
                        @foreach ($urls as $url)
                        <tr>
                            <td>{{$url->id}}</td>
                            <td><a href="{{route('urls.show', ['id' => $url->id])}}">{{$url->name}}</a></td>
                            <td>{{$url->latest_created_at}}</td>
                            <td>{{$url->status_code}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>