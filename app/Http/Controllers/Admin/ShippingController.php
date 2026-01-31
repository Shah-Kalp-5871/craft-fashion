<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index()  { return view('admin.shipping.index'); }
    public function charges() { return view('admin.shipping.charges'); }
}
