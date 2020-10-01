<?php

namespace App\Http\Controllers;

use App\AccountActivated;
use App\Models\CodeResponse;
use App\Models\ModelPermission;
use App\Models\ResponseModel;
use App\Models\UserPagination;
use App\User;
use App\ViewUserInformation;
use App\ViewUserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage as Storage;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Int $idUSer
     * @param Int $idCompany
     * @return \Illuminate\Http\JsonResponse
     */
    public function UserByIdCompany(Int $idUSer,Int $idCompany)
    {

        $items = ViewUserPermission::where('id_company',$idCompany)->where('id_user',$idUSer)->get();
        return response()->json(new ResponseModel(CodeResponse::ERROR,"",$this->FormatUser($items)), 200);

    }


    public function updateFirebaseToken(Request $request,int $idUser)
    {

        $validate =Validator::make($request->all(), [
            'firebaseToken' => 'require',
        ]);


        if(!$validate){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Formato no valido",null), 200);

        }
        $user=User::find($idUser);


        $user->device_id =$request->get('firebaseToken');
        $user->save();
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"Token actualizado", null,null),200);

    }

    public function updatePhoto(Request $request,int $idUser)
    {

        $validate =Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);


        if(!$validate){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Formato no valido",null), 200);

        }
        $user=User::find($idUser);

        Storage::delete('public/'.$user->photo_path);

        $originalPath = storage_path('app/public/');
        $originalImage= $request->file('image');
        $nameImage=time().$originalImage->getClientOriginalName();
        $thumbnailImage = Image::make($originalImage);
        $thumbnailImage->resize(700,700);
        $thumbnailImage->save($originalPath.$nameImage);
        $user->photo_path =$nameImage;
        $user->save();
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"", $user,null),200);

    }


    public function updateProfile(Request $request,int $idUser)
    {
        $data =new User($request->all());


        $validate=$data->validate($request->only(
            'name',
            'paternal_surname',
            'maternal_surname',
            'phone_number'));

        if($validate->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Campos invalidos",null,"Error en actualizar tu perfil"), 200);
        }
        $user=User::find($idUser);
        $user->name = $data->name;
        $user->paternal_surname = $data->paternal_surname;
        $user->maternal_surname = $data->maternal_surname;
        $user->phone_number = $data->phone_number;
        $user->save();

        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"Actualización correcta", $user,null),200);

    }


    public function GetUsersByIdCompany(Int $idCompany)
    {


        $items = ViewUserPermission::select(
            'id_user',
            'id_company',
            'email',
            'no_employee',
            'name',
            'paternal_surname',
            'maternal_surname',
            'photo_path',
            'name_area',
            'icon_area',
            'name_company',
            'logotype_company',
            'id_rol',
            'name_rol',
            'status_user'
        )
            ->where('id_company',$idCompany)
            ->groupBy([   'id_user',
                'id_company',
                'email',
                'no_employee',
                'name',
                'paternal_surname',
                'maternal_surname',
                'photo_path',
                'name_area',
                'icon_area',
                'name_company',
                'logotype_company',
                'id_rol',
                'name_rol',
                'status_user'])
            ->get();


        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$this->FormatUsers($items)), 200);

    }

    public function getUsersInformation(Int $idCompany,Int $pagination)
    {

        $pagination = $pagination ==0 ? 1 :$pagination;
        $id_company= $idCompany ==0 ? 0 :$idCompany;




        $items=ViewUserInformation::where('id_company',$id_company)->paginate($pagination);

        $items->each(function ($item){

            $item->photo_path =  asset(($item->photo_path == null ||$item->photo_path=='')?"storage/default_user.png":"storage/".$item->photo_path);
        });

        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$items, 200));

    }


    public function getAllUser()
    {

        $items = ViewUserPermission::select(
            'id_user',
            'name',
            'paternal_surname',
            'maternal_surname',
            'photo_path'
        )
            ->groupBy('id_user')
            ->get();
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$this->FormatUsers($items)), 200);

    }
    private function FormatUser(object $items){


        if(count($items)<=0){
            return array();
        }

        $filterItem = new ViewUserPermission();

        $filterItem->id_user = $items->get(0)->id_user;
        $filterItem->no_employee = $items->get(0)->no_employee;
        $filterItem->name = $items->get(0)->name;
        $filterItem->paternal_surname = $items->get(0)->paternal_surname;
        $filterItem->maternal_surname = $items->get(0)->maternal_surname;
        $filterItem->email = $items->get(0)->email;
        $filterItem->photo_path =  assert($items->get(0)->photo_path);

        $filterItem->name_area = $items->get(0)->name_area;
        $filterItem->icon_area = $items->get(0)->icon_area;

        $filterItem->name_company = $items->get(0)->name_company;
        $filterItem->logotype_company = $items->get(0)->logotype_company;
        $filterItem->id_company = $items->get(0)->id_company;



        $filterItem->id_rol = $items->get(0)->id_rol;
        $filterItem->name_rol = $items->get(0)->name_rol;
        $filterItem->name_status_user = $items->get(0)->status_user;



        $filterModule=array();

        foreach ($items as $item){

            $modelPermission = new ModelPermission();
            $modelPermission->can_create =$item->can_create;
            $modelPermission->can_delete =$item->can_delete;
            $modelPermission->can_update =$item->can_update;
            $modelPermission->can_select =$item->can_select;

            $modelPermission->name_module =$item->name_module;
            $modelPermission->id_module =$item->id_module;
            $modelPermission->icon_module =$item->icon_module;

            array_push($filterModule,$modelPermission);


        }

        $filterItem->modules =$filterModule;
        return $filterItem;

    }
    public function sendPushNotification(Int $idUser){
        try {
            $user=User::find($idUser);
            $user->notify(new AccountActivated());
        }catch (CouldNotSendNotification $exception){
            dd($exception);
        }



    }


    private function FormatUsers(object $items){


        if(count($items)<=0){
            return array();
        }

        $filterItems = array();
        foreach ($items as $item) {
            $filterItem = new ViewUserPermission();

            $filterItem->id_user = $item->id_user;
            $filterItem->no_employee = $item->no_employee;
            $filterItem->name = $item->name;
            $filterItem->paternal_surname = $item->paternal_surname;
            $filterItem->maternal_surname = $item->maternal_surname;
            $filterItem->email = $item->email;
            $filterItem->photo_path = $item->photo_path;

            $filterItem->name_area = $item->name_area;
            $filterItem->icon_area = $item->icon_area;

            $filterItem->name_company = $item->name_company;
            $filterItem->logotype_company = $item->logotype_company;
            $filterItem->id_company = $item->id_company;


            $filterItem->id_rol = $item->id_rol;
            $filterItem->name_rol = $item->name_rol;
            $filterItem->name_status_user = $item->status_user;

            array_push($filterItems,$filterItem);

        }

        return $filterItems;

    }

}
