@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать запись</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Тема:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $blog->name) }}" required>
        </div>
        <div class="form-group">
            <label for="text">Текст:</label>
            <textarea class="form-control" id="text" name="text" rows="5" required>{{ old('text', $blog->text) }}</textarea>
        </div>
        <div class="form-group">
            <label for="img">Изображение:</label>
            <input type="file" class="form-control-file" id="img" name="img">
            @if($blog->img)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $blog->img) }}" alt="img" width="100">
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
