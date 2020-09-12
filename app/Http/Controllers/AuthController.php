<?php

namespace App\Http\Controllers;

use App\Company;
use App\Models\CodeResponse;
use App\Models\ModelPermission;
use App\Models\ResponseModel;
use App\Notifications\SignupActivate;
use App\User;
use App\UserIformationCompanys;
use App\ViewInfoUserCompany;
use App\ViewUserInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function signup(Request $request)
    {


        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'no_employee'     => $request->no_employee,
            'paternal_surname' => $request->paternal_surname,
            'maternal_surname' => $request->maternal_surname,
            'activation_token' => Str::uuid()->toString()

        ]);
        $user->deleted_at =null;


        $company  = Company::where('cod_company', $request->cod_company)->first();
        if($company==null){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Valida tu informacion",null,"cod_company"), 200);

        }

        $validate=$user->validate($request->only(['name','email','password','no_employee','paternal_surname','maternal_surname']));





        if($validate->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Valida tu informacion",$user,$validate->messages()), 200);
        }



        if($user->save()) {
            $user->cod_company = $request->cod_company;

            $user->notify(new SignupActivate($user));
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS, null, "Registro exitoso, se envió un correo a " . $user->email . " para que validez tu cuenta"), 200);
        }else
            return response()->json(
                new ResponseModel(CodeResponse::ERROR, null, "Registro no completado, valida tu informacion"), 200);

    }

    public function signupActivate($token,$codCompany)
    {

        $user = User::where('activation_token', $token)->first();

        if ($user== null) {
           return response()->json( new ResponseModel(CodeResponse::ERROR,null,"Tu token ha expirado o es invalido", 200));

        }

        $company  = Company::where('cod_company', $codCompany)->first();
        if($company==null){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Valida tu informacion",null,"cod_company"), 200);

        }


        $UserCompany= new UserIformationCompanys();
        $UserCompany->id_user = $user->id_user;
        $UserCompany->id_company = $company->id_company;
        $UserCompany->id_area = 0;
        $UserCompany->id_rol =0;

        $user->active = true;
        $user->activation_token = '';
        $user->save();
        $UserCompany->save();
        return "Registro completado";
    }

    public  function  recoveryPassword(Request $request) {
        $user = User::select()->where("email",$request->email)->first();



        if($user!=null){
            $user->sendPasswordResetNotification(Str::uuid()->toString());
        }

        return response()->json(
            new ResponseModel(CodeResponse::ERROR,null,"Registro exitoso"), 200);

    }

    public  function  login(Request  $request) {
        $credentials = $request->only('email', 'password');

       $validator = Validator::make($credentials,[

           'email' =>'required|email',
           'password'=>'required',
           'remember_me' => 'boolean'

       ]);


        if($validator->fails()) {
            return response()->json(
                new ResponseModel(CodeResponse::ERROR,null,"Las contraseñas son invalidas"), 200);
        }

        $credentials = $request->only('email', 'password');
        if (!$jwt_token = JWTAuth::attempt($credentials)) {
            return  response()->json([
                'status' => 'invalid_credentials',
                'message' => 'Correo o contraseña no válidos.',
            ], 401);
        }


        if($jwt_token){

            //DB::enableQueryLog(); // Enable query log
            $infoUser=ViewInfoUserCompany::where('email',$request->only('email'))->first();
            //dd(DB::getQueryLog()); // Show results of log
            $permission = ViewUserInformation::where('email',$request->only('email'))->get();

            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS,"Login correcto",$this->FormatUser($permission,$jwt_token,$infoUser)), 200);
        }
        else{

            return response()->json(new ResponseModel(CodeResponse::ERROR,"Las contraseñas son invalidas"), 200);
        }

    }

    public  function  logout(Request  $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return  response()->json([
                'status' => 'ok',
                'message' => 'Cierre de sesión exitoso.'
            ]);
        } catch (JWTException  $exception) {
            return  response()->json([
                'status' => 'unknown_error',
                'message' => 'Al usuario no se le pudo cerrar la sesión.'
            ], 500);
        }
    }

    private function FormatUser(object $items,string $token,object $infoUserCompany){



        $filterItem = new ViewUserInformation();
        $filterItem->token =$token;

        $filterItem->id_user = $infoUserCompany->id_user;
        $filterItem->no_employee = $infoUserCompany->no_employee;
        $filterItem->name = $infoUserCompany->name;
        $filterItem->paternal_surname = $infoUserCompany->paternal_surname;
        $filterItem->maternal_surname = $infoUserCompany->maternal_surname;
        $filterItem->email = $infoUserCompany->email;
        $imagePath = ($infoUserCompany->photo_path?:"storage/default_user.png");
        $filterItem->photo_path = assert($imagePath);

        $filterItem->name_area = $infoUserCompany->name_area?:"";
        $filterItem->icon_area = $infoUserCompany->icon_area?:0;

        $filterItem->name_company = $infoUserCompany->name_company?:"";
        $filterItem->logotype_company = $infoUserCompany->logotype_company?:"";
        $filterItem->id_company = $infoUserCompany->id_company?:0;



        $filterItem->id_rol = $infoUserCompany->id_rol?:0;
        $filterItem->name_rol = $infoUserCompany->name_rol?:"";
        $filterItem->name_status_user = $infoUserCompany->id_status_user?:0;



        $filterModule=array();

        foreach ($items as $item){

            $modelPermission = new ModelPermission();
            $modelPermission->can_create =$item->can_create==true;
            $modelPermission->can_delete =$item->can_delete==true;
            $modelPermission->can_update =$item->can_update==true;
            $modelPermission->can_select =$item->can_select==true;

            $modelPermission->name_module =$item->name_module;
            $modelPermission->id_module =$item->id_module;
            $modelPermission->icon_module =$item->icon_module;

            array_push($filterModule,$modelPermission);


        }

        $filterItem->modules =$filterModule;
        return $filterItem;

    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public  function  getAuthUser(Request  $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        return  response()->json(['user' => $user]);
    }
}
