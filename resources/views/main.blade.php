<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </ul>
            </div>
        @elseif(Session::has('status'))
            <div class="alert alert-info">
                <ul> 
                    {{Session::get('status')}}
                </ul>
            </div>
        @endif
        <main class="flex-grow-1">
            <div class="container-lg mt-3">
                <div class="row">
                    <div class="col-12 col-md-10 col-lg-8 mx-auto border rounded-3 bg-light p-5">
                        <h1 class="display-3">Анализатор страниц</h1>
                        <p class="lead">Бесплатно проверяйте сайты на SEO пригодность</p>
                        <form action="/" method="post" class="d-flex justify-content-center">
                            @csrf                  
                            <input type="text" name="url[name]" value="" class="form-control form-control-lg" placeholder="https://www.example.com">
                            <input type="submit" class="btn btn-primary btn-lg ms-3 px-5 text-uppercase mx-3" value="Проверить">
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>