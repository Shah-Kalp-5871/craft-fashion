<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('admin.inventory.index');
    }

    public function history()
    {
        return view('admin.inventory.history');
    }

    public function updateStock($id)
    {
        return view('admin.inventory.update', compact('id'));
    }
}
