<?php

namespace App\Http\Controllers;

use App\Message;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use App\Models\RoomChat;
use App\Models\SendMessageModel;
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
