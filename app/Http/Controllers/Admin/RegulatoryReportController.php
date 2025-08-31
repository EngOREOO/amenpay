<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegulatoryReportController extends Controller
{
    public function index()
    {
        return view('admin.regulatory-reports.index');
    }

    public function create()
    {
        return view('admin.regulatory-reports.create');
    }

    public function show($id)
    {
        return view('admin.regulatory-reports.show', compact('id'));
    }
}
