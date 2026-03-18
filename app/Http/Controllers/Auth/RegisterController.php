<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\facilities;
use App\department;
use App\unit;
use Illuminate\Support\Facades\Mail;
use App\Mail\accountEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

       try{

            // SEND E-MAILS
            $userEmail = [$data['email']];
            $AdminEmails = ['anwokoma@ihvnigeria.org'];
            $emails = array_merge($userEmail,$AdminEmails);


            Mail::to($data['email'])->cc($AdminEmails)->send(new accountEmail($data['name'],$data['password'],$data['email']));


        } catch(\Exception $e) {
            echo 'Message: ' .$e->getMessage();
		}
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'unit' => $data['unit'],
            'department' => $data['department'],
            'facility' => $data['facility'],
            'state' => $data['state'],
            'role' => $data['role']

        ]);
        session()->flash('message','The New Account has been created successfully!');

        return redirect()->back();


    }

    public function showRegistrationForm()
    {
        if (Auth()->user()->role=="Admin" || Auth()->user()->role=="DCTAdmin"){
            $facilities=facilities::select('id','facility_name')->orderBy('facility_name','asc')->get();
        }else{
            $facilities=facilities::select('id','facility_name')->orderBy('facility_name','asc')->where('state',Auth()->user()->state)->get();
        }
        $units=unit::select('id','unit_name')->get();
        $departments=department::select('id','department_name')->get();

        return view('auth.register', compact('facilities','units','departments'));
    }

}
