<?php

namespace App\Controllers;

use Core\View;

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