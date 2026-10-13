<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    /**
     * =====================================================
     * CUSTOMER - NOTIFICATIONS
     * =====================================================
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();

        return view(
            'customer.notifications',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }
    /**
     * =====================================================
     * GET UNREAD NOTIFICATIONS - AJAX POLLING
     * =====================================================
     */
    public function unread()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,

            'count' => auth()->user()
                ->unreadNotifications()
                ->count(),

            'notifications' => $notifications->map(function ($notification) {

                $data = $notification->data;

               return [
    'id' => $notification->id,

    'title' =>
        $data['title']
        ?? 'Notification',

    'message' =>
        $data['message']
        ?? '',

    'type' =>
        $data['type']
        ?? 'booking_update',

    'booking_id' =>
        $data['booking_id']
        ?? null,

    'payment_id' =>
        $data['payment_id']
        ?? null,

    'rejection_reason' =>
        $data['rejection_reason']
        ?? null,

    'created_at' =>
        $notification->created_at
            ->diffForHumans(),
];
            
            }),
        ]);
    }

    /**
     * =====================================================
     * MARK ONE NOTIFICATION AS READ
     * =====================================================
     */
    public function markAsRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }


    /**
     * =====================================================
     * MARK ALL NOTIFICATIONS AS READ
     * =====================================================
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
            'unreadCount' => 0,
        ]);
    }
    public function destroy(string $id)
{
    $notification = auth()->user()
        ->notifications()
        ->where('id', $id)
        ->firstOrFail();

    $notification->delete();

    return response()->json([
        'success' => true,
        'message' => 'Notification deleted successfully.',
    ]);
}
}