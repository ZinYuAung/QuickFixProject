<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    QuickFix - Payment
</title>

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

<!-- QuickFix -->

<link
    rel="stylesheet"
    href="{{ asset('css/quickfix.css') }}"
>

<link
    rel="stylesheet"
    href="{{ asset('css/customer.css') }}"
>

<link
    rel="stylesheet"
    href="{{ asset('css/customer-payment.css') }}"
>

<link
    rel="stylesheet"
    href="{{ asset('css/customer-payment-form.css') }}"
>

</head>


@php

$isAccepted =
    $booking->status === 'accepted';

$isCompleted =
    $booking->status === 'completed';

$hasPrepaymentRequired =
    $requiredPrepayment > 0;

$prepaymentStatus =
    $payment?->payment_status;

$finalPaymentStatus =
    $payment?->final_payment_status;


/*
|--------------------------------------------------------------------------
| PREPAYMENT STATES
|--------------------------------------------------------------------------
*/

$prepaymentVerified =
    $prepaymentStatus === 'verified';

$prepaymentPending =
    $prepaymentStatus === 'pending' &&
    !empty($payment?->slip_image);

$prepaymentRejected =
    $prepaymentStatus === 'rejected';


/*
|--------------------------------------------------------------------------
| FINAL PAYMENT STATES
|--------------------------------------------------------------------------
*/

$finalPending =
    $isCompleted &&
    $finalPaymentStatus === 'pending' &&
    !empty($payment?->final_slip_image);

$finalRejected =
    $isCompleted &&
    $finalPaymentStatus === 'rejected';

$finalVerified =
    $isCompleted &&
    $finalPaymentStatus === 'verified';


/*
|--------------------------------------------------------------------------
| FINAL PAYMENT REQUIRED
|--------------------------------------------------------------------------
|
| Booking is completed
| Remaining amount is greater than zero
| Final payment is not verified
|
*/

$finalPaymentRequired =
    $isCompleted &&
    $remainingAmount > 0 &&
    $finalPaymentStatus !== 'verified';


/*
|--------------------------------------------------------------------------
| PREPAYMENT FORM
|--------------------------------------------------------------------------
*/

$showPrepaymentForm =
    $isAccepted &&
    $hasPrepaymentRequired &&
    (
        !$payment ||
        $prepaymentRejected ||
        (
            $prepaymentStatus === 'pending' &&
            empty($payment?->slip_image)
        )
    );


/*
|--------------------------------------------------------------------------
| FINAL PAYMENT FORM
|--------------------------------------------------------------------------
*/

$showFinalPaymentForm =
    $finalPaymentRequired &&
    (
        (
            $finalPaymentStatus === 'pending' &&
            empty($payment?->final_slip_image)
        )
        ||
        $finalRejected
    );


/*
|--------------------------------------------------------------------------
| DISPLAY SLIP
|--------------------------------------------------------------------------
*/

$displaySlip = null;

$displaySlipType = null;

if (
    $isCompleted &&
    $payment?->final_slip_image
) {

    $displaySlip =
        $payment->final_slip_image;

    $displaySlipType =
        'Final Payment Slip';

}
elseif (
    $payment?->slip_image
) {

    $displaySlip =
        $payment->slip_image;

    $displaySlipType =
        'Prepayment Slip';
}

@endphp


<body
    data-service-amount="{{ $serviceAmount }}"
    data-prepaid-amount="{{ $prepaidAmount }}"
    data-remaining-amount="{{ $remainingAmount }}"
    data-booking-id="{{ $booking->id }}"
>


<div
    class="customer-layout"
    id="customerLayout"
>


<!-- =====================================================
     SIDEBAR
====================================================== -->

<aside
    class="customer-sidebar"
    id="customerSidebar"
>

<div class="customer-sidebar-header">

    <a
        href="{{ url('/') }}"
        class="customer-logo"
    >

        <span class="customer-logo-icon">

            <i class="fa-solid fa-wrench"></i>

        </span>

        <span class="customer-logo-text">

            QuickFix

        </span>

    </a>


    <button
        type="button"
        class="customer-sidebar-toggle"
        id="customerSidebarToggle"
        aria-label="Toggle sidebar"
        title="Toggle sidebar"
    >

        <i class="fa-solid fa-bars"></i>

    </button>

</div>


<!-- Profile -->

<div class="customer-profile">

    <div class="customer-avatar">

        <i class="fa-solid fa-user"></i>

    </div>


    <div class="customer-profile-info">

        <h4>

            {{ $customer->user?->name ?? auth()->user()->name ?? 'Customer' }}

        </h4>

        <span>

            Customer

        </span>

    </div>

</div>


<!-- Menu -->

<nav class="customer-sidebar-menu">


    <a
        href="{{ route('customer.dashboard') }}"
        class="customer-menu-link"
    >

        <i class="fa-solid fa-house"></i>

        <span class="customer-menu-text">

            Dashboard

        </span>

    </a>


    <a
        href="{{ route('customer.services') }}"
        class="customer-menu-link"
    >

        <i class="fa-solid fa-screwdriver-wrench"></i>

        <span class="customer-menu-text">

            Services

        </span>

    </a>


    <a
        href="{{ route('customer.bookings') }}"
        class="customer-menu-link"
    >

        <i class="fa-solid fa-calendar-check"></i>

        <span class="customer-menu-text">

            Bookings

        </span>

    </a>


    <a
        href="{{ route('customer.payments') }}"
        class="customer-menu-link active"
    >

        <i class="fa-solid fa-credit-card"></i>

        <span class="customer-menu-text">

            Payment

        </span>

    </a>


    <a
        href="{{ route('customer.chats') }}"
        class="customer-menu-link"
    >

        <i class="fa-solid fa-comments"></i>

        <span class="customer-menu-text">

            Chat

        </span>

        <span class="customer-chat-badge">

            2

        </span>

    </a>


    <a
        href="{{ route('customer.profile') }}"
        class="customer-menu-link"
    >

        <i class="fa-solid fa-user"></i>

        <span class="customer-menu-text">

            Profile

        </span>

    </a>

</nav>


<!-- Logout -->

<div class="customer-sidebar-bottom">

    <form
        action="{{ route('logout') }}"
        method="POST"
    >

        @csrf

        <button
            type="submit"
            class="customer-menu-link customer-logout"
        >

            <i class="fa-solid fa-right-from-bracket"></i>

            <span class="customer-menu-text">

                Logout

            </span>

        </button>

    </form>

</div>


</aside>


<!-- Overlay -->

<div
    class="customer-overlay"
    id="customerOverlay"
></div>


<!-- =====================================================
     MAIN
====================================================== -->

<main class="customer-main">


<!-- Header -->

<header class="customer-header">

    <div class="customer-header-left">


        <button
            type="button"
            class="customer-mobile-menu"
            id="customerMobileMenu"
            aria-label="Open menu"
            title="Open menu"
        >

            <i class="fa-solid fa-bars"></i>

        </button>


        <div>

            <h1>

                Payment

            </h1>

            <p>

                Complete your booking payment

            </p>

        </div>

    </div>


    <div class="customer-header-actions">

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

</header>


<!-- =================================================
     PAYMENT CONTENT
================================================== -->

<section class="customer-payment-form-content">


    <!-- Back -->

    <div class="payment-form-topbar">

        <a
            href="{{ route('customer.payments') }}"
            class="payment-back-button"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Back to Payments

        </a>

    </div>


    <!-- =================================================
         BOOKING HEADER
    ================================================== -->

    <div class="payment-booking-header">

        <div>

            <span>

                Booking

            </span>

            <h2>

                #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}

            </h2>

        </div>


        @if($booking->status)

            <span class="booking-status-pill">

                {{ ucwords(str_replace('_', ' ', $booking->status)) }}

            </span>

        @endif

    </div>


    <div class="payment-form-grid">


        <!-- =================================================
             LEFT
        ================================================= -->

        <div class="payment-form-main">


            <!-- =================================================
                 BOOKING INFORMATION
            ================================================= -->

            <div class="payment-form-card">

                <div class="payment-form-card-header">

                    <div>

                        <h3>

                            Booking Information

                        </h3>

                        <p>

                            Details of your selected service.

                        </p>

                    </div>

                    <i class="fa-solid fa-calendar-check"></i>

                </div>


                <div class="payment-info-grid">


                    <div class="payment-info-item">

                        <span>

                            Service

                        </span>

                        <strong>

                            {{ $booking->service_name ?? 'Service Booking' }}

                        </strong>

                    </div>


                    <div class="payment-info-item">

                        <span>

                            Provider

                        </span>

                        <strong>

                            {{ $booking->provider?->user?->name ?? 'Not Assigned' }}

                        </strong>

                    </div>


                    <div class="payment-info-item">

                        <span>

                            City

                        </span>

                        <strong>

                            {{ $booking->area?->city?->name ?? '—' }}

                        </strong>

                    </div>


                    <div class="payment-info-item">

                        <span>

                            Scheduled Date

                        </span>

                        <strong>

                            {{ $booking->scheduled_at?->format('d M Y') ?? '—' }}

                        </strong>

                    </div>


                    <div class="payment-info-item">

                        <span>

                            Scheduled Time

                        </span>

                        <strong>

                            {{ $booking->scheduled_at?->format('h:i A') ?? '—' }}

                        </strong>

                    </div>


                </div>

            </div>


            <!-- =================================================
                 AJAX STATUS MESSAGE
            ================================================== -->

            <div
                id="paymentStatusMessage"
                class="payment-form-status-message"
                style="display: none;"
            >

                <div class="payment-form-status-icon">

                    <i
                        id="paymentStatusMessageIcon"
                        class="fa-solid fa-clock"
                    ></i>

                </div>


                <div>

                    <strong id="paymentStatusMessageTitle">

                        Payment Status

                    </strong>

                    <div id="paymentStatusMessageText">

                        Checking payment status...

                    </div>

                </div>

            </div>


            <!-- =================================================
                 PENDING BOOKING
            ================================================== -->

            @if(!$isAccepted && !$isCompleted)

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <strong>

                            Waiting for Provider Acceptance

                        </strong>

                        <div>

                            Your booking is currently

                            <strong>

                                {{ ucwords(str_replace('_', ' ', $booking->status)) }}

                            </strong>.

                            Payment will become available after the provider accepts your booking.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 ACCEPTED - NO PREPAYMENT
            ================================================== -->

            @if(
                $isAccepted &&
                !$hasPrepaymentRequired
            )

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <strong>

                            No Prepayment Required

                        </strong>

                        <div>

                            Your booking total is below
                            30,000 MMK, so no prepayment is required.
                            You can pay the remaining

                            <strong>

                                {{ number_format($remainingAmount, 2) }} MMK

                            </strong>

                            after the service is completed.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 PREPAYMENT VERIFIED
            ================================================== -->

            @if(
                $isAccepted &&
                $hasPrepaymentRequired &&
                $prepaymentVerified
            )

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <strong>

                            Prepayment Verified

                        </strong>

                        <div>

                            Your

                            <strong>

                                {{ number_format($prepaidAmount, 2) }} MMK

                            </strong>

                            prepayment has been verified by the provider.
                            You can now wait for the service.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 PREPAYMENT PENDING
            ================================================== -->

            @if(
                $isAccepted &&
                $hasPrepaymentRequired &&
                $prepaymentPending
            )

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <strong>

                            Prepayment Verification Pending

                        </strong>

                        <div>

                            Your

                            <strong>

                                {{ number_format($prepaidAmount, 2) }} MMK

                            </strong>

                            prepayment has been submitted.
                            Please wait for the provider to verify it.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 FINAL PAYMENT REQUIRED
            ================================================== -->

            @if($finalPaymentRequired && !$finalPending && !$finalRejected)

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-wallet"></i>

                    </div>

                    <div>

                        <strong>

                            Final Payment Required

                        </strong>

                        <div>

                            Your service has been completed.
                            Please pay the remaining

                            <strong>

                                {{ number_format($remainingAmount, 2) }} MMK

                            </strong>

                            to complete your payment.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 FINAL PAYMENT VERIFIED
            ================================================== -->

            @if(
                $isCompleted &&
                $finalVerified
            )

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <strong>

                            Fully Paid

                        </strong>

                        <div>

                            Your final payment has been verified.
                            This booking is fully paid.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 FINAL PAYMENT PENDING
            ================================================== -->

            @if(
                $isCompleted &&
                $finalPending
            )

                <div class="payment-form-status-message">

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-clock"></i>

                    </div>

                    <div>

                        <strong>

                            Final Payment Verification Pending

                        </strong>

                        <div>

                            Your final payment has been submitted.
                            Please wait for the provider to verify
                            your payment.

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 FINAL PAYMENT REJECTED
            ================================================== -->

            @if(
                $isCompleted &&
                $finalRejected
            )

                <div
                    class="payment-form-status-message"
                >

                    <div class="payment-form-status-icon">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div>

                        <strong>

                            Final Payment Rejected

                        </strong>

                        <div>

                            Your final payment was rejected by the provider.
                            Please review the rejection reason and submit
                            your payment again.

                            @if($payment?->final_rejection_reason)

                                <br>

                                <strong>

                                    Reason:

                                </strong>

                                {{ $payment->final_rejection_reason }}

                            @endif

                        </div>

                    </div>

                </div>

            @endif


            <!-- =================================================
                 PAYMENT FORM
                 ONLY ONE FORM - AJAX CONTROLLED
            ================================================== -->

            <div
                id="paymentFormContainer"
                style="{{ ($showPrepaymentForm || $showFinalPaymentForm) ? '' : 'display: none;' }}"
            >

                <form
                    action="{{ route('customer.payment.store', $booking->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="paymentForm"
                >

                    @csrf


                    <div class="payment-form-card">


                        <!-- =================================================
                             FORM HEADER
                        ================================================== -->

                        <div class="payment-form-card-header">

                            <div>

                                <h3 id="paymentFormTitle">

                                    @if($showFinalPaymentForm)

                                        Final Payment Method

                                    @else

                                        Prepayment Method

                                    @endif

                                </h3>


                                <p id="paymentFormDescription">

                                    @if($showFinalPaymentForm)

                                        Pay the remaining amount for your completed service.

                                    @else

                                        Pay the required 10% prepayment.

                                    @endif

                                </p>

                            </div>


                            <i class="fa-solid fa-wallet"></i>

                        </div>


                        <!-- =================================================
                             VALIDATION ERRORS
                        ================================================== -->

                        @if($errors->any())

                            <div class="payment-form-error">

                                <i class="fa-solid fa-circle-exclamation"></i>

                                <div>

                                    @foreach($errors->all() as $error)

                                        <div>

                                            {{ $error }}

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                        <!-- =================================================
                             AJAX REJECTION MESSAGE
                        ================================================== -->

                        <div
                            id="paymentRejectedMessage"
                            class="payment-form-error payment-rejection-message"
                            style="display: none;"
                        >

                            <i class="fa-solid fa-circle-xmark"></i>


                            <div class="payment-rejection-content">

                                <strong id="paymentRejectedTitle">

                                    Payment Rejected

                                </strong>


                                <p class="payment-rejection-description">

                                    Your previous payment was rejected by the provider.

                                </p>


                                <div class="payment-rejection-reason">

                                    <span class="payment-rejection-reason-label">

                                        Rejection Reason

                                    </span>


                                    <p id="paymentRejectionReason">

                                        No rejection reason was provided.

                                    </p>

                                </div>


                                <p class="payment-rejection-help">

                                    Please check the reason above and submit your payment again.

                                </p>

                            </div>

                        </div>


                        <!-- =================================================
                             PAYMENT OPTIONS
                        ================================================== -->

                        <div class="payment-method-options">


                            <!-- =================================================
                                 COD
                            ================================================== -->

                            <label
                                class="payment-method-option"
                                id="codPaymentOption"
                                style="{{ $showFinalPaymentForm ? '' : 'display: none;' }}"
                            >

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="cod"
                                    id="paymentMethodCod"
                                    {{ old('payment_method', $payment?->payment_method) === 'cod'
                                        && $showFinalPaymentForm
                                        ? 'checked'
                                        : '' }}
                                >


                                <div class="payment-method-content">

                                    <div class="payment-method-icon cod">

                                        <i class="fa-solid fa-money-bill-wave"></i>

                                    </div>


                                    <div>

                                        <strong>

                                            Cash on Delivery

                                        </strong>

                                        <span>

                                            Pay the remaining amount in cash.

                                        </span>

                                    </div>

                                </div>


                                <i class="fa-solid fa-circle-check payment-method-check"></i>

                            </label>


                            <!-- =================================================
                                 KBZPAY
                            ================================================== -->

                            <label class="payment-method-option">

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="kbzpay"
                                    id="paymentMethodKbzpay"
                                    {{ old('payment_method', $payment?->payment_method) === 'kbzpay'
                                        ? 'checked'
                                        : (!$showFinalPaymentForm ? 'checked' : '') }}
                                >


                                <div class="payment-method-content">

                                    <div class="payment-method-icon kbzpay">

                                        <i class="fa-solid fa-mobile-screen-button"></i>

                                    </div>


                                    <div>

                                        <strong>

                                            KBZPay

                                        </strong>


                                        <span id="kbzpayMethodDescription">

                                            @if($showFinalPaymentForm)

                                                Pay the remaining amount using KBZPay.

                                            @else

                                                Pay the required prepayment using KBZPay.

                                            @endif

                                        </span>

                                    </div>

                                </div>


                                <i class="fa-solid fa-circle-check payment-method-check"></i>

                            </label>


                        </div>


                        <!-- =================================================
                             KBZPAY SECTION
                        ================================================== -->

                        <div
                            class="kbzpay-section"
                            id="kbzpaySection"
                        >

                            <div class="kbzpay-information">


                                <div class="kbzpay-information-icon">

                                    <i class="fa-solid fa-mobile-screen-button"></i>

                                </div>


                                <div>

                                    <strong id="kbzpayTitle">

                                        @if($showFinalPaymentForm)

                                            KBZPay Final Payment

                                        @else

                                            KBZPay Prepayment

                                        @endif

                                    </strong>


                                    <p id="kbzpayDescription">

                                        @if($showFinalPaymentForm)

                                            Please transfer the remaining amount and upload your payment slip.

                                        @else

                                            Please transfer the required prepayment amount and upload your payment slip.

                                        @endif

                                    </p>

                                </div>

                            </div>


                            <!-- =================================================
                                 PAYABLE AMOUNT
                            ================================================== -->

                            <div class="payable-amount-box">

                                <span id="payableAmountLabel">

                                    @if($showFinalPaymentForm)

                                        Remaining Payment

                                    @else

                                        Required Prepayment

                                    @endif

                                </span>


                                <strong>

                                    <span id="payableAmountValue">

                                        @if($showFinalPaymentForm)

                                            {{ number_format($remainingAmount, 2) }}

                                        @else

                                            {{ number_format($prepaidAmount, 2) }}

                                        @endif

                                    </span>

                                    MMK

                                </strong>

                            </div>


                            <!-- =================================================
                                 UPLOAD
                            ================================================== -->

                            <div class="payment-upload-section">

                                <label
                                    for="slip_image"
                                    class="payment-upload-label"
                                >

                                    <span>

                                        <i class="fa-solid fa-cloud-arrow-up"></i>


                                        <span id="slipUploadLabel">

                                            @if($showFinalPaymentForm)

                                                KBZPay Final Payment Slip

                                            @else

                                                KBZPay Prepayment Slip

                                            @endif

                                        </span>

                                    </span>


                                    <small>

                                        JPG, JPEG, PNG or WEBP — maximum 5MB

                                    </small>

                                </label>


                                <label
                                    for="slip_image"
                                    class="payment-upload-box"
                                >

                                    <i class="fa-solid fa-image"></i>


                                    <strong>

                                        Choose Payment Slip

                                    </strong>


                                    <span id="uploadFileName">

                                        No file selected

                                    </span>

                                </label>


                                <input
                                    type="file"
                                    name="slip_image"
                                    id="slip_image"
                                    accept=".jpg,.jpeg,.png,.webp"
                                >


                                @error('slip_image')

                                    <div class="payment-field-error">

                                        {{ $message }}

                                    </div>

                                @enderror

                            </div>

                        </div>


                        <!-- =================================================
                             SUBMIT
                        ================================================== -->

                        <div class="payment-form-submit">

                            <button
                                type="submit"
                                class="payment-submit-button"
                                id="paymentSubmitButton"
                            >

                                <i class="fa-solid fa-paper-plane"></i>


                                <span id="paymentSubmitText">

                                    @if($showFinalPaymentForm)

                                        Submit Final Payment

                                    @else

                                        Submit Prepayment

                                    @endif

                                </span>

                            </button>

                        </div>


                    </div>

                </form>

            </div>


            <!-- =================================================
                 EXISTING PREPAYMENT SLIP
            ================================================== -->

            @if(
                $payment &&
                $payment->slip_image &&
                !$isCompleted
            )

                <div class="existing-slip-card">

                    <div class="existing-slip-header">

                        <div>

                            <h4>

                                Prepayment Slip

                            </h4>

                            <span>

                                Your submitted 10% prepayment slip.

                            </span>

                        </div>


                        <button
                            type="button"
                            class="existing-slip-view"
                            data-bs-toggle="modal"
                            data-bs-target="#paymentSlipModal"
                        >

                            <i class="fa-solid fa-expand"></i>

                            View Full Image

                        </button>

                    </div>


                    <div class="existing-slip-image-wrapper">

                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($payment->slip_image) }}"
                            alt="Prepayment Slip"
                            class="existing-slip-image"
                        >

                    </div>

                </div>

            @endif


            <!-- =================================================
                 EXISTING FINAL SLIP
            ================================================== -->

            @if(
                $payment &&
                $payment->final_slip_image
            )

                <div class="existing-slip-card">

                    <div class="existing-slip-header">

                        <div>

                            <h4>

                                Final Payment Slip

                            </h4>

                            <span>

                                Your submitted remaining-payment slip.

                            </span>

                        </div>


                        <button
                            type="button"
                            class="existing-slip-view"
                            data-bs-toggle="modal"
                            data-bs-target="#paymentSlipModal"
                        >

                            <i class="fa-solid fa-expand"></i>

                            View Full Image

                        </button>

                    </div>


                    <div class="existing-slip-image-wrapper">

                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($payment->final_slip_image) }}"
                            alt="Final Payment Slip"
                            class="existing-slip-image"
                        >

                    </div>

                </div>

            @endif


        </div>


        <!-- =================================================
             RIGHT SUMMARY
        ================================================== -->

        <aside class="payment-form-sidebar">


            <!-- =================================================
                 PAYMENT SUMMARY
            ================================================== -->

            <div class="payment-summary-detail-card">


                <div class="payment-summary-detail-header">

                    <div>

                        <span>

                            Payment Summary

                        </span>

                        <h3>

                            Booking #{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}

                        </h3>

                    </div>


                    <i class="fa-solid fa-receipt"></i>

                </div>


                <div class="payment-summary-detail-body">


                    <!-- Service Amount -->

                    <div class="payment-summary-row">

                        <span>

                            Service Amount

                        </span>

                        <strong>

                            {{ number_format($serviceAmount, 2) }} MMK

                        </strong>

                    </div>


                    <!-- Prepayment -->

                    <div
                        class="payment-summary-row prepaid"
                        id="prepaidAmountRow"
                    >

                        <span>

                            Prepayment ({{ $prepaymentRate }}%)

                        </span>


                        <strong>

                            @if($hasPrepaymentRequired)

                                {{ number_format($prepaidAmount, 2) }} MMK

                            @else

                                0.00 MMK

                            @endif

                        </strong>

                    </div>


                    <!-- Remaining -->

                    <div class="payment-summary-row">

                        <span>

                            Remaining Amount

                        </span>

                        <strong>

                            {{ number_format($remainingAmount, 2) }} MMK

                        </strong>

                    </div>


                    <div class="payment-summary-divider"></div>


                    <!-- Required Payment -->

                    <div class="payment-summary-total">

                        <span>

                            @if($showPrepaymentForm)

                                Required Prepayment

                            @elseif($showFinalPaymentForm)

                                Required Final Payment

                            @elseif($finalPaymentRequired)

                                Required Final Payment

                            @elseif($finalVerified)

                                Payment Completed

                            @elseif(
                                $isAccepted &&
                                $prepaymentVerified
                            )

                                Prepayment Paid

                            @else

                                Current Payment

                            @endif

                        </span>


                        <strong>

                            <span id="summaryPayableAmount">

                                @if($showPrepaymentForm)

                                    {{ number_format($prepaidAmount, 2) }}

                                @elseif($showFinalPaymentForm)

                                    {{ number_format($remainingAmount, 2) }}

                                @elseif($finalPaymentRequired)

                                    {{ number_format($remainingAmount, 2) }}

                                @elseif(
                                    $isAccepted &&
                                    $prepaymentVerified
                                )

                                    {{ number_format($prepaidAmount, 2) }}

                                @elseif($finalVerified)

                                    {{ number_format($remainingAmount, 2) }}

                                @else

                                    0.00

                                @endif

                            </span>

                            MMK

                        </strong>

                    </div>


                </div>

            </div>


            <!-- =================================================
                 CURRENT STATUS CARD
            ================================================== -->

            <div class="payment-current-status-card">


                <div class="payment-current-status-icon">


                    @if(
                        $isCompleted &&
                        $finalVerified
                    )

                        <i class="fa-solid fa-circle-check"></i>


                    @elseif(
                        $isCompleted &&
                        $finalRejected
                    )

                        <i class="fa-solid fa-circle-xmark"></i>


                    @elseif(
                        $isCompleted &&
                        $finalPending
                    )

                        <i class="fa-solid fa-clock"></i>


                    @elseif(
                        $finalPaymentRequired
                    )

                        <i class="fa-solid fa-wallet"></i>


                    @elseif(
                        $prepaymentVerified
                    )

                        <i class="fa-solid fa-circle-check"></i>


                    @elseif(
                        $prepaymentRejected
                    )

                        <i class="fa-solid fa-circle-xmark"></i>


                    @elseif(
                        $prepaymentPending
                    )

                        <i class="fa-solid fa-clock"></i>


                    @elseif(
                        $isAccepted &&
                        !$hasPrepaymentRequired
                    )

                        <i class="fa-solid fa-circle-check"></i>


                    @else

                        <i class="fa-solid fa-wallet"></i>

                    @endif


                </div>


                <div>

                    <span>

                        Current Status

                    </span>


                    <strong>


                        @if(
                            $isCompleted &&
                            $finalVerified
                        )

                            Fully Paid


                        @elseif(
                            $isCompleted &&
                            $finalRejected
                        )

                            Final Payment Rejected


                        @elseif(
                            $isCompleted &&
                            $finalPending
                        )

                            Final Payment Pending


                        @elseif(
                            $finalPaymentRequired
                        )

                            Final Payment Required


                        @elseif(
                            $prepaymentVerified
                        )

                            Prepayment Verified


                        @elseif(
                            $prepaymentRejected
                        )

                            Prepayment Rejected


                        @elseif(
                            $prepaymentPending
                        )

                            Prepayment Pending


                        @elseif(
                            $isAccepted &&
                            !$hasPrepaymentRequired
                        )

                            No Prepayment Required


                        @elseif(
                            $isAccepted &&
                            $hasPrepaymentRequired
                        )

                            Prepayment Required


                        @else

                            Waiting for Acceptance

                        @endif


                    </strong>

                </div>

            </div>


        </aside>


    </div>


</section>

</main>

</div>


<!-- =========================================================
     SLIP MODAL
========================================================== -->

<div
    class="modal fade"
    id="paymentSlipModal"
    tabindex="-1"
    aria-hidden="true"
>


<div class="modal-dialog modal-dialog-centered modal-xl">

    <div class="modal-content payment-slip-modal">


        <div class="modal-header">

            <div>

                <h5 class="modal-title">

                    <i class="fa-solid fa-image"></i>

                    {{ $displaySlipType ?? 'Payment Slip' }}

                </h5>


                <span>

                    Booking #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}

                </span>

            </div>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
            ></button>

        </div>


        <div class="modal-body">

            @if($displaySlip)

                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($displaySlip) }}"
                    alt="{{ $displaySlipType ?? 'Payment Slip' }}"
                    class="payment-form-modal-image"
                >

            @endif

        </div>


        <div class="modal-footer">

            @if($displaySlip)

                <a
                    href="{{ \Illuminate\Support\Facades\Storage::url($displaySlip) }}"
                    target="_blank"
                    class="payment-slip-open-button"
                >

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    Open Full Image

                </a>

            @endif


            <button
                type="button"
                class="payment-modal-close-button"
                data-bs-dismiss="modal"
            >

                Close

            </button>

        </div>


    </div>

</div>


</div>


<!-- =========================================================
     JAVASCRIPT
========================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script src="{{ asset('js/quickfix.js') }}"></script>

<script src="{{ asset('js/customer.js') }}"></script>

<script src="{{ asset('js/customer-payment-form.js') }}"></script>

</body>

</html>
