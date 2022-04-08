@extends('layouts.app')

@section('content')
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
        <h2 class="mt-5 mb-3">Проверки</h2>
        <form method="get" action="{{route('urls.check', ['id' => $url->id])}}">
            @csrf
            <input type="submit" class="btn btn-primary" value="Запустить проверку"><br /><br />
        </form>
        <table class="table table-bordered table-hover text-nowrap">
            <tr>
                <th>ID</th>
                <th>Код ответа</th>
                <th>h1</th>
                <th>title</th>
                <th>description</th>
                <th>Дата создания</th>
            </tr>
            @foreach ($checks as $check)
            <tr>
                <td>{{$check->id}}</td>
                <td>{{$check->status_code}}</td>
                <td>{{Str::limit($check->h1, 10)}}</td>
                <td>{{Str::limit($check->title, 30)}}</td>
                <td>{{Str::limit($check->description, 30)}}</td>
                <td>{{$check->created_at}}</td>
            </tr>
            @endforeach
        </table>
        {{ $checks->links() }}
    </div>
</main>
@endsection