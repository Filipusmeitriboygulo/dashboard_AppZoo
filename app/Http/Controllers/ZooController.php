<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZooController extends Controller
{
    public function index() {
        return view('zoo.index');
    }
}
