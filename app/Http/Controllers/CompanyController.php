<?php

namespace App\Http\Controllers;

use App\Company;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use App\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {


        $data =new Company($request->all());


        $validate=$data->validate($request->only(['name_company','description_company']));

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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $data = Company::find($id);

        if(!$data){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"The data not exist"), 200);


        }
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,$data), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {

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
        $data =new Company($request->all());


        $validate=$data->validateUpdated($request->only(['name_company','description_company','logotype_company']));

        if($validate->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Validate your field",$data,$validate->messages()), 200);
        }
        else{

            $findData = Company::find($id);
            $findData-> name_company = $data->name_company;
            $findData-> description_company = $data->description_company;
            $findData-> icon_company = $data->icon_company;

            if($findData->save()){
                return response()->json(
                    new ResponseModel(CodeResponse::SUCCESS,$findData), 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $data = Company::find($id);

        if(!$data){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Company not exist"), 200);
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
