<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function index()
    {
        $stations = \App\Station::get();
        return view('backend.index', compact('stations'));
    }
}
