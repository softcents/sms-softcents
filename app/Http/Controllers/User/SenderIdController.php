<?php

namespace App\Http\Controllers\User;

use App\Models\Senderid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SenderIdController extends Controller
{
    public function index(Request $request)
    {
        $senderids = Senderid::where('user_id', auth()->id())->with('user')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('sender', 'like', '%' . $request->search . '%')
                        ->orWhere('type', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $senderids = $senderids->get();
            return response()->json([
                'data' => view('users.senderids.datas', compact('senderids'))->render()
            ]);
        }

        $senderids = $senderids->paginate(10);
        return view('users.senderids.index', compact('senderids'));
    }
}
