<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TaxController extends Controller
{
    public function index()  { return view('admin.taxes.index'); }
    public function create() { return view('admin.taxes.create'); }
    public function edit($id) { return view('admin.taxes.edit', compact('id')); }
}
