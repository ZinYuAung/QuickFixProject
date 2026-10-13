<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProviderNotificationController extends Controller
{
    /**
     * Display provider notifications.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();

        return view(
            'provider.notifications',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }


    /**
     * Get unread notifications for AJAX polling.
     *
     * Badge = ALL unread notifications.
     * Dropdown = latest 10 unread notifications.
     */
    public function unread()
    {
        /*
         * Count ALL unread notifications.
         */
        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();


        /*
         * Get only latest 10 unread notifications
         * for the dropdown.
         */
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get();


        return response()->json([

            'success' => true,

            /*
             * REAL total unread count.
             */
            'count' => $unreadCount,

            /*
             * Latest 10 unread notifications.
             */
            'notifications' =>
                $notifications->map(function ($notification) {

                    $data = $notification->data;

                    return [

                        'id' =>
                            $notification->id,

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

'created_at' =>
    $notification->created_at
        ->diffForHumans(),

                    ];

                })
                ->values(),

        ]);
    }


    /**
     * Mark one notification as read.
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


        /*
         * Get the NEW unread count
         * after marking this notification as read.
         */
        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();


        return response()->json([

            'success' => true,

            'unreadCount' => $unreadCount,

        ]);
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);


        /*
         * All unread notifications are now read.
         */
        return response()->json([

            'success' => true,

            'unreadCount' => 0,

        ]);
    }


    /**
     * Delete one notification.
     */
    public function destroy(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();


        $notification->delete();


        /*
         * Get the NEW unread count
         * after deleting the notification.
         */
        $unreadCount = auth()->user()
            ->unreadNotifications()
            ->count();


        return response()->json([

            'success' => true,

            'unreadCount' => $unreadCount,

        ]);
    }
}
