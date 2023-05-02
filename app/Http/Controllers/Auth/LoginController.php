<?php

namespace App\Http\Controllers\Auth;
use illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Session;
use Cookie;
use Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use App\Models\Interest;
use DB;
use Image;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    private $home;

    /**
     * Where to redirect users after login.
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
       
    }

}
