@extends('layouts.app')

@section('title', 'Login - QuickFix')


@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/auth.css') }}"
    >

@endpush


@section('content')

    <div class="container-fluid p-0">

        <div class="row g-0 min-vh-100">


            <!-- =====================================================
                 LEFT SIDE
            ====================================================== -->

            <div
                class="col-5
                        d-flex
                        align-items-center
                        justify-content-center
                        auth-left-panel"
            >


                <div class="auth-left-content">


                    <!-- =================================================
                         QUICKFIX LOGO
                         CLICK → WELCOME / HOME
                    ================================================== -->

                    <a
                        href="{{ url('/') }}"
                        class="auth-logo-link"
                    >


                        <h2>

                            <i class="fa-solid fa-wrench"></i>

                            QuickFix

                        </h2>


                    </a>


                    <!-- =================================================
                         WELCOME MESSAGE
                    ================================================== -->

                    <h1 class="auth-welcome">

                        Welcome Back!

                    </h1>


                    <p>

                        Login to your account
                        to continue.

                    </p>


                    <!-- =================================================
                         ICON
                    ================================================== -->

                    <i class="fa-solid fa-house auth-left-icon"></i>


                </div>


            </div>



            <!-- =====================================================
                 RIGHT SIDE
            ====================================================== -->

            <div
                class="col-7
                        d-flex
                        align-items-center
                        justify-content-center
                        auth-right-panel"
            >


                <div class="auth-form-container">


                    <!-- =================================================
                         TITLE
                    ================================================== -->

                    <h2 class="auth-title">

                        Login

                    </h2>


                    <p class="auth-subtitle">

                        Enter your email and password

                    </p>

                    <!-- success MESSAGE -->
                    @if (session('success'))
                        <div class="custom-alert custom-alert-success" role="alert">
                            <i class="fa-solid fa-circle-check custom-alert-icon"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif

                    <!-- =================================================
                         LOGIN FORM
                    ================================================== -->

                    <form
                        method="POST"
                        action="{{ route('login') }}"
                    >

                        @csrf


                        <!-- =============================================
                             EMAIL
                        ============================================== -->

                        <div class="auth-form-group">


                            <label class="auth-form-label">

                                Email Address

                            </label>


                            <input
                                type="email"
                                name="email"
                                class="auth-form-control"
                                placeholder="you@example.com"
                            >


                        </div>



                        <!-- =============================================
                             PASSWORD
                        ============================================== -->

                        <div class="auth-form-group">


                            <label class="auth-form-label">

                                Password

                            </label>


                            <input
                                type="password"
                                name="password"
                                class="auth-form-control"
                                placeholder="Enter your password"
                            >


                        </div>



                        <!-- =============================================
                             REMEMBER + FORGOT
                        ============================================== -->

                        <div class="auth-options">


                            <label class="auth-remember">


                                <input
                                    type="checkbox"
                                    name="remember"
                                >


                                Remember me


                            </label>



                            <a
                                href="#"
                                class="auth-link"
                            >

                                Forgot password?

                            </a>


                        </div>



                        <!-- =============================================
                             LOGIN BUTTON
                        ============================================== -->

                        <button
                            type="submit"
                            class="auth-btn"
                        >


                            Login


                        </button>


                    </form>



                    <!-- =================================================
                         REGISTER LINK
                    ================================================== -->

                    <p class="auth-footer">


                        Don't have an account?


                        <a
                            href="{{ url('/register') }}"
                            class="auth-link"
                        >

                            Register

                        </a>


                    </p>


                </div>


            </div>


        </div>

    </div>

@endsection