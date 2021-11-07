<?php

namespace App\Http\Controllers\Auth;

use App\Events\TemporaryRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $user = User::create([
                'email' => $request->input('email')
            ]);
        }

        if (!$user->hash) {
            $hash = base64_encode(Hash::make(now()));
            $user->hash = $hash;
        }

        try {
            $user->save();
            event(new TemporaryRegistered($user));
            return redirect('/dashboard/' . $user->hash);
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


        $user = $this->checkiIfExists([
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
        $user = $this->checkiIfExists(array(
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
        $user->last_name = $request->input('layt_name');
        $user->password = Hash::make($request->input('password'));
        $user->status = 1;
        $user->hash = null;
        $user->exp_date = null;
        $user->email_verified_at = null;

        $user-> save();


        event(new Registered($user));


        return view('auth.verify')->with('id',$user->id);


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
           'email'=>$request->input('re-email'),
           'first_name'=>$request->input('first_name'),
           'last_name'=>$request->input('last_name'),
           'password'=> Hash::make($request->input('password')),
           'status'=>1,
        ]);


        event(new Registered($user));
        return view('auth.verify')->with('id',$user->id);

    }

    private function checkiIfExists(array $params)
    {
        return User::where([$params])->first();
    }

    private function generateAccesses(User &$user)
    {
        $accessCode = "U-".strtolower(substr($user->first_name,0,3).substr($user->last_name,0,3).substr(time(),0,3));
        $user->access_code =$accessCode;
        QrCode::generate($accessCode, '../public/qrcodes/users/'.$user->id.'.svg');
    }

}
