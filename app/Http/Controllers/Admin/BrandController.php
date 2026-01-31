<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()  { return view('admin.brands.index'); }
    public function create() { return view('admin.brands.create'); }
    public function edit($id) { return view('admin.brands.edit', compact('id')); }
}
