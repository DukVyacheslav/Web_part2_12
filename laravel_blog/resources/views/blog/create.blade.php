@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Добавить запись</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Тема:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="text">Текст:</label>
            <textarea class="form-control" id="text" name="text" rows="5" required maxlength="2000">{{ old('text') }}</textarea>
        </div>
        <div class="form-group">
            <label for="img">Изображение:</label>
            <input type="file" class="form-control-file" id="img" name="img">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
