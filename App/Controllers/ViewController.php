<?php

namespace App\Controllers;

class ViewController
{
    public function index()
    {
        $users = db()->all('SELECT * FROM users');
        return view('index', compact('users'));
    }
}