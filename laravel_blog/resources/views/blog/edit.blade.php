@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать запись в блоге</h1>

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

    <form action="{{ route('blog.update', $blog) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $blog->title) }}" required maxlength="255">
        </div>
        <div class="form-group">
            <label for="content">Содержание</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $blog->content) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
