<?php

namespace App\Http\Controllers;

use App\Area;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use Illuminate\Http\Request;

class AreaController extends Controller
{



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        return response()->json(
            new ResponseModel(CodeResponse::SUCCESS,"Success Save",Area::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {


        $data =new Area($request->all());


        $validate=$data->validate($request->only(['name_area','description_area']));

        if($validate->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Validate your field",$data,$validate->messages()), 200);
        }
        else{

            if($data->save()){
                return response()->json(
                    new ResponseModel(CodeResponse::SUCCESS,$data,"Success Save"), 200);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = Area::find($id);

        if(!$data){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"The data not exist"), 200);


        }
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,$data), 200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $data =new Area($request->all());


        $validate=$data->validateUpdated($request->only(['name_area','description_area','id_area']));

        if($validate->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Validate your field",$data,$validate->messages()), 200);
        }
        else{

            $findData = Area::find($id);
            $findData-> name_area = $data->name_area;
            $findData-> description_area = $data->description_area;
            $findData-> icon_area = $data->icon_area;

            if($findData->save()){
                return response()->json(
                    new ResponseModel(CodeResponse::SUCCESS,$findData), 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        $data = Area::find($id);

        if(!$data){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Area not exist"), 200);
        }
        else{
           $result = $data->delete();

            if($result){
                return response()->json(
                    new ResponseModel(CodeResponse::SUCCESS,"Delete success"), 200);
            }
        }
    }
}
