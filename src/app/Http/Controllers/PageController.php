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

}
