<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('admin.audit-logs.index');
    }

    public function show($id)
    {
        return view('admin.audit-logs.show', compact('id'));
    }
}
