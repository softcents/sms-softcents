<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Senderid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);
        return view('auth.register', compact('countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'password' => ['required', 'min:4', 'max:15','confirmed'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'country' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'client_secret' => $request->role == 'user' ? Str::uuid() : null,
        ]);

        $sender_id = Senderid::where('is_default', 1)->first();

        Senderid::create([
            'user_id' => $user->id,
            'sender' => $sender_id->sender,
            'type' => $sender_id->type,
        ]);

        // event(new Registered($user));

        $data = [
            'url' => route('login'),
            'name' => $request->name,
            'login_id' => $request->email,
            'password' => $request->password,
        ];

        if (env('MAIL_USERNAME')) {
            if (env('QUEUE_MAIL')) {
                Mail::to($request->email)->queue(new WelcomeMail($data));
            } else {
                Mail::to($request->email)->send(new WelcomeMail($data));
            }
        } else {
            return response()->json([
                'message' => __('Please setup you mail credentials. For sending mail to the users.'),
            ], 406);
        }

        Auth::login($user);

        if ($user->role == 'admin' || $user->role == 'superadmin') {

            $role = Role::where('name', $user->role)->first();
            $first_role = $role->permissions->pluck('name')->all()[0]; // get auth user first page permission.
            $page = explode('-', $first_role);
            $redirect_url = route('admin.'.$page[0].'.index');

        } else {
            $redirect_url = route('users.dashboard.index');
        }

        sendNotification($user->id, route('admin.users.index', ['users' => 'user']), __('New user Registerd.'));

        return response()->json([
            'message' => __('Registerd Successfully'),
            'redirect' => $redirect_url ?? route('login'),
        ]);
    }
}
