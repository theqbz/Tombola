<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class UserController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('user.index', ['user' => Auth::user()]);
    }

    protected function validator(array $data) {
        $validationRules = array("email"      => ['required', 'string', 'email', 'max:255'],
                                 "first_name" => ['required', 'string', 'max:255'],
                                 "last_name"  => ['required', 'string', 'max:255'],
                                 'address'    => ['nullable', 'regex:/([- ,\/0-9a-zA-Z]+)/', 'string', 'max:255']);

        return Validator::make($data, $validationRules);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        return view('user.edit', ['user' => Auth::user()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function authorValidation(array $data, $checkAuthor = false) {

        $validationMust = array("email"      => ['required', 'string', 'email', 'max:255'],
                                "first_name" => ['required', 'string', 'max:255'],
                                "last_name"  => ['required', 'string', 'max:255']);
        $validationPlus = array('address'       => ['nullable', 'regex:/([- ,\/0-9a-zA-Z]+)/', 'string', 'max:255'],
                                'date_of_birth' => ['nullable', 'date', 'max:255']);
        if ($checkAuthor) {
            $validationPlus = array_map( function ($item) {
                $item[0] = 'required';
                return $item;
            },$validationPlus);
        }

        $validationRules = array_merge($validationMust, $validationPlus);
        return Validator::make($data, $validationRules);
    }

    public function update(Request $request) {
        $this->validator($request->all())->validate();
        try {
            $user = Auth::user();
            $user->update($request->all());
            if (!$this->authorValidation($request->all(), true)->fails()) {
                $user->status = 2;
                $user->save();
            } else {
                $user->status = 1;
                $user->save();
            }
        } catch (Exception $e) {
            return back()->with(['error' => __('Failed Save')]);
        }


        return back()->with(['success' => __('Saved')]);
    }

    public function myTickets(Request $request)
    {
        $status = (!is_null($request->input('status')))?$request->input('status'):'active';
        $eventTickets = Auth::user()->listTickets($status);

        return view('user.tickets', ['eventTickets' => $eventTickets,'status'=>$status]);
    }

    public function myPrizes() {
        $eventPrizes = Auth::user()->listPrizes();
        return view('user.prizes', ['eventPrizes' => $eventPrizes]);
    }

}
