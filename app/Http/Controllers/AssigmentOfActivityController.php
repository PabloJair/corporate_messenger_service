<?php

namespace App\Http\Controllers;

use App\AssigmentOfActivity;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
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


        $assigmentOfActivity->save();

        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,"Se agrego correctamente",$assigmentOfActivity), 200);


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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssigmentOfActivity  $assigmentOfActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(AssigmentOfActivity $assigmentOfActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssigmentOfActivity  $assigmentOfActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssigmentOfActivity $assigmentOfActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssigmentOfActivity  $assigmentOfActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssigmentOfActivity $assigmentOfActivity)
    {
        //
    }
}
