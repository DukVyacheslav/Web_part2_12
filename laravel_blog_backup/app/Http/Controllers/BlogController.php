<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Display a listing of the blog posts
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return view('blog.index', compact('blogs'));
    }

    // Show the form for creating a new blog post
    public function create()
    {
        return view('blog.create');
    }

    // Store a newly created blog post in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Blog::create($request->only('title', 'content'));

        return redirect()->route('blog.index')->with('success', 'Blog post created successfully.');
    }

    // Show the form for editing the specified blog post
    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    // Update the specified blog post in storage
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $blog->update($request->only('title', 'content'));

        return redirect()->route('blog.index')->with('success', 'Blog post updated successfully.');
    }

    // Remove the specified blog post from storage
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Blog post deleted successfully.');
    }
}
