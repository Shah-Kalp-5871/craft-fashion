<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()     { return view('admin.reports.index'); }
    public function sales()     { return view('admin.reports.sales'); }
    public function customers() { return view('admin.reports.customers'); }
    public function products()  { return view('admin.reports.products'); }
}
