<?php
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCityController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminProviderController;
use App\Http\Controllers\Admin\AdminServiceAreaController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\CustomerNotificationController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\CustomerChatController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\CustomerReviewController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\ProviderEarningsController;
use App\Http\Controllers\ProviderNotificationController;
use App\Http\Controllers\ProviderPaymentController;
use App\Http\Controllers\ProviderReviewController;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $popularServices = Service::where('is_active', true)
        ->orderBy('name')
        ->take(6)
        ->get();

    return view('welcome', compact('popularServices'));
});

Auth::routes();

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

//customer routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get(
        '/services',
        [CustomerServiceController::class, 'index']
    )->name('services');
    Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    // Customer Change Password
    Route::post('/profile/password', [CustomerProfileController::class, 'changePassword'])
        ->name('profile.password');
    Route::get('/chats', [CustomerChatController::class, 'index'])->name('chats');
    Route::get(
        '/chats/unread-counts',
        [CustomerChatController::class, 'unreadCounts']
    )->name('chats.unread-counts');
    Route::post('/chats/{provider}/send', [CustomerChatController::class, 'store'])
        ->name('chats.store');
    Route::get(
        '/chats/{provider}/messages',
        [CustomerChatController::class, 'fetchMessages']
    )->name('chats.messages');

    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/create', [CustomerBookingController::class, 'create'])->name('booking.create');
    Route::post('/bookings', [CustomerBookingController::class, 'store'])->name('booking.store');
    Route::get(
        '/bookings/{id}/status',
        [CustomerBookingController::class, 'status']
    )->name('booking.status');
    Route::post(
        '/bookings/{id}/cancel',
        [CustomerBookingController::class, 'cancel']
    )->name('booking.cancel');

    Route::post(
        '/bookings/{id}/refund/verify',
        [CustomerBookingController::class, 'verifyRefund']
    )->name('booking.refund.verify');

    Route::post(
        '/bookings/{id}/review',
        [CustomerReviewController::class, 'store']
    )->name('review.store');
    // =====================================================
// CUSTOMER PAYMENTS
// =====================================================

    // Payment list
    Route::get(
        '/payments',
        [PaymentController::class, 'index']
    )->name('payments');



    // Payment form for selected booking
    Route::get(
        '/bookings/{id}/payment',
        [PaymentController::class, 'show']
    )->name('payment.show');
    Route::get(
        '/bookings/{id}/payment/status',
        [PaymentController::class, 'status']
    )->name('payment.status');

    // Store payment
    Route::post(
        '/bookings/{id}/payment',
        [PaymentController::class, 'store']
    )->name('payment.store');
    Route::get('/bookings/{id}', [CustomerBookingController::class, 'show'])->name('booking.detail');

    //noti
    Route::get(
        '/notifications',
        [CustomerNotificationController::class, 'index']
    )->name('notifications');

    Route::post(
        '/notifications/{id}/read',
        [CustomerNotificationController::class, 'markAsRead']
    )->name('notifications.read');
    Route::delete(
        '/notifications/{id}',
        [CustomerNotificationController::class, 'destroy']
    )->name('notifications.destroy');

    Route::post(
        '/notifications/read-all',
        [CustomerNotificationController::class, 'markAllAsRead']
    )->name('notifications.read-all');
    Route::get(
        '/notifications/unread',
        [CustomerNotificationController::class, 'unread']
    )->name('notifications.unread');
    Route::get('/browse-services', [
        CustomerServiceController::class,
        'browse'
    ])->name('services.browse');

    Route::get('/providers/{id}', [
        CustomerServiceController::class,
        'providerShow'
    ])->name('providers.show');
});

//provider routes
Route::middleware(['auth', 'role:provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
    Route::get(
        '/dashboard/booking-requests',
        [ProviderDashboardController::class, 'bookingRequests']
    )->name('dashboard.booking-requests');
    Route::get(
        '/dashboard/today-jobs',
        [ProviderDashboardController::class, 'todayJobs']
    )->name('dashboard.today-jobs');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');

    Route::post('/bookings/{id}/accept', [BookingController::class, 'accept'])->name('bookings.accept');
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{id}/go-to-site', [BookingController::class, 'goToSite'])->name('bookings.go-to-site');
    Route::post('/bookings/{booking}/refund', [BookingController::class, 'submitRefund'])->name('bookings.refund');


    Route::post('/bookings/{id}/arrive', [BookingController::class, 'arrive'])->name('bookings.arrive');
    Route::post('/bookings/{id}/start', [BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{id}/complete', [BookingController::class, 'complete'])->name('bookings.complete');

    Route::get('/profile', [ProviderDashboardController::class, 'profile'])->name('profile');
    Route::get('/profile/nrc/{side}', [ProviderDashboardController::class, 'nrcImage'])
    ->where('side', 'front|back')
    ->name('profile.nrc-image');
    Route::get('/profile/edit', [ProviderDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [ProviderDashboardController::class, 'updateProfile'])->name('profile.update');
    // Change password
    Route::post('/profile/password', [ProviderDashboardController::class, 'changePassword'])
        ->name('profile.password');

    // Account active / inactive
    Route::post('/profile/account-status', [ProviderDashboardController::class, 'updateAccountStatus'])
        ->name('profile.account-status');
    // Provider online / offline availability
    Route::post('/availability-status', [ProviderDashboardController::class, 'updateAvailability'])
        ->name('availability-status');
    Route::get('/jobs', [ProviderDashboardController::class, 'jobs'])->name('jobs');
    Route::get('/earnings', [ProviderEarningsController::class, 'index'])->name('earnings');
    Route::get('/chats', [ProviderDashboardController::class, 'chats'])->name('chats');
    Route::get(
        '/chats/unread-counts',
        [ProviderDashboardController::class, 'unreadCounts']
    )->name('chats.unread-counts');

    Route::post(
        '/chats/{customer}/send',
        [ProviderDashboardController::class, 'sendChat']
    )->name('chats.send');
    Route::get(
        '/chats/{customer}/messages',
        [ProviderDashboardController::class, 'fetchMessages']
    )->name('chats.messages');
    Route::get(
        '/reviews',
        [ProviderReviewController::class, 'index']
    )->name('reviews.index');

    Route::delete(
        '/reviews/{id}',
        [ProviderReviewController::class, 'destroy']
    )->name('reviews.destroy');
    /*
       |--------------------------------------------------------------------------
       | Payments
       |--------------------------------------------------------------------------
       */

    Route::get('/payments', [
        ProviderPaymentController::class,
        'index'
    ])->name('payments.index');
    Route::get(
        '/payments/check-updates',
        [ProviderPaymentController::class, 'checkUpdates']
    )->name('payments.check-updates');

    Route::get('/payments/{payment}', [
        ProviderPaymentController::class,
        'show'
    ])->name('payments.show');

    Route::post('/payments/{payment}/verify', [
        ProviderPaymentController::class,
        'verify'
    ])->name('payments.verify');

    Route::post('/payments/{payment}/reject', [
        ProviderPaymentController::class,
        'reject'
    ])->name('payments.reject');
    //niti
    Route::get('/notifications', [ProviderNotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/unread', [ProviderNotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [ProviderNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [ProviderNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [ProviderNotificationController::class, 'destroy'])->name('notifications.destroy');
});

//admin routes (Secured & Grouped)
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [AdminProfileController::class, 'update'])->name('profile.update');

    //customer
    Route::resource('customers', AdminCustomerController::class);
    Route::post('/customers/{id}/reactivate', [AdminCustomerController::class, 'reactivate'])->name('customers.reactivate');
    //provider
    Route::get(
        '/providers/{id}/nrc/{side}',
        [AdminProviderController::class, 'nrcImage']
    )->where([
                'id' => '[0-9]+',
                'side' => 'front|back',
            ])->name('providers.nrc-image');
    Route::resource('providers', AdminProviderController::class);
    Route::post('/providers/{id}/reactivate', [AdminProviderController::class, 'reactivate'])->name('providers.reactivate');
    Route::post('/providers/{id}/approve', [AdminProviderController::class, 'approve'])->name('providers.approve');
    Route::post('/providers/{id}/reject', [AdminProviderController::class, 'reject'])->name('providers.reject');
    //service
    Route::resource('services', AdminServiceController::class);
    Route::post('/services/{id}/reactivate', [AdminServiceController::class, 'reactivate'])->name('services.reactivate');
    //category
    Route::resource('categories', AdminCategoryController::class);
    Route::post('/categories/{id}/reactivate', [AdminCategoryController::class, 'reactivate'])->name('categories.reactivate');
    //booking
    //Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show']);
    //city
    Route::resource('cities', AdminCityController::class);
    Route::post('/cities/{id}/reactivate', [AdminCityController::class, 'reactivate'])->name('cities.reactivate');
    //service area
    Route::resource('service-areas', AdminServiceAreaController::class);
    Route::post('/service-areas/{id}/reactivate', [AdminServiceAreaController::class, 'reactivate'])->name('service-areas.reactivate');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');

});

//admin
// Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
// Route::get('/admin/customers', [AdminCustomerController::class,'index'])->name('admin.customers');
// Route::get('admin/providers', [AdminProviderController::class, 'index'])->name('admin.providers');
// Route::get('/admin/services', [AdminServiceController::class, 'index'])->name('admin.services');
// Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings');
// Route::get('/admin/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
// Route::get('/admin/cities', [AdminCityController::class, 'index'])->name('admin.cities');
// Route::get('/admin/service-areas', [AdminServiceAreaController::class, 'index'])->name('admin.service-areas');




//customer
// Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
// Route::get('/customer/profile', [CustomerProfileController::class, 'index'])->name('customer.profile');
// Route::get('/customer/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
// Route::put('/customer/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
// Route::get('/customer/chats',[CustomerChatController::class, 'index'])->name('customer.chats');
// Route::get('/customer/bookings', [CustomerBookingController::class, 'index'])->name('customer.bookings');
// Route::get('/customer/bookings/{id}', [CustomerBookingController::class, 'show'])->name('customer.booking.detail');
// Route::get('/customer/services', [CustomerServiceController::class, 'index'])->name('customer.services');

//provider
// Route::get('/provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');
// Route::get('/provider/bookings', [BookingController::class, 'index'])->name('provider.bookings');
// Route::get('/provider/profile', [ProviderDashboardController::class, 'profile'])->name('provider.profile');
// Route::get('/provider/profile/edit', [ProviderDashboardController::class, 'editProfile'])->name('provider.profile.edit');
// Route::put('/provider/profile',[ProviderDashboardController::class, 'updateProfile'])->name('provider.profile.update');
// Route::get('/provider/jobs', [ProviderDashboardController::class, 'jobs'])->name('provider.jobs');
// Route::get('/provider/earnings', [ProviderDashboardController::class, 'earnings'])->name('provider.earnings');
// Route::get('/provider/chats', [ProviderDashboardController::class, 'chats'])->name('provider.chats');

