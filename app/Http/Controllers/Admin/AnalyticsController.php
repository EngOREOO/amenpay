<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function financial()
    {
        return view('admin.analytics.financial');
    }

    public function users()
    {
        return view('admin.analytics.users');
    }

    public function transactions()
    {
        return view('admin.analytics.transactions');
    }
}
