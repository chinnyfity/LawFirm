<?php
// namespace App\Http\Controllers\Auth;
// use illuminate\Support\Facades\Auth;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

use DB;
use Str;
use Hash;
use Cookie;
use URL;
// use Twilio\Rest\Client;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{

    public function __construct(){
        
    }

    function index(){
        return redirect('dashboard/');
    }
}
