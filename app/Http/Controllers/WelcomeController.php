<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    function showWelcomePage()
    {
        return view('welcome');
    }
}
