@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Добавить запись в блог</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ошибка!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif<create_file>
<path>laravel_blog/resources/views/blog/create.blade.php</path>
<content>
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Добавить запись в блог</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ошибка!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('blog.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Заголовок</label>
<input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required maxlength="255" />
        </div>
        <div class="form-group">
            <label for="content">Содержание</label>
<textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
