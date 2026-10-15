<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerChatController extends Controller
{
    /**
     * Display customer-provider chats.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get providers who have bookings with this customer
        $providerIds = \App\Models\Booking::whereHas('customer', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->pluck('provider_id')
            ->unique();

        $providers = Provider::with('user')
            ->whereIn('id', $providerIds)
            ->get();

        // Selected provider
        $activeProviderId = $request->query('provider_id');

        $activeProvider = null;
        $messages = collect();

        if ($activeProviderId) {

    // Only allow providers who have bookings with this customer
    $activeProvider = Provider::with('user')
        ->whereIn('id', $providerIds)
        ->find($activeProviderId);

            if ($activeProvider && $activeProvider->user_id) {

                $providerUserId = $activeProvider->user_id;

                // Mark provider's unread messages as read
                Chat::where('sender_id', $providerUserId)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true
                    ]);

                // Get messages between this customer and provider
                $messages = Chat::where(function ($query) use ($userId, $providerUserId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $providerUserId);
                })
                    ->orWhere(function ($query) use ($userId, $providerUserId) {
                        $query->where('sender_id', $providerUserId)
                            ->where('receiver_id', $userId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('customer.chats', compact(
            'providers',
            'activeProvider',
            'messages'
        ));
    }
/**
 * Check whether the customer has a booking with this provider.
 */
private function hasBookingWithProvider($userId, $providerId)
{
    return \App\Models\Booking::where('provider_id', $providerId)
        ->whereHas('customer', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->exists();
}
    /**
     * Send a message to a provider.
     */
    public function store(Request $request, $provider)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $providerModel = Provider::findOrFail($provider);
        if (!$this->hasBookingWithProvider(Auth::id(), $providerModel->id)) {
    abort(403, 'You can only chat with providers you have booked.');
}

        // Provider must have a user account
        if (!$providerModel->user_id) {
            abort(404);
        }

        $chat = Chat::create([
            'booking_id' => null,
            'sender_id' => Auth::id(),
            'receiver_id' => $providerModel->user_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $chat->id,
                    'sender_id' => $chat->sender_id,
                    'receiver_id' => $chat->receiver_id,
                    'message' => $chat->message,
                    'time' => $chat->created_at->format('g:i A'),
                ]
            ]);
        }

        return redirect()->route('customer.chats', [
            'provider_id' => $providerModel->id
        ]);
    }
    public function fetchMessages(Request $request, $provider)
    {
        $userId = Auth::id();

        $providerModel = Provider::findOrFail($provider);

       // Check whether this customer has a booking with this provider
if (!$this->hasBookingWithProvider($userId, $providerModel->id)) {
    abort(403, 'You can only access chats with providers you have booked.');
}

if (!$providerModel->user_id) {
    abort(404);
}

        $providerUserId = $providerModel->user_id;

        // Get messages between customer and provider
        $messages = Chat::where(function ($query) use ($userId, $providerUserId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $providerUserId);
        })
            ->orWhere(function ($query) use ($userId, $providerUserId) {
                $query->where('sender_id', $providerUserId)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('id', 'asc')
            ->get();

        // Mark provider messages as read
        Chat::where('sender_id', $providerUserId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true
            ]);

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'sender_id' => $chat->sender_id,
                    'receiver_id' => $chat->receiver_id,
                    'message' => $chat->message,
                    'time' => $chat->created_at->format('g:i A'),
                ];
            })->values(),
        ]);
    }
    public function unreadCounts()
    {
        $userId = Auth::id();

        $counts = Chat::where('receiver_id', $userId)
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->pluck('unread_count', 'sender_id');

        return response()->json([
            'success' => true,
            'counts' => $counts,
            'total' => $counts->sum(),
        ]);
    }
    public function create()
    {
    }

    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}