<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FraudDetectionController extends Controller
{
    public function index()
    {
        return view('admin.fraud-detection.index');
    }

    public function alerts()
    {
        return view('admin.fraud-detection.alerts');
    }

    public function rules()
    {
        return view('admin.fraud-detection.rules');
    }
}
