<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    public function bills() 
    {
        return view('bill.list');
    }
}
