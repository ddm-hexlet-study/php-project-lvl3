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
                <a class="navbar-brand" href="/">Анализатор страниц</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="/urls">Сайты</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <main class="flex-grow-1">
            <div class="container-lg">
                <h1 class="mt-5 mb-3">Сайт: {{$url->name}}</h1>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-nowrap">
                        <tr>
                            <td>ID</td>
                            <td>{{$url->id}}</td>
                        </tr>
                        <tr>
                            <td>Имя</td>
                            <td>{{$url->name}}</td>
                        </tr>
                        <tr>
                            <td>Дата создания</td>
                            <td>{{$url->created_at}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </main>
    </body>
</html>