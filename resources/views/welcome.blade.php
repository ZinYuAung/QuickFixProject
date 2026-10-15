<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>QuickFix - Home Services Made Easy</title>

        <!-- Bootstrap -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >

        <!-- Font Awesome -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        >

        <!-- QuickFix CSS -->
        <link
            rel="stylesheet"
            href="{{ asset('css/quickfix.css') }}"
        >

    </head>

    <body>


        <!-- =====================================================
     NAVBAR
===================================================== -->

        <nav class="navbar navbar-expand-lg quickfix-navbar">

            <div class="container">

                <!-- Logo -->

                <a class="navbar-brand quickfix-logo" href="#home">

                    <i class="fa-solid fa-wrench"></i>

                    <span>QuickFix</span>

                </a>


                <!-- Mobile Menu Button -->

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#quickfixNavbar"
                    aria-controls="quickfixNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >

                    <i class="fa-solid fa-bars"></i>

                </button>


                <!-- Navbar Menu -->

                <div
                    class="collapse navbar-collapse"
                    id="quickfixNavbar"
                >

                    <ul class="navbar-nav mx-auto">


                        <!-- Home -->

                        <li class="nav-item">

                            <a
                                class="nav-link active"
                                href="#home"
                            >
                                Home
                            </a>

                        </li>


                        <!-- Services -->

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#services"
                            >
                                Services
                            </a>

                        </li>


                        <!-- How It Works -->

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#how-it-works"
                            >
                                How It Works
                            </a>

                        </li>


                        <!-- About -->

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#about"
                            >
                                About
                            </a>

                        </li>

                    </ul>


                    <!-- Login / Register -->

                    <div class="navbar-actions">

                        <a
                            href="{{ url('/login') }}"
                            class="login-link"
                        >
                            Login
                        </a>

                        <a
                            href="{{ url('/register') }}"
                            class="register-btn"
                        >
                            Register
                        </a>
                        <!-- Dark / Light Mode -->
                        <button
                            type="button"
                            class="quickfix-theme-toggle"
                            id="themeToggle"
                            aria-label="Toggle dark mode"
                            title="Toggle dark mode"
                        >
                            <i
                                class="fa-solid fa-moon"
                                id="themeIcon"
                            ></i>
                        </button>
                    </div>

                </div>

            </div>

        </nav>



        <!-- =====================================================
     HOME / HERO
===================================================== -->

        <section
            class="hero-section"
            id="home"
        >

            <div class="container">

                <div class="row align-items-center">


                    <!-- Hero Text -->

                    <div class="col-lg-6">

                        <div class="hero-content">

                            <div class="hero-label">

                                <i class="fa-solid fa-sparkles"></i>

                                TRUSTED HOME SERVICES

                            </div>


                            <h1>

                                Home Services
                                <span>Made Easy</span>

                            </h1>


                            <p>

                                Find trusted professionals for plumbing,
                                electrical, cleaning, appliance repair,
                                and more — all in one place.

                            </p>


                            <div class="hero-buttons">

                                <a
                                    href="{{ url('/login') }}"
                                    class="primary-btn"
                                >

                                    <i class="fa-solid fa-magnifying-glass"></i>

                                    Find a Service

                                </a>


                                <a
                                    href="{{ url('/register') }}"
                                    class="secondary-btn"
                                >

                                    Get Started

                                    <i class="fa-solid fa-arrow-right"></i>

                                </a>

                            </div>

                        </div>

                    </div>



                    <!-- Hero Illustration -->

                    <div class="col-lg-6">

                        <div class="hero-illustration">


                            <div class="home-card">

                                <div class="roof"></div>

                                <div class="house-body">

                                    <div class="window"></div>

                                    <div class="door"></div>

                                </div>

                            </div>


                            <!-- Floating Icons -->

                            <div class="floating-icon icon-plumbing">

                                <i class="fa-solid fa-faucet-drip"></i>

                            </div>


                            <div class="floating-icon icon-electric">

                                <i class="fa-solid fa-bolt"></i>

                            </div>


                            <div class="floating-icon icon-cleaning">

                                <i class="fa-solid fa-broom"></i>

                            </div>


                            <div class="floating-icon icon-tools">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <!-- =====================================================
     FEATURES
===================================================== -->

        <section class="features-section">

            <div class="container">
                <div class="section-heading text-center mb-5">
                    <h2>Safe, Simple & Reliable Home Services</h2>
    
                </div>

                <div class="row g-4">


                    <div class="col-md-6 col-lg-3">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="fa-solid fa-user-shield"></i>

                            </div>

                            <h5>Verified Professionals</h5>

                            <p>
                                Work with trusted and approved
                                service providers.
                            </p>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg-3">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="fa-solid fa-credit-card"></i>

                            </div>

                            <h5>Secure Payments</h5>

                            <p>
                                Simple and secure payment options
                                for your services.
                            </p>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg-3">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="fa-solid fa-location-dot"></i>

                            </div>

                            <h5>Real-time Tracking</h5>

                            <p>
                                Track your service provider
                                while they are on the way.
                            </p>

                        </div>

                    </div>


                    <div class="col-md-6 col-lg-3">

                        <div class="feature-card">

                            <div class="feature-icon">

                                <i class="fa-solid fa-headset"></i>

                            </div>

                            <h5>24/7 Support</h5>

                            <p>
                                Get help whenever you need
                                our support.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>



<!-- =====================================================
     SERVICES
===================================================== -->

<section class="services-section" id="services">
    <div class="container">

        <div class="section-heading text-center">
            <div class="section-label">
                OUR SERVICES
            </div>

            <h2>Popular Home Services</h2>

            <p>
                Professional help for everything
                your home needs.
            </p>
        </div>

        <div class="row g-4 mt-3">

            @forelse ($popularServices as $service)
                <div class="col-md-6 col-lg-4">
                    <a
                        href="{{ url('/login') }}"
                        class="service-card-link"
                    >
                        <div class="service-card">

                            <div class="service-icon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>

                            <h5>{{ $service->name }}</h5>

                            <p>
                                {{ $service->description
                                    ?: 'Professional home service for your needs.' }}
                            </p>

                            <div class="service-price">
                                {{ number_format((float) $service->base_price) }}
                                MMK
                            </div>

                            <span>
                                Explore Service
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>

                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No services are available at the moment.</p>
                </div>
            @endforelse

        </div>
    </div>
</section>

<!-- =====================================================
     HOW IT WORKS
===================================================== -->

<section class="how-it-works-section" id="how-it-works">
    <div class="container">

        <div class="section-heading text-center">
            <div class="section-label">
              
            </div>

            <h2>How It Works</h2>

            <p>
                Book trusted home service professionals
                in just a few simple steps.
            </p>
        </div>

        <div class="row g-4 mt-3">

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <h5>1. Choose a Service</h5>

                    <p>
                        Browse available home services
                        and select what you need.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <h5>2. Book a Service</h5>

                    <p>
                        Choose your provider, location,
                        and preferred schedule.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>

                    <h5>3. Get Confirmation</h5>

                    <p>
                        Wait for the service provider
                        to respond to your booking.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-card text-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-house-circle-check"></i>
                    </div>

                    <h5>4. Get the Job Done</h5>

                    <p>
                        Receive your home service and
                        complete the booking.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>


        <!-- =====================================================
     ABOUT
===================================================== -->

        <section
            class="about-section"
            id="about"
        >

            <div class="container">

                <div class="row align-items-center">


                    <div class="col-lg-6">

                        <div class="about-content">

                            <div class="section-label">
                                ABOUT QUICKFIX
                            </div>

                            <h2>
                                Making Home Services Simple
                            </h2>

                            <p>
                                QuickFix connects customers with
                                trusted professionals for reliable
                                home services.
                            </p>

                            <p>
                                From plumbing and electrical work
                                to cleaning and appliance repair,
                                everything can be managed from
                                one simple platform.
                            </p>

                        </div>

                    </div>


                    <div class="col-lg-6">

                        <div class="about-box">

                            <i class="fa-solid fa-house-circle-check"></i>

                            <h4>
                                One Platform.
                                Many Solutions.
                            </h4>

                            <p>
                                Find, book and track trusted
                                home service professionals.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>



        <!-- =====================================================
     CTA
===================================================== -->

        <section class="cta-section">

            <div class="container">

                <div class="cta-box text-center">

                    <h2>
                        Need Help at Home?
                    </h2>

                    <p>
                        Find a trusted professional today.
                    </p>

                    <a
                        href="{{ url('/login') }}"
                        class="primary-btn"
                    >

                        Find a Service

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </section>



        <!-- =====================================================
     FOOTER
===================================================== -->

        <footer class="quickfix-footer">

            <div class="container">

                <div class="row g-4">


                    <div class="col-lg-5">

                        <a
                            href="#home"
                            class="quickfix-logo"
                        >

                            <i class="fa-solid fa-wrench"></i>

                            <span>QuickFix</span>

                        </a>

                        <p>
                            Making home services easier,
                            faster and more reliable.
                        </p>

                    </div>


                    <div class="col-6 col-lg-3">

                        <h6>Quick Links</h6>

                        <a href="#home">Home</a>

                        <a href="#services">Services</a>

                        <a href="#how-it-works">
                            How It Works
                        </a>

                        <a href="#about">About</a>

                    </div>


                    <div class="col-6 col-lg-4">

                        <h6>Contact</h6>

                        <p>
                            <i class="fa-solid fa-phone"></i>
                            +95 9 123 456 789
                        </p>

                        <p>
                            <i class="fa-solid fa-envelope"></i>
                            support@quickfix.com
                        </p>

                    </div>

                </div>


                <div class="footer-bottom">

                    <p>
                        © 2026 QuickFix. All rights reserved.
                    </p>

                </div>

            </div>

        </footer>



        <!-- Bootstrap JS -->

        <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


        <!-- QuickFix JS -->

        <script
    src="{{ asset('js/quickfix.js') }}"
></script>


    </body>

</html>