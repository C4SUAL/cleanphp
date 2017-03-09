<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return view('dashboard');
    }
}
