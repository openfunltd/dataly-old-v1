<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegislatorController extends Controller
{
    public function legislators()
    {
        return view('legislator.list');
    }
}
