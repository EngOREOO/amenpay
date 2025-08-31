<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiAnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.ai-analytics.index');
    }

    public function predictions()
    {
        return view('admin.ai-analytics.predictions');
    }

    public function insights()
    {
        return view('admin.ai-analytics.insights');
    }
}
