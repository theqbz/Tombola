<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    public function index()
    {
        if (Auth::user()) {
            return view('pages.dashboard')->with('user', Auth::user());
        }
        return view('pages.home');
    }

    public function getPage(Request $request)
    {

        $route = $request->route()->uri;
        if (method_exists($this, $route)) {
            return $this->$route($request);
        }

        if (view()->exists('pages.' . $route)) {
            return view('pages.' . $route);
        }

        return abort(404);
    }

    public function dashboard()
    {
        return view('pages.dashboard')->with('user', Auth::user());
    }

    public function dashboardTemp(Request $request)
    {
        $hash = $request->route('hash');
        $user = User::where('dashboard_url', $hash)->first();
        if ($user) {
            return view('pages.dashboardTemp')->with('user', $user);
        } else {
            return abort(404);
        }
    }


    public function verified()
    {
        return view('auth.verified');
    }

}
