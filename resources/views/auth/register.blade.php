@extends('layouts.app')

@section('title', 'Register - QuickFix')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- LEFT SIDE -->
        <div class="col-5 d-flex align-items-center justify-content-center auth-left-panel">
            <div class="auth-left-content">
                <a href="{{ url('/') }}" class="auth-logo-link">
                    <h2>
                        <i class="fa-solid fa-wrench"></i>
                        QuickFix
                    </h2>
                </a>
                <h1 class="auth-welcome">Create Your Account</h1>
                <p>Join QuickFix and get started today.</p>
                <i class="fa-solid fa-house auth-left-icon"></i>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-7 d-flex align-items-center justify-content-center auth-right-panel">
            <div class="auth-form-container register" style="max-height: 90vh; overflow-y: auto;">

                <h2 class="auth-title">Register</h2>
                <p class="auth-subtitle">Create your account</p>
                {{-- Registration error message section --}}
@if ($errors->any())
    <div class="custom-alert custom-alert-danger" role="alert">
        <i class="fa-solid fa-circle-exclamation custom-alert-icon"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

                <!-- REGISTER FORM START -->
                <form method="POST"
      action="{{ route('register') }}"
      enctype="multipart/form-data"
      novalidate>
                    @csrf

                    <!-- Full Name -->
                    <div class="auth-form-group">
                        <label class="auth-form-label">Full Name</label>
                        <input type="text" name="name" class="auth-form-control" value="{{ old('name') }}" placeholder="Enter your full name" required>
                    </div>

                    <!-- Email -->
                    <div class="auth-form-group">
                        <label class="auth-form-label">Email Address</label>
                        <input type="email" name="email" class="auth-form-control" value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>

                    <!-- Phone -->
                    <div class="auth-form-group">
                        <label class="auth-form-label">Phone Number</label>
                        <input type="text" name="phone" class="auth-form-control" value="{{ old('phone') }}" placeholder="+95 9xxxxxxxxx" required>
                    </div>

                    <!-- Password -->
                    <div class="auth-form-group">
                        <label class="auth-form-label">Password</label>
                        <input type="password" name="password" class="auth-form-control" placeholder="Enter your password" required>
                    </div>

                    <!-- Confirm Password -->
                    <div class="auth-form-group">
                        <label class="auth-form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="auth-form-control" placeholder="Confirm your password" required>
                    </div>

                    <!-- Role Selection -->
                    <div class="auth-role-group">
                        <label class="auth-role-title">I am a</label>
                        <div class="auth-role">
                            <label class="auth-role-option">
                                <input type="radio" name="role" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                                Customer
                            </label>
                            <label class="auth-role-option">
                                <input type="radio" name="role" value="provider" {{ old('role') == 'provider' ? 'checked' : '' }}>
                                Provider
                            </label>
                        </div>
                    </div>

                   <!-- =====================================================
                         PROVIDER SPECIFIC FIELDS
                    ====================================================== -->
                    <div id="provider-specific-fields" style="display: {{ old('role') == 'provider' ? 'block' : 'none' }}; border-top: 1px dashed #ccc; padding-top: 15px; margin-top: 15px;">
                        <h4 style="font-size: 16px; margin-bottom: 12px; color: #333;">
                            <i class="fa-solid fa-user-tie"></i> Provider Professional Details
                        </h4>

                        <!-- 1. City Selection -->
                        <div class="auth-form-group">
                            <label class="auth-form-label">City</label>
                            <select name="city_id" id="city_id" class="auth-form-control @error('city_id') is-invalid @enderror">
                                <option value="">Select your city</option>
                                @foreach(\App\Models\City::where('is_active', true)->orderBy('name')->get() as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                          
                        </div>

                        <!-- 2. Service Area Selection -->
                        <div class="auth-form-group">
                            <label class="auth-form-label">Service Area</label>
                            <select name="area_id" id="area_id" class="auth-form-control @error('area_id') is-invalid @enderror">
                                <option value="">Select your service area</option>
                                @foreach(\App\Models\ServiceArea::where('is_active', true)->orderBy('name')->get() as $area)
                                    <option value="{{ $area->id }}" data-city="{{ $area->city_id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                           
                        </div>

                        <!-- 3. Experience Years -->
                        <div class="auth-form-group">
                            <label class="auth-form-label">Years of Experience</label>
                            <input type="number" 
                                   name="experience_years" 
                                   class="auth-form-control @error('experience_years') is-invalid @enderror" 
                                   value="{{ old('experience_years', 0) }}" 
                                   min="0" max="50" 
                                   placeholder="e.g. 3">
                          
                        </div>

                        <!-- 4. Services Selection (Grouped by Category or Listed cleanly) -->
                        <div class="auth-form-group">
                            <label class="auth-form-label">Select Services (Choose multiple)</label>
                            <div class="p-2 border rounded" style="max-height: 180px; overflow-y: auto; background: #f9f9f9;">
                                @foreach(\App\Models\Service::orderBy('name')->get() as $service)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="services[]" 
                                               value="{{ $service->id }}" 
                                               id="service_{{ $service->id }}"
                                               {{ (is_array(old('services')) && in_array($service->id, old('services'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service_{{ $service->id }}">
                                            {{ $service->name }} 
                                            @if(isset($service->category))
                                                <small class="text-muted">({{ $service->category->name }})</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                          
                        </div>

                        <!-- 5. Bio / Expertise -->
                        <div class="auth-form-group">
                            <label class="auth-form-label">Bio / Expertise Summary</label>
                            <textarea name="bio" class="auth-form-control @error('bio') is-invalid @enderror" rows="3" placeholder="Briefly describe your experience">{{ old('bio') }}</textarea>
                           
                    </div>
                    <!-- NRC Front Image -->
<div class="auth-form-group">
    <label class="auth-form-label" for="nrc_front_image">
        NRC Front Photo
    </label>
    <input
        type="file"
        name="nrc_front_image"
        id="nrc_front_image"
        class="auth-form-control @error('nrc_front_image') is-invalid @enderror"
        accept="image/jpeg,image/png,image/webp"
    >
    @error('nrc_front_image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- NRC Back Image -->
<div class="auth-form-group">
    <label class="auth-form-label" for="nrc_back_image">
        NRC Back Photo
    </label>
    <input
        type="file"
        name="nrc_back_image"
        id="nrc_back_image"
        class="auth-form-control @error('nrc_back_image') is-invalid @enderror"
        accept="image/jpeg,image/png,image/webp"
    >
    @error('nrc_back_image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
                    </div>
                    <button type="submit" class="auth-btn mt-3">
                        Register
                    </button>

                </form>
                <!-- REGISTER FORM END -->

                <!-- Login Link -->
                <p class="auth-footer mt-3">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="auth-link">Login</a>
                </p>

            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerRadio = document.querySelector('input[name="role"][value="customer"]');
        const providerRadio = document.querySelector('input[name="role"][value="provider"]');
        const providerFields = document.getElementById('provider-specific-fields');

        function toggleProviderFields() {
            if (providerRadio.checked) {
                providerFields.style.display = 'block';
            } else {
                providerFields.style.display = 'none';
            }
        }

        if (customerRadio && providerRadio && providerFields) {
            customerRadio.addEventListener('change', toggleProviderFields);
            providerRadio.addEventListener('change', toggleProviderFields);
            toggleProviderFields();
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const citySelect = document.getElementById('city_id');
        const areaSelect = document.getElementById('area_id');
        const areaOptions = areaSelect ? Array.from(areaSelect.options) : [];

        function filterAreas() {
            if (!citySelect || !areaSelect) return;
            const selectedCityId = citySelect.value;
            
            // Clear current options except default
            areaSelect.innerHTML = '<option value="">Select your service area</option>';

            areaOptions.forEach(option => {
                if (!option.value) return;
                if (option.getAttribute('data-city') === selectedCityId) {
                    areaSelect.appendChild(option);
                }
            });
        }

        if (citySelect) {
            citySelect.addEventListener('change', filterAreas);
            // Run on load if old value exists
            if (citySelect.value) {
                const oldAreaId = "{{ old('area_id') }}";
                filterAreas();
                if (oldAreaId) {
                    areaSelect.value = oldAreaId;
                }
            }
        }
    });
</script>
@endpush

@endsection