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
use Illuminate\Support\Facades\Storage;

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

            // Provider NRC image validation
            'nrc_front_image' => [
                'required_if:role,provider',
                'image',
                'mimes:jpeg,png,webp',
                'max:2048',
            ],

            'nrc_back_image' => [
                'required_if:role,provider',
                'image',
                'mimes:jpeg,png,webp',
                'max:2048',
            ],
        ]);
    }

    protected function create(array $data)
    {

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


        // Provider Profile
        if ($user->role === 'provider') {

            // Retrieve uploaded NRC images from the request
$request = request();

$frontImage = $request->file('nrc_front_image');
$backImage = $request->file('nrc_back_image');

// Store images on the private local disk
$frontImagePath = $frontImage->store('provider-nrc', 'local');
$backImagePath = $backImage->store('provider-nrc', 'local');
            $provider = Provider::create([
                'user_id' => $user->id,
                'area_id' => $data['area_id'] ?? null,
                'bio' => $data['bio'] ?? null,
                'experience_years' => $data['experience_years'] ?? 0,
                'availability_status' => 'offline',
                'approval_status' => 'pending',
                'rating' => 0.00,

                // Save NRC image paths
                'nrc_front_image' => $frontImagePath,
                'nrc_back_image' => $backImagePath,
            ]);
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