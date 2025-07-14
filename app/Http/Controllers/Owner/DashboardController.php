<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan bisnis/monitoring bisa ditambahkan di sini
        return view('owner.dashboard');
    }
} 