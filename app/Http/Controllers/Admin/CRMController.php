<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CRMController extends Controller
{
    public function index()    { return view('admin.crm.index'); }
    public function popup()    { return view('admin.crm.popup'); }
    public function settings() { return view('admin.crm.settings'); }
}
