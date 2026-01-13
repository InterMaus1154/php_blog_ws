<?php

namespace App\Controllers;

use Core\Render\View;

class PostController
{
    public function index(): View
    {
        return view('posts.index');
    }

    public function show(int $id): View
    {
        return view('posts.show', compact('id'));
    }
}