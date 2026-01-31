<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }
    public function create()
    {
        return view('admin.categories.create');
    }
    public function edit($id)
    {
        return view('admin.categories.edit', compact('id'));
    }

    public function show($id)
    {
        return view('admin.categories.show', ['id' => $id]);
    }
}
