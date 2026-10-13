<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Provider;
use App\Models\ServiceArea;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:customer,provider'],

            // Provider Specific Validation
            'city_id' => ['required_if:role,provider', 'nullable', 'exists:cities,id'],
            'area_id' => ['required_if:role,provider', 'nullable', 'exists:service_areas,id'],
            'experience_years' => ['required_if:role,provider', 'nullable', 'integer', 'min:0', 'max:100'],
            'bio' => ['nullable', 'string'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:services,id'],
        ]);
    }

    protected function create(array $data)
    {
        // အကယ်၍ Provider ဖြစ်ပါက Area သည် Selected City ထဲတွင် ပါဝင်ခြင်း ရှိမရှိ စစ်ဆေးမည်
        if (isset($data['role']) && $data['role'] === 'provider') {
            $areaExists = ServiceArea::where('id', $data['area_id'])
                ->where('city_id', $data['city_id'])
                ->exists();

            if (!$areaExists) {
                throw ValidationException::withMessages([
                    'area_id' => 'The selected service area does not belong to the selected city.',
                ]);
            }
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'is_active' => true,
            'password' => Hash::make($data['password']),
        ]);

        // Provider Profile ဖန်တီးခြင်း
        if ($user->role === 'provider') {
            $provider = Provider::create([
                'user_id' => $user->id,
                'area_id' => $data['area_id'] ?? null,
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'availability_status' => 'offline',
                'approval_status' => 'pending',
                'rating' => 0.00,
            ]);

            // Services များ ချိတ်ဆက်ခြင်း
            if (!empty($data['services'])) {
                $provider->services()->sync($data['services']);
            }
        }

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        Auth::logout();
        return redirect('login')->with('success', 'Registration successful! Please login once approved by admin.');
    }
}