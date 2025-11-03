<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Kuesionerroot extends Controller
{
    //
    public function index()
    {

        return view('pages.kuesioner.index');
    }
}
