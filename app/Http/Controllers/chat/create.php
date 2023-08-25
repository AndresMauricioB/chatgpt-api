<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Models\chat;
use App\Models\message;
use Illuminate\Http\Request;

class create extends Controller
{
    function create(Request $request) {
        $chat = new chat();
        $chat->save();

        return response()->json([
            "success" => true,
            "chat" => $chat,
        ]);
    }

    public function listar(){
        $chats = chat::all();
        //dd($chats); 
        return response()->json([
            "success" => true,
            "chats" => $chats,
        ]);
    }

    public function show(chat $chat){
        $messages = $chat->messages()->get()->toArray();
  
        return response()->json([
            "success" => true,
            "menssages" => $messages,
        ]);
    }

    public function delete(chat $chat){
            $chat->messages()->delete();
            $chat->delete();
            
        return response()->json([
            "success" => true,
        ]);
    }
}
