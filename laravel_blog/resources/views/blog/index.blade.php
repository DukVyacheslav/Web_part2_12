@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Мой Блог</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('blog.create') }}" class="btn btn-primary">Добавить запись</a>

    @if($blogs->count())
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Заголовок</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('blog.edit', $blog) }}" class="btn btn-sm btn-warning">Редактировать</a>
                        <form action="{{ route('blog.destroy', $blog) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить запись?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Записей нет.</p>
    @endif
</div>
@endsection
