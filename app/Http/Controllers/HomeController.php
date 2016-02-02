<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stations = \App\Station::get();
        return view('home.index', compact('stations'));
    }
}
