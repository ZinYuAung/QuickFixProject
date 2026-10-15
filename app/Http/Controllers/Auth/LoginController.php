<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Provider;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {

   // Auto create profile if not exist
        if ($user->isCustomer() && !$user->customerProfile) {
            Customer::create(['user_id' => $user->id]);
        } 
        elseif ($user->isProvider() && !$user->providerProfile) {
            Provider::create([
                'user_id' => $user->id,
                'approval_status' => 'pending'
            ]);
        } 
        elseif (($user->role === 'admin' || $user->role === 'superadmin') && !$user->adminProfile) {
            Admin::create(['user_id' => $user->id]);
        }

        // --- Provider Approval Check ---
        if ($user->isProvider()) {
            $provider = $user->providerProfile;
            
            // အကယ်၍ Admin က approve မလုပ်ရသေးပါက (pending သို့မဟုတ် rejected)
            if ($provider && $provider->approval_status !== 'approved') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'သင့်အကောင့်ကို Admin မှ စိစစ်ဆဲဖြစ်ပါသည်။ အတည်ပြုပြီးမှသာ ဝင်ရောက်နိုင်ပါမည်။');
            }
        }
        // ---------------------------------

        // Admin or Super Admin 
        if ($user->role === 'admin' || $user->role === 'superadmin') {
            return redirect()->route('admin.dashboard'); 
        }

        // Provider 
        if ($user->role === 'provider') {
            return redirect()->route('provider.dashboard');
        }

        // Customer 
        if ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        // Default to home
        return redirect($this->redirectPath());
    }
}
