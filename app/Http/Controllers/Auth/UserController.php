<?php

namespace App\Http\Controllers\Auth;
use illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Models\ClientDetail;
use Cookie;
use URL;
use Str;
use Hash;
use Image;
use Carbon\Carbon;
use Validator;
use DB;
use DataTables;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $userData = User::find(3);
            Auth::guard("user")->login($userData, false);

            $this->user = auth('user')->user();
            \View::share('user', $this->user);
            return $next($request);
        });
    }


    function dispatchEmails($details){
        $receiver = $details['emails'];
        $isArray = $details['isArray'];
        try{
            if($isArray == 1) // array
                Mail::to('noreply@momentumafrica.org')->bcc($receiver)->send(new SendEmail($details));
            else
                Mail::to($receiver)->send(new SendEmail($details));
            return 'email was sent';
          }catch(\Exception $e){
            return 'failed - '.$e->getResponse();
        }
    }


    public function dashboard() {
        $firstname = $this->user['firstname'];
        $data['page_name'] = "dashboard";
        $data['page_name1'] = "dashboard";

        $clients = ClientDetail::orderBy('id', 'desc')->take(6)->get();
        $data['no_pics'] = ClientDetail::whereRaw('pics IS NULL')->count();
        $data['no_of_clients'] = ClientDetail::count();
        $data['no_of_staff'] = User::count();

        $data['clients'] = $clients;
        $data['id_url'] = "";
        $data['page_title'] = "My Dashboard";
        return view('auth.dashboard.index', $data);
    }


    function add_clients(){        
        $data['page_name1'] = "dashboard";
        $data['page_name'] = "add_clients";
        $data['page_title'] = "Add Clients";
        $data['client_details'] = "";
        return view('auth.dashboard.form', $data);
    }

    function edit_clients($id){        
        $data['page_name1'] = "dashboard";
        $data['page_name'] = "edit_clients";
        $data['page_title'] = "Edit Clients";
        $data['client_details'] = ClientDetail::where('uuid', $id)->first();
        return view('auth.dashboard.form', $data);
    }

    function view_clients(){        
        $data['page_name1'] = "dashboard";
        $data['page_name'] = "view_clients";
        $data['page_title'] = "View Clients";
        return view('auth.dashboard.tables', $data);
    }


    public function submit_clients(Request $request){
        $attributes = [
            'fname'         => 'Firstname',
            'lname'         => 'Lastname',
            'email'         => 'Email',
            'phone'         => 'Phone',
            'legal_counsel' => 'Legal Counsel',
            'dob'           => 'Date of Birth',
            'case_details'  => 'Client\'s Case Details',
            'profiled'      => 'Date Profiled',
            'img_picture'   => 'Profile photo',
            'img_picture.*' => 'Profile photo',
            '_token'        => 'Token',
        ];
        $rules = [
            'fname'         => 'required|string|min:3|max:20|alpha',
            'lname'         => 'required|string|min:3|max:20|alpha',
            'email'         => 'required|email|string|unique:client_details,email,'.$request->client_id.',uuid',
            'phone'         => 'numeric|regex:/[0-9]{9}/|unique:client_details,phone,'.$request->client_id.',uuid',
            'legal_counsel' => 'required',
            'dob'           => 'required',
            'case_details'  => 'required',
            'profiled'      => 'required',
            'img_picture.*' => 'mimes:jpeg,jpg,png|max:10485760', // 10mb
            '_token'        => 'required',
        ];
        $messages = [
            'required'      => ':attribute field is required',
            'required_if'   => ':attribute field is required',
            'alpha'         => ':attribute may only contain letters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages)->setAttributeNames($attributes);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
                'data' => ''
            ],200); 
        }

        $fileName = "";
        $data = [];
        $data2 = [];
        $data3 = [];
        if($request->hasfile('img_picture')) {
            $fileName = time().'.'.$request->img_picture->extension();

            $filePath = public_path('/assets/clients_profile/');
            $img = Image::make($request->img_picture->path());
            $img->resize(400, 400, function ($const) { // resize the image to make the site load faster
                $const->aspectRatio();
            })->save($filePath.$fileName); 

            if($request->txt_file != ""){ // delete the prev image
                $in_folder1 = public_path()."/assets/clients_profile/".$request->txt_file;
                if(is_readable($in_folder1)) @unlink($in_folder1);
            }
            $data = array('pics' => $fileName);
        }

        $data2 = array(
            'uuid'     => Str::uuid(),
        );

        $data3 = array(
            'staff_id'      => $this->user->id,
            'firstname'     => $request->fname,
            'lastname'      => $request->lname,
            'email'         => trim($request->email),
            'phone'         => trim($request->phone),
            'date_profiled' => $request->profiled,
            'legal_counsel' => $request->legal_counsel,
            'dob'           => $request->dob,
            'case_details'  => $request->case_details,
        );
        $allData = array_merge($data, $data3);

        if($request->client_id != ""){
            $users = ClientDetail::where('uuid', $request->client_id)->first();
            $users->update($allData);
        }else{
            $allData = array_merge($data, $data2, $data3);
            $users = ClientDetail::create($allData);
        }
        
        if($users){
            $full_names = ucwords($request->fname." ".$request->lname);
            $data1 = [
                'firstname' => $request->fname,
                'message' => "
                <div><b>Welcome to ABC Law Firm</b></div>
                <div>Your profile has been created with us and these below are your details:</div>
                <div class='row'>
                    <div class='col-4'>Full names</div>
                    <div class='col-8'>$full_names</div>
                </div>
                <div class='row'>
                    <div class='col-4'>Email</div>
                    <div class='col-8'>".trim($request->email)."</div>
                </div>
                <div class='row'>
                    <div class='col-4'>Phone</div>
                    <div class='col-8'>".trim($request->phone)."</div>
                </div>
                <div class='row'>
                    <div class='col-4'>Date of Birth</div>
                    <div class='col-8'>".$request->dob."</div>
                </div>
                <div class='row'>
                    <div class='col-4'>Legal Counsel</div>
                    <div class='col-8'>".ucwords($request->legal_counsel)."</div>
                </div>
                <div class='row'>
                    <div class='col-4'>Case Details</div>
                    <div class='col-8'>".nl2br($request->case_details)."</div>
                </div>
                ",
            ];
            $details['subj'] = "Your details have been created";
            $details['data'] = $data1;
            $details['isArray'] = 0; // 1=array, 0=not_array
            $details['email_template'] = "email_templates";
            $details['emails'] = trim($request->email);

            $mail="";
            if($request->client_id == ""){ // new clients only
                $mail = $this->dispatchEmails($details);
            }

            return response()->json([
                'status' => 'success',
                'message' => $request->client_id != "" ? "updated" : 'created',
                'data' => [
                    'image' => $fileName,
                    'mail_response' => $mail,
                ]
            ],200);
        }
        return response()->json([
            'status' => 'success',
            'message' => $request->client_id != "" ? "Error in updating clients details" : "Error in creating clients details",
            'data' => ''
        ],200); 
    }


    public function fetch_tables(Request $request){
        $routeName = Route::currentRouteName();

        if($routeName == "view_clients_"){
            if ($request->ajax()) {
                $data = ClientDetail::orderBy('id', 'desc');

                return Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn('firstname', function($row){
                        $fulls = ucwords($row->firstname." ".$row->lastname);
                        if(strlen($fulls) <= 4){
                            $fulls = "Anonymous User";
                        }
                        return $fulls;
                    })
                    ->addColumn('staff_id', function($row){
                        $names = User::select('firstname', 'lastname', 'email')->whereId($row->staff_id)->first();
                        if($names){
                            $full_names = "$names->firstname $names->lastname";

                            if(strlen($full_names) <= 4){
                                $full_names = $names->email;
                            }
                            return ucwords($full_names)." <b>(Staff)</b>";
                        }
                    })
                    ->addColumn('email', function($row){
                        $email = "<p style='line-height:28px;margin:0'><a style='color:#AE653E' href='mailto:$row->email'>$row->email</a></p>";
                        return $email;
                    })
                    ->addColumn('phone', function($row){
                        $phone = "<p style='line-height:28px;margin:0'><a style='color:#AE653E' href='tel:+$row->phone'>$row->phone</a></p>";
                        return $phone;
                    })
                    ->addColumn('date_profiled', function($row){
                        return date("D jS, M Y", strtotime($row->date_profiled));
                    })
                    ->addColumn('legal_counsel', function($row){
                        return ucwords($row->legal_counsel);
                    })
                    ->addColumn('dob', function($row){
                        $age = $this->getAge($row->dob)." years";
                        return date("D jS, M Y", strtotime($row->dob))." <span style='color:#AE653E;font-size:14px'>(".$age.")</span>";
                    })
                    ->addColumn('case_details', function($row){
                        return nl2br($row->case_details);
                    })
                    ->addColumn('pics', function ($row) { 
                        $myPics="";
                        if($row->pics == NULL){
                            $myPics .= "<label style='color:#666'>No image Uploaded</label>";
                        }else{
                            $myPics.="<div class='row'>";
                                $myPics .= "<div class='col-sm-12 col-xs-12 pt-10'>";
                                    $pic_pathi= asset('clients_profile/'.$row->pics);
                                    $myPics .= "<img src='$pic_pathi' style='width:150px' />";
                                $myPics .= "</div>";
                            $myPics .= "</div>";
                        }
                        return $myPics;
                    })
                    ->addColumn('created_at', function($row){
                        $created_at = date("D jS, M Y h:ia", strtotime($row->created_at));
                        if($row->created_at == null){
                            $created_at = "<font style='color:#666'>Null</font>";
                        }
                        return $created_at;
                    })
                    ->addColumn('updated_at', function($row){
                        $updated_at = date("D jS, M Y h:ia", strtotime($row->updated_at));
                        if($row->updated_at == null){
                            $updated_at = "<font style='color:#666'>Null</font>";
                        }
                        return $updated_at;
                    })
                    ->addColumn('action', function($row){
                        $actionBtn = '';
                        $actionBtn .= '<div class="row mt-5 pr-20">';
                            $actionBtn .= '<div class="col-lg-6 col-md-1 col-2 pr-xl-0 pr-lg-0 pr-md-20">';
                                $actionBtn .= '<button class="btn btn-primary btn-xs edit_client pl-10 pr-10" mypage="edit-client" id="'.$row->uuid.'" style="border-radius:5px!important"><span class="fa fa-edit"></span> </button>';
                            $actionBtn .= '</div>';

                            $actionBtn .= '<div class="col-lg-6 col-md-10 col-10 pl-xl-1 pr-xs-40 pl-lg-1 pl-md-20">';
                                $actionBtn .= '<button class="btn btn-danger btn-xs btn_delete pl-10 pr-10" table="App\Models\ClientDetail" table_name="client_details" caption="" ids="'.$row->uuid.'" column="uuid" style="border-radius:5px!important"><span class="fa fa-trash"></span></button>';

                            $actionBtn .= '</div>';
                        $actionBtn .= '</div>';
                        return $actionBtn;
                    })
                ->rawColumns(['firstname', 'email', 'phone', 'staff_id', 'date_profiled', 'legal_counsel', 'dob', 'case_details', 'date_created', 'action', 'pics'])->make(true);
            }
        }
    }

    function getAge($date) {
        return intval(date('Y', time() - strtotime($date))) - 1970;
    }

    
    function delete_records(Request $request){
        $ids = $request->ids;
        $column = $request->column;
        $table = $request->table;
        $table_name = $request->table_name;
        
        if($table_name == "client_details"){
            $user_details = $table::where($column, $ids)->first();
            if($user_details){
                $pics = $user_details->pics;
                $in_folder1 = public_path()."/assets/clients_profile/$pics";
                if(is_readable($in_folder1)) @unlink($in_folder1);
            }          
            $is_deleted = $user_details->delete();
        }
        return ($is_deleted ? true : false);
    }

}
