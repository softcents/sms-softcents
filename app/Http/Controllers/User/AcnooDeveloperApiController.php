<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AcnooDeveloperApiController extends Controller
{
    public function index()
    {
        return view('users.client-secret.index');
    }

    public function store()
    {
        $user = auth()->user();
        $new_secret = Str::uuid();

        $user->update([
            'client_secret' => $new_secret,
        ]);

        return response()->json([
            'secret' => $new_secret,
            'message' => __("New client secret generated successfully.")
        ]);
    }

    public function developerOptions()
    {
        return view('users.documentation.index');
    }
}
