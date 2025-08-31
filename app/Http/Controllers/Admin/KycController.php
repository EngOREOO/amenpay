<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KycVerification;
use App\Models\User;

class KycController extends Controller
{
    public function index()
    {
        $kycRequests = KycVerification::with('user')
            ->latest('submitted_at')
            ->paginate(20);

        $pendingCount = KycVerification::where('status', 'pending')->count();
        $approvedCount = KycVerification::where('status', 'approved')->count();

        return view('admin.kyc.index', compact('kycRequests', 'pendingCount', 'approvedCount'));
    }

    public function show($id)
    {
        $kyc = KycVerification::with('user')->findOrFail($id);
        return view('admin.kyc.show', compact('kyc'));
    }

    public function review($id)
    {
        $kyc = KycVerification::with('user')->findOrFail($id);
        return view('admin.kyc.review', compact('kyc'));
    }

    public function approve($id)
    {
        $kyc = KycVerification::findOrFail($id);
        $kyc->update([
            'status' => 'approved',
            'approved_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        // Update user verification status
        if ($kyc->user) {
            $kyc->user->update(['is_verified' => true]);
        }

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC request approved successfully');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $kyc = KycVerification::findOrFail($id);
        $kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id()
        ]);

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC request rejected successfully');
    }
}
