<?php

namespace App\Http\Controllers;

use App\AssigmentOfActivity;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assigmentOfActivity= new AssigmentOfActivity($request->only(
            ['id_user','type_activity','status_activity','start_date','end_date','start_time','end_time','notes','stated_date','finish_date']
        ));


        $assigmentOfActivity->save();


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
