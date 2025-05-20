<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Список всех записей блога
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('blog.index', compact('blogs'));
    }

    // Форма создания новой записи
    public function create()
    {
        return view('blog.create');
    }

    // Сохранение новой записи
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'text' => 'required',
            'img' => 'nullable|image|max:5120',
        ]);

        $imgPath = null;
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('images', 'public');
        }

        Blog::create([
            'name' => $validated['name'],
            'text' => $validated['text'],
            'img' => $imgPath,
        ]);

        return redirect()->route('blog.index')->with('success', 'Запись добавлена!');
    }

    // Форма редактирования
    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    // Обновление записи
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'text' => 'required',
            'img' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('images', 'public');
            $blog->img = $imgPath;
        }

        $blog->name = $validated['name'];
        $blog->text = $validated['text'];
        $blog->save();

        return redirect()->route('blog.index')->with('success', 'Запись обновлена!');
    }

    // Удаление записи
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('blog.index')->with('success', 'Запись удалена!');
    }

    // Форма импорта CSV
    public function importForm()
    {
        return view('blog.import');
    }

    // Обработка импорта CSV
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $errors = [];
        $count = 0;
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 2) {
                    $errors[] = 'Некорректная строка: ' . implode(',', $data);
                    continue;
                }
                $name = $data[0];
                $text = $data[1];
                $img = $data[2] ?? null;
                Blog::create([
                    'name' => $name,
                    'text' => $text,
                    'img' => $img,
                ]);
                $count++;
            }
            fclose($handle);
        } else {
            $errors[] = 'Не удалось открыть файл.';
        }
        if ($errors) {
            return redirect()->route('blog.importForm')->with(['errors' => $errors]);
        }
        return redirect()->route('blog.index')->with('success', "Импортировано записей: $count");
    }
}
