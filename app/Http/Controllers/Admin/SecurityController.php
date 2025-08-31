<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        return view('admin.security.index');
    }

    public function fraudMonitoring()
    {
        return view('admin.security.fraud-monitoring');
    }

    public function compliance()
    {
        return view('admin.security.compliance');
    }
}
