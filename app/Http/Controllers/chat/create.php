<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Models\chat;
use App\Models\message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Type\Integer;

class create extends Controller
{
    
    public function create(Request $request) {
       
        if (Auth::check()) {
            $chat = new Chat();
            $chat->user_id = Auth::id();
            $chat->save();
    
            return response()->json([
                "success" => true,
                "chat" => $chat,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "User is not authenticated.",
            ], 401); // Código de estado 401 indica no autorizado
        }
    }

    public function listar(){
        //$chats = chat::all();
        //dd($chats); 
       $chats = Auth::user()->chats; 
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

    public function user(){
        $userId = Auth::id();
        return response()->json([
            "success" => true,
            "id" => $userId,
        ]);
       
    }
}
