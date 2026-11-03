<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InboxMessage;
use App\Models\Senderid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all sender IDs belonging to this user
        $userSenders = Senderid::where('user_id', $userId)->pluck('sender')->all();

        $query = InboxMessage::where(function ($q) use ($userId, $userSenders) {
            $q->where('user_id', $userId);
            if (!empty($userSenders)) {
                $q->orWhereIn('receiver', $userSenders);
            }
        })->latest('received_at');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('sender', 'like', "%{$search}%")
                  ->orWhere('receiver', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        $totalCount  = (clone $query)->count();
        $unreadCount = (clone $query)->where('is_read', false)->count();
        $messages    = $query->paginate(20)->withQueryString();

        return view('users.inbox.index', compact('messages', 'totalCount', 'unreadCount', 'userSenders'));
    }

    public function markAsRead($id)
    {
        $userId = Auth::id();
        $userSenders = Senderid::where('user_id', $userId)->pluck('sender')->all();

        $message = InboxMessage::where(function ($q) use ($userId, $userSenders) {
            $q->where('user_id', $userId);
            if (!empty($userSenders)) {
                $q->orWhereIn('receiver', $userSenders);
            }
        })->findOrFail($id);

        $message->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('Message marked as read.'));
    }

    public function markAllRead()
    {
        $userId = Auth::id();
        $userSenders = Senderid::where('user_id', $userId)->pluck('sender')->all();

        InboxMessage::where(function ($q) use ($userId, $userSenders) {
            $q->where('user_id', $userId);
            if (!empty($userSenders)) {
                $q->orWhereIn('receiver', $userSenders);
            }
        })->where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', __('All messages marked as read.'));
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $userSenders = Senderid::where('user_id', $userId)->pluck('sender')->all();

        $message = InboxMessage::where(function ($q) use ($userId, $userSenders) {
            $q->where('user_id', $userId);
            if (!empty($userSenders)) {
                $q->orWhereIn('receiver', $userSenders);
            }
        })->findOrFail($id);

        $message->delete();

        return back()->with('success', __('Message deleted.'));
    }
}
