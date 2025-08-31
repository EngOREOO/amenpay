<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        return view('admin.payment-gateways.index');
    }

    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    public function show($id)
    {
        return view('admin.payment-gateways.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.payment-gateways.edit', compact('id'));
    }
}
