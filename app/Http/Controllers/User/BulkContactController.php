<?php

namespace App\Http\Controllers\User;

use App\Models\Group;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Imports\NumberImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class BulkContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groups = Group::where('user_id', auth()->id())->whereStatus(1)->latest()->get();
        return view('users.bulk-contacts.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
            'group_id' => 'nullable|exists:groups,id',
            'file' => 'required|file|mimes:xlsx,xls,csv,ods|max:10240',
        ]);

        $time = now();
        $import = new NumberImport();
        Excel::import($import, $request->file('file'));

        foreach ($import->numbers as $key => $number) {
            $data[] = [
                'number' => $number,
                'user_id' => auth()->id(),
                'status' => $request->status,
                'group_id' => $request->group_id,
                'name' => $import->names[$key],
                'email' => $import->emails[$key],
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }

        Contact::insert($data);

        return response()->json([
            'message' => __('Contacts created successfully'),
            'redirect' => route('users.contacts.index')
        ]);
    }
}
