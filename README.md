# Лабораторная работа №12: Исследование возможностей фреймворка Laravel для разработки веб приложений

## 1 Цель работы
Ознакомиться с возможностями фреймворка Laravel и приобрести практические навыки реализации веб приложений с его помощью.

## 2 Краткие теоретические сведения

### 2.1 Фреймворк Laravel
Laravel — модель-представление-контроллер (MVC) веб-фреймворк с открытым исходным кодом. Некоторые особенности, лежащие в основе архитектуры Laravel и обеспечившие фреймворку высокую популярность:

- Использование пакетов (packages) позволяет создавать и подключать к приложению на Laravel модули в формате Composer.
- Использование REST контроллеров обеспечивает отделение логики обработки HTTP запросов.
- Использование ORM (Object-Relational Mapping) — система объектно-реляционного отображения, реализующая шаблон проектирования ActiveRecord.
- Использование миграций — системы управления версиями для базы данных, позволяющей связывать изменения в коде приложения с изменениями в структуре БД, что упрощает развёртывание и обновление приложения.
- Использование шаблонизатора Blade обеспечивает гибкое построение представлений с использованием управляющих структур, таких как условные операторы, циклы и т.п.
- Встроенный страничный вывод (pagination) упрощает генерацию страниц, заменяя различные способы решения этой задачи единым механизмом.
- Встроенное модульное тестирование (юнит тесты) обеспечивает проверку корректности работы компонентов.

### 2.2 Установка Laravel
Перед установкой необходимо убедиться, что установлено следующее ПО:

- Расширения PHP: mbstring, openssl, PDO, tokenizer и другие, необходимые для версии Laravel.
- Менеджер зависимостей Composer.

Установка Laravel производится с помощью Composer, например, команда:

```bash
composer create-project --prefer-dist laravel/laravel blog
```

создаст каталог `blog` с Laravel и всеми зависимостями.

Также возможна установка с использованием VirtualBox и Vagrant через Laravel Homestead — готовый образ для разработки, включающий веб-сервер Nginx, PHP, MySQL, Postgres, Redis, Memcached, Node.js и другое ПО.

## 3 Создание web приложения на Laravel

### 3.1 Подготовка базы данных. Миграции БД
Для создания таблицы задач используется миграция:

```bash
php artisan make:migration create_tasks_table --create=tasks
```

В файле миграции определяется структура таблицы:

```php
Schema::create('tasks', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});
```

Запуск миграций:

```bash
php artisan migrate
```

### 3.2 Модели Eloquent ORM
Создаётся модель Task:

```bash
php artisan make:model Task
```

Класс модели:

```php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
}
```

### 3.3 Маршрутизация
В файле `routes/web.php` определяются маршруты:

```php
use App\Task;
use Illuminate\Http\Request;

Route::get('/', function () {
    $tasks = Task::orderBy('created_at', 'asc')->get();
    return view('tasks', ['tasks' => $tasks]);
});

Route::post('/task', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
    ]);
    if ($validator->fails()) {
        return redirect('/')->withInput()->withErrors($validator);
    }
    $task = new Task;
    $task->name = $request->name;
    $task->save();
    return redirect('/');
});

Route::delete('/task/{id}', function ($id) {
    Task::findOrFail($id)->delete();
    return redirect('/');
});
```

### 3.4 Представления и шаблоны
Используется Blade шаблонизатор.

Макет `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laravel Quickstart - Basic</title>
    <!-- CSS и JavaScript -->
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <!-- Содержимое Navbar -->
        </nav>
    </div>
    @yield('content')
</body>
</html>
```

Представление задач `resources/views/tasks.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="panel-body">
    @include('common.errors')
    <form action="/task" method="POST" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="task" class="col-sm-3 control-label">Задача</label>
            <div class="col-sm-6">
                <input type="text" name="name" id="task-name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-default">
                    <i class="fa fa-plus"></i> Добавить задачу
                </button>
            </div>
        </div>
    </form>
</div>

@if (count($tasks) > 0)
<div class="panel panel-default">
    <div class="panel-heading">
        Текущие задачи
    </div>
    <div class="panel-body">
        <table class="table table-striped task-table">
            <thead>
                <th>Задача</th>
                <th>&nbsp;</th>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    <td class="table-text">
                        <div>{{ $task->name }}</div>
                    </td>
                    <td>
                        <form action="/task/{{ $task->id }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button>Удалить задачу</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
```

### 3.5 Валидация и обработка ошибок
Ошибки валидации отображаются через включаемое представление `resources/views/common/errors.blade.php`:

```blade
@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Ошибка!</strong>
    <br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
```

## 4 Варианты заданий
- Реализовать страницы «Редактор Блога» и «Загрузка сообщений блога» в разделе администратора персонального сайта.
- Реализовать страницу «Мой Блог» в пользовательском разделе.
- Предусмотреть возможность редактирования и удаления записей блога на странице «Редактор Блога», реализовав валидацию средствами Laravel.
- Разработать миграцию, модель, маршруты, контроллеры, валидации, макеты пользовательской и административной части сайта, а также необходимые представления на основе имеющихся файлов.
- Дизайн сайта и блога должен остаться прежним.

## 5 Порядок выполнения работы
- Используя фреймворк Laravel, реализовать указанные страницы и функционал.
- Обеспечить валидацию данных.
- Сохранить и расширить существующий дизайн.

## 6 Содержание отчета
- Цель работы.
- Порядок выполнения работы.
- Исходные тексты разработанных миграций, моделей, контроллеров, макетов и представлений.
- Изображения разработанных страниц.
- Выводы по результатам работы.

## 7 Контрольные вопросы
- Для чего используется фреймворк Laravel?
- Как осуществляется маршрутизация в Laravel?
- Приведите пример простейшего класса контроллера в Laravel.
- Как создать новую модель в Laravel? Приведите пример.
- Для чего в Laravel используются миграции?
- Расскажите о возможностях шаблонизатора Blade.
