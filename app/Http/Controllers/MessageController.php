<?php

namespace App\Http\Controllers;

use App\Message;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use App\Models\RoomChat;
use App\Models\SendMessageModel;
use App\ViewMessageUserFromUserTo;
use App\ViewUserInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @param int $idUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllMessageByUser(int $idUser)
    {

        $items = DB::select('call get_chat_room_user_2_user(?)',array($idUser));
        //$this->formatMessage($items)
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$this->filterChatRoom($items)), 200);

        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $idUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessagePaginateUser(Int $id_user_from,Int $id_user_to,Int $pagination)
    {


        $items = ViewMessageUserFromUserTo::where([

            ['id_user_from', '=', $id_user_from],
            ['id_user_to', '=', $id_user_to],

        ])->orWhere(
            [

                ['id_user_from', '=', $id_user_to],
                ['id_user_to', '=', $id_user_from],

            ]
        )->paginate($pagination);



        $items->each(function ($item){

            $item->photo_user_to =  asset(($item->photo_user_to === null ||$item->photo_user_to=='')?"storage/default_user.png":"storage/".$item->photo_user_to);
            $item->photo_user_from =  asset(($item->photo_user_from === null ||$item->photo_user_from=='')?"storage/default_user.png":"storage/".$item->photo_user_from);

        });
        //$this->formatMessage($items)
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$items), 200);

        //
    }




    public function sendMessage(Request $request)
    {

        $items = DB::select("call add_message_chat_room_user_2_user("
            .$request->get('id_user_from').","
            .$request->get('id_user_to').",'"
            .$request->get('type_message')."','"
            .$request->get("text_message")."')"

        );
        //$this->formatMessage($items)
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$items[0]), 200);

        //
    }


    public function getLastMessageFrom(int $idRoom,int $idUser,string $date,int $id_last_message)
    {
        $items = DB::select(
            "call get_messages_from_next_id(".$idRoom.",".$idUser.",'$date',".$id_last_message.")");

        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$items), 200);
    }

    public function getMessageFromRoom(int $idRoom,int $idUser,string $date)
    {
        $items = DB::select(
            "call get_messages_from_user(".$idRoom.",".$idUser.",'$date')");
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"",$items), 200);
    }




    private function filterChatRoom(array $items){
         $newItems= array();

         $isContain =true;
        foreach ($items as $item){
            foreach ($newItems as $newItemsFilter){
                if($newItemsFilter->id_user_from ==$item->id_user_to){
                    $isContain = false;

                }

            }

            if($isContain){
                array_push($newItems,$item);
                $isContain = true;


            }
            $isContain = true;

        }
        return $newItems;


    }

}
