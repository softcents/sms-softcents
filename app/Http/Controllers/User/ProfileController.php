<?php

namespace App\Http\Controllers\User;

use App\Models\PlanSubscribe;
use App\Models\User;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use HasUploader;

    public function index()
    {
        $user = User::where('id',Auth::user()->id)->first();
        $plan = PlanSubscribe::where('user_id', $user->id)
            ->select('will_expire', 'plan_data')
            ->first();
        return view('users.profile.index',compact('user', 'plan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'image' => 'nullable|image',
        ]);
        $user = User::findOrFail($id);

        if( $request->password || $request->current_password){
            if(Hash::check($request->current_password,$user->password)){
                $request->validate([
                    'current_password' => 'required|string',
                    'password' => 'required|string|confirmed',
                ]);
            }
            else{
                return response()->json(__('Current Password does not match with old password'),404);
            }
        }
        $user->update($request->except('image','password') + [
                'image'     => $request->image ? $this->upload($request, 'image', $user->image) : $user->image,
                'password'  => Hash::make($request->password),
            ]);

        return response()->json([
            'message'   => __('Profile updated successfully'),
            'redirect'  => route('users.profiles.index')
        ]);
    }
}
