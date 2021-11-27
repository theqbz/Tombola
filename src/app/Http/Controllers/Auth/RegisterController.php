<?php

namespace App\Http\Controllers\Auth;

use App\Events\TemporaryRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegisterController extends Controller
{

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.registerBase');
    }

    public function showTempRegistrationForm()
    {
        return view('auth.registerTemp');
    }


    protected function validator(array $data, $type)
    {
        $validationRules = array();
        switch ($type) {
            case 'temp':
                $validationRules['email'] = [
                    'required', 'string', 'email', 'max:255'
                ];
                break;
            case 're-email':
                $validationRules['re-email'] = [
                    'required', 'string', 'email', 'max:255'
                ];
                break;
            case 'new':
                $validationRules['email'] = [
                    'required', 'string', 'email', 'max:255', 'unique:users'
                ];
                break;
            case 'step2':
                $validationRules['re-email'] = [
                    'required', 'string', 'email', 'max:255',
                ];
                $validationRules['first_name'] = [
                    'required', 'string', 'max:255'
                ];
                $validationRules['last_name'] = [
                    'required', 'string', 'max:255'
                ];
                $validationRules['password'] = [
                    'required', 'string', 'min:8', 'confirmed'
                ];
                $validationRules['gdpr'] = [
                    'accepted', 'required'
                ];
                break;
        }
        return Validator::make($data, $validationRules);
    }

    public function registerTemp(Request $request)
    {
        $user = User::where('email', $request->input('email'))->where('status', 0)->first();

        if (!$user) {
            $this->validator($request->all(), 'new')->validate();
            $user = User::create([
                'email' => $request->input('email')
            ]);
        }

        try {


            if (!$user->hash) {
                $this->generateAccesses($user, true);
            }

            if (!$user->dashboard_url) {
                $user->dashboard_url = Hash::make($user->hash);
                $user->save();
            }
            event(new TemporaryRegistered($user));
            //return redirect('/dashboard/' . $user->hash);
            return view('auth.verifyTemp')->with('id', $user->id);
        } catch (Exception $e) {
            return back()->with(['error' => __('An error occured.Please try again!')]);
        }

    }

    public function register(Request $request)
    {
        if ($request->input('id')) {
            return $this->updateFinalAccount($request);
        }

        if ($request->input('re-email')) {
            return $this->createFinalAccount($request);
        }


        $user = $this->checkIfExists([
            'email', $request->input('email')
        ]);

        if ($user) {
            if (!$user->status) {
                $this->validator($request->all(), 'temp')->validate();

                return view('auth.register', [
                    'uid' => $user->id, 're_email' => $user->email
                ]);
            }
        }
        $this->validator($request->all(), 'new')->validate();

        return view('auth.register', ['re_email' => $request->input('email')]);
    }


    private function updateFinalAccount(Request $request)
    {
        $user = $this->checkIfExists(array(
            [
                'id' => $request->input('id'), 'status' => 0
            ]
        ));
        if (!$user || $this->validator($request->only('re-email'), 're-email')->fails()) {
            return back()->with(['error' => __('An error occured.Please try again!')]);
        }


        if ($this->validator($request->all(), 'step2')->fails()) {
            return view('auth.register', [
                'uid' => $user->id, 're_email' => $user->email, 'name' => $request->input('name')
            ])->withErrors($this->validator($request->all(), 'step2'));
        }


        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->password = Hash::make($request->input('password'));
        $user->status = 1;
        $user->dashboard_url = null;
        $user->exp_date = null;
        $user->email_verified_at = null;

        $user->save();


        event(new Registered($user));


        return view('auth.verify')->with('id', $user->id);


    }

    private function createFinalAccount(Request $request)
    {

        if ($this->validator(['email' => $request->input('re-email')], 'new')->fails()) {
            return back()->with(['error' => __('An error occured.Please try again!')]);
        }

        if ($this->validator($request->all(), 'step2')->fails()) {
            $request->flashExcept('password');
            return view('auth.register', ['re_email' => $request->input('re-email'), 'name' => $request->input('name')])->withErrors($this->validator($request->all(), 'step2'));
        }

        $user = User::create([
            'email' => $request->input('re-email'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'password' => Hash::make($request->input('password')),
            'status' => 1,
        ]);

        $this->generateAccesses($user);


        event(new Registered($user));
        return view('auth.verify')->with('id', $user->id);

    }

    private function checkIfExists(array $params)
    {
        return User::where([$params])->first();
    }

    private function generateAccesses(User &$user, $isTemp = false)
    {
        if ($isTemp) {
            $accessCode = "U-" . strtolower(substr($user->email, 0, 3) . substr(time(), -3));
        } else {
            $accessCode = "U-" . strtolower(substr($user->first_name, 0, 3) . substr($user->last_name, 0, 3) . substr(time(), -3));
        }
        $user->hash = $accessCode;
        $user->save();
        $path = public_path('/qrcodes/users');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }
        QrCode::generate($accessCode, $path . '/' . $user->id . '.svg');

    }

}
