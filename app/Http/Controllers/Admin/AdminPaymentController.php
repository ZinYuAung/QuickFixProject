<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Booking, Customer, Provider relations များကို ချိတ်ဆက်၍ ငွေပေးစာရင်းများကို ဆွဲထုတ်ခြင်း
        $payments = Payment::with([
                'booking.customer.user', 
                'booking.provider.user',
                'verifier'
            ])
            ->latest('id')
            ->paginate(10);

       
        $totalRevenue = Payment::sum('service_amount');

        return view('admin.payments.index', compact('payments', 'totalRevenue'));
    }
}