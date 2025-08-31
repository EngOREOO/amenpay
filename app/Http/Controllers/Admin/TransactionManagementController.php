<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionManagementController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }

    public function approveTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'approved']);
        
        return response()->json(['success' => true]);
    }

    public function rejectTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'rejected']);
        
        return response()->json(['success' => true]);
    }

    public function refundTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'refunded']);
        
        return response()->json(['success' => true]);
    }

    public function fraudAlerts()
    {
        $alerts = Transaction::where('risk_score', '>', 0.7)->latest()->get();
        return response()->json(['data' => $alerts]);
    }

    public function disputes()
    {
        $disputes = Transaction::where('status', 'disputed')->latest()->get();
        return response()->json(['data' => $disputes]);
    }

    public function exportTransactions(Request $request)
    {
        return response()->json(['message' => 'Export started']);
    }

    public function bulkActions(Request $request)
    {
        return response()->json(['message' => 'Bulk actions completed']);
    }
}
