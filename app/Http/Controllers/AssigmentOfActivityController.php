<?php

namespace App\Http\Controllers;

use App\AssigmentOfActivity;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use App\NewNotification;
use App\Notifications\NewActivityEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssigmentOfActivityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,AssigmentOfActivity::all(),"Success Save"), 200);



    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBetweenDate(int $idUSer,string $startDate)
    {


        $datas= AssigmentOfActivity::where(
         [
             'id_user'=>$idUSer,
             'start_date'=>$startDate
         ])->get();


        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,"Success",$datas), 200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivitiesforMoth(int $idUSer)
    {


        $datas= AssigmentOfActivity::
        whereRaw('month(start_date) = month(current_date) and year(start_date) = year(current_date) and id_user =?',[$idUSer])
            ->get();


        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,"Success",$datas), 200);


    }






    public function changeStatusStart(Request $request)
    {



        $idAssigment = $request->get("idAssigment");
        $time = $mytime = date('Y-m-d H:i:s');

        $assigment = AssigmentOfActivity::find($idAssigment);


        $start_date = $assigment->start_date .$assigment->start_time;


        if(Carbon::parse($start_date)->lt(Carbon::now()))
        {
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS, "No, se puede iniciar esta actividad, esta fuera de fechas ", null), 200);
        }
        $assigment->status_activity = "2";
        $assigment->stated_date = $time;


        if( $assigment->save()) {
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS, "Actividad actualizada", null), 200);
        }
        else {
            return response()->json(
                new ResponseModel(CodeResponse::ERROR, "Actividad  no actualizada", null), 200);
        }


    }

    public function changeStatusStop(Request $request)
    {

        $idAssigment = $request->get("idAssigment");
        $time = $mytime = date('Y-m-d H:i:s');

        $assigment = AssigmentOfActivity::find($idAssigment);
        $assigment->status_activity = "6";

        $assigment->finish_date = $time;


        if( $assigment->save()) {
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS, "Actividad actualizada", null), 200);
        }
        else {
            return response()->json(
                new ResponseModel(CodeResponse::ERROR, "Actividad  no actualizada", null), 200);
        }


    }

    public function changeStatusAssigment(int $idAssigment,string $status)
    {

        $assigment = AssigmentOfActivity::find($idAssigment);
        $assigment->status_activity = $status;

       if( $assigment->save()) {
           return response()->json(
               new ResponseModel(CodeResponse::SUCCESS, "Actividad actualizada", "OK"), 200);
       }
       else {
           return response()->json(
               new ResponseModel(CodeResponse::ERROR, "Actividad  no actualizada", "OK"), 200);
       }


    }


    public function sendPushNotificationActivity(AssigmentOfActivity $info){
        try {
            $user=User::find($info->id_user);
            $user->notify(new NewNotification($info));
            $user->notify(new NewActivityEmail($info));

        }catch (Exception $exception){
            dd($exception);
        }

    }
    public function getCurrentWeekend(int $idUSer)
    {

        $results = DB::select( DB::raw("select *from assigment_of_activities where   week(start_date) = week(now()) and id_user =".$idUSer), array(
        ));

        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,"Success",$results), 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {


        $assigmentOfActivity= new AssigmentOfActivity($request->only(
            ['id_user','type_activity','status_activity','start_date','end_date','start_time','end_time','notes']
        ));



        if($assigmentOfActivity->save()){
            $this->sendPushNotificationActivity($assigmentOfActivity);

            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS,"Se agrego correctamente",$assigmentOfActivity), 200);
        }else {
            return response()->json(
                new ResponseModel(CodeResponse::ERROR,"Erro al agregar la actividad al usuario",$assigmentOfActivity), 200);
        }





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AssigmentOfActivity  $assigmentOfActivity
     * @return \Illuminate\Http\Response
     */
    public function show(AssigmentOfActivity $assigmentOfActivity)
    {
        //
    }


    public function edit(Request $request, int $id)
    {
        $item= AssigmentOfActivity::find($id);

        if($item == null){

            return response()->json(
                new ResponseModel(CodeResponse::ERROR,"Dato no encontrado",$id), 200);
        }
        $item->start_date = $request->get("start_date")?? $item->start_date;
        $item->end_date = $request->get("end_date")?? $item->end_date;
        $item->start_time = $request->get("start_time")?? $item->start_time;
        $item->end_time = $request->get("end_time")?? $item->end_time;
        $item->end_time = $request->get("end_time")?? $item->end_time;
        $item->notes = $request->get("notes")?? $item->notes;
        $item->type_activity = $request->get("type_activity")?? $item->type_activity;

        if($item->save()){
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS,"Actialización correcta",$id), 200);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {

       $item= AssigmentOfActivity::find($id);

       if($item == null){

           return response()->json(
               new ResponseModel(CodeResponse::ERROR,"Dato no encontrado",$id), 200);
       }
        $item->start_date = $request->get("start_date")?? $item->start_date;
        $item->end_date = $request->get("end_date")?? $item->end_date;
        $item->start_time = $request->get("start_time")?? $item->start_time;
        $item->end_time = $request->get("end_time")?? $item->end_time;

        if($item->save()){
            return response()->json(
                new ResponseModel(CodeResponse::SUCCESS,"Actialización correcta",$id), 200);

        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Int $id)
    {
        $items =AssigmentOfActivity::find($id);


       if( $items->delete()){
           return response()->json(
               new ResponseModel(CodeResponse::SUCCESS,"Eliminado correctamente",$id), 200);
       }else
       {
           return response()->json(
               new ResponseModel(CodeResponse::SUCCESS,"No se pudo eliminar la actividad",$id), 200);
       }
    }



}
