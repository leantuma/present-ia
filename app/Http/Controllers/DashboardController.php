<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect employees to their specific dashboard
        if (Auth::user()->isEmployee()) {
            return redirect()->route('employee.dashboard');
        }
        
        return view('dashboard');
    }
}

