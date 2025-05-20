@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Блог</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('blog.create') }}" class="btn btn-primary mb-3">Добавить запись</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Тема</th>
                <th>Текст</th>
                <th>Изображение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->name }}</td>
                    <td>{{ $blog->text }}</td>
                    <td>
                        @if($blog->img)
                            <img src="{{ asset('storage/' . $blog->img) }}" alt="img" width="100">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('blog.edit', $blog) }}" class="btn btn-warning btn-sm">Редактировать</a>
                        <form action="{{ route('blog.destroy', $blog) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить запись?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
