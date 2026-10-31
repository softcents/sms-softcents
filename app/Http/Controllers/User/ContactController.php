<?php

namespace App\Http\Controllers\User;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $contacts = Contact::where('user_id', auth()->id())->with('group')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                           ->orWhere('email', 'like', '%' . $request->search . '%')
                           ->orWhere('number', 'like', '%' . $request->search . '%')
                           ->orWhereHas('group', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->search . '%');
                            });
                });
            })
            ->latest();

        if ($request->ajax()) {
            $contacts = $contacts->get();
            return response()->json([
                'data' => view('users.contacts.datas', compact('contacts'))->render()
            ]);
        }

        $contacts = $contacts->paginate(10);
        return view('users.contacts.index', compact('contacts'));
    }

    public function acnooFilter(Request $request)
    {
        $contacts = Contact::where('user_id', auth()->id())->with('group')
            ->when($request->has('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('number', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->orWhereHas('group', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10);

        if($request->ajax()){
            return response()->json([
                'data' => view('users.contacts.datas',compact('contacts'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('user_id', auth()->id())->whereStatus(1)->latest()->get();
        return view('users.contacts.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
            'email' => 'nullable|email|max:50',
            'name' => 'nullable|string|max:250',
            'number' => 'required|string|max:20|min:5',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        Contact::create($request->all() + [
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => __('Contact created successfully'),
            'redirect' => route('users.contacts.index')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $groups = Group::where('user_id', auth()->id())->latest()->get();
        return view('users.contacts.edit', compact('groups', 'contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        abort_if($contact->user_id != auth()->id(), 404);

        $request->validate([
            'status' => 'required|boolean',
            'email' => 'nullable|email|max:50',
            'name' => 'nullable|string|max:250',
            'number' => 'required|string|max:20|min:5',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $contact->update($request->all());

        return response()->json([
            'message' => __('Contact updated successfully'),
            'redirect' => route('users.contacts.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        abort_if($contact->user_id != auth()->id(), 404);
        $contact->delete();
        return response()->json([
            'message'   => __('Contact deleted successfully'),
            'redirect'  => route('users.contacts.index')
        ]);
    }

    public function status(Request $request, Contact $contact)
    {
        abort_if($contact->user_id != auth()->id(), 404);
        $contact->update(['status' => $request->status]);
        return response()->json(['message' => 'Contact status updated.']);
    }

    public function deleteAll(Request $request)
    {
        Contact::where('user_id', auth()->id())->whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Contacts deleted successfully'),
            'redirect' => route('users.contacts.index')
        ]);
    }
}
