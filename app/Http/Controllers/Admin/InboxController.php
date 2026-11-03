<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InboxMessage;
use App\Models\Smsgateway;
use App\Services\InboxService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $query = InboxMessage::with(['user:id,name,email,phone', 'gateway:id,name,driver'])
            ->latest('received_at');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('sender', 'like', "%{$search}%")
                  ->orWhere('receiver', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('received_at', $request->date);
        }

        $totalCount  = InboxMessage::count();
        $unreadCount = InboxMessage::where('is_read', false)->count();
        $messages    = $query->paginate(20)->withQueryString();

        $anbernetGateway = Smsgateway::where('driver', 'anbernet')->first();

        return view('admin.inbox.index', compact('messages', 'totalCount', 'unreadCount', 'anbernetGateway'));
    }

    public function markAsRead($id)
    {
        $message = InboxMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('Message marked as read.'));
    }

    public function markAllRead()
    {
        InboxMessage::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', __('All messages marked as read.'));
    }

    public function destroy($id)
    {
        $message = InboxMessage::findOrFail($id);
        $message->delete();

        return back()->with('success', __('Incoming message deleted successfully.'));
    }

    public function syncAnbernet(Request $request)
    {
        $gateway = Smsgateway::where('driver', 'anbernet')->first();
        if (!$gateway) {
            return back()->with('error', __('Anbernet gateway is not configured.'));
        }

        $result = InboxService::syncAnbernet(
            $gateway,
            $request->input('msisdn'),
            $request->input('incoming_password')
        );

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
