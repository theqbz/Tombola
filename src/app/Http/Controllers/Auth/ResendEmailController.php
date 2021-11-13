<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

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

    public function resendTempEmail(Request $request) {
        if ($request->input('id')) {
            $user = User::findOrFail($request->input('id'));
        }

        $details = ['title' => __('Login credentials')."-".config('app.name'), 'url' => URL::to('/') . '/dashboard/' . $user->hash];
        Mail::to($user->email)->send(new \App\Mail\TempMail($details));
        return view('auth.verifyTemp')->with('resent', true);
    }
}
