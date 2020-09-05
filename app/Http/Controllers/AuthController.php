<?php

namespace App\Http\Controllers;

use App\Models\CodeResponse;
use App\Models\ModelPermission;
use App\Models\ResponseModel;
use App\User;
use App\UserIformationCompanys;
use App\ViewUserInformation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Tymon\JWTAuth\JWTAuth;


class AuthController extends Controller
{
    public  $loginAfterSignUp = true;

    public  function  register(Request  $request) {
        $user = new  User();
        $user->no_employee      = $request->no_employee;
        $user->name             = $request->name;
        $user->paternal_surname = $request->paternal_surname;
        $user->maternal_surname = $request->maternal_surname;
        $user->email            = $request->email;
        $user->password         = bcrypt($request->password);
        $user->photo_path       = $request->photo_path;
        $is_saved = $user->save();

        return  response()->json([
            'status' => 'ok',
            'data' => $is_saved
        ], 200);
    }

    public  function  login(Request  $request) {
        $credentials = $request->only('email', 'password');

       $validator = Validator::make($credentials,[

           'email' =>'required|email',
           'password'=>'required'
       ]);

        $username =  $request->input('email');
        $password =  $request->input('password');
        //$token = JWTAuth::attempt($credentials);

        if($validator->fails()) {
            return response()->json(
                new ResponseModel(CodeResponse::ERROR,null,"Las contraseñas son invalidas"), 200);
        }


       $token = $this->guard()->attempt(

           array('email'=> $username,'password'=>$password)
       );

        if($token){
            $informationUser = ViewUserInformation::where('email',$username)->get();



            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS,"Login correcto",$this->FormatUser($informationUser,$token)), 200);
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

    private function FormatUser(object $items,string $token){


        if(count($items)<=0){
            return array();
        }

        $filterItem = new ViewUserInformation();
        $filterItem->token =$token;

        $filterItem->id_user = $items->get(0)->id_user;
        $filterItem->no_employee = $items->get(0)->no_employee;
        $filterItem->name = $items->get(0)->name;
        $filterItem->paternal_surname = $items->get(0)->paternal_surname;
        $filterItem->maternal_surname = $items->get(0)->maternal_surname;
        $filterItem->email = $items->get(0)->email;
        $filterItem->photo_path = $items->get(0)->photo_path;

        $filterItem->name_area = $items->get(0)->name_area;
        $filterItem->icon_area = $items->get(0)->icon_area;

        $filterItem->name_company = $items->get(0)->name_company;
        $filterItem->logotype_company = $items->get(0)->logotype_company;
        $filterItem->id_company = $items->get(0)->id_company;



        $filterItem->id_rol = $items->get(0)->id_rol;
        $filterItem->name_rol = $items->get(0)->name_rol;
        $filterItem->name_status_user = $items->get(0)->id_status_user;



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
