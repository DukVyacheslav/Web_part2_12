@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Импорт записей из CSV</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('errors'))
        <div class="alert alert-danger">
            @foreach(session('errors') as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <form action="{{ route('blog.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="csv_file">Выберите CSV файл:</label>
            <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv" required>
            <small class="form-text text-muted">
                Файл должен содержать записи в формате: "тема","сообщение","изображение"<br>
                Максимальный размер файла: 5MB
            </small>
        </div>
        <button type="submit" class="btn btn-primary">Загрузить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
