<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResendEmailController extends Controller
{
    public function resend(Request $request)
    {
        if ($request->input('id')) {
            $user = User::findOrFail($request->input('id'));
        }


        $user = isset($user) ? $user: $request->user();
        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return view('auth.verify')->with('resent', true);
    }
}
