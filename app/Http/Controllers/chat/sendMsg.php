<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\chatGPT\Client;
use App\Http\Controllers\Controller;
use App\Models\chat;
use App\Models\message;
use Illuminate\Http\Request;

class sendMsg extends Controller
{
    function send(Request $request, chat $chat) {
        $prompt = $request->input("prompt") ?? "";
        $message = new message();
        $message->content = $prompt;
        $message->role = "user";
        $message->chat_id = $chat->id;
        $message->save();

        $messageHistory = $chat->messages()->get()->toArray();

        $chatGpt = new Client("k-7T7Zk5LayKleO2TzcsgGT3BlbkFJRmTQPVi9SaaK79lPsL2U", "gpt-3.5-turbo", "la clase de arquitectura en ingenieria es en la sala A este semestre");
        $answer = $chatGpt->getAnswer($messageHistory);

        $answerMsg = new message();
        $answerMsg->content = $answer;
        $answerMsg->role = "assistant";
        $answerMsg->chat_id = $chat->id;
        $answerMsg->save();

        $messageHistory = $chat->messages()->get()->toArray();

        return response()->json([
            "success" => true,
            "answer" => $answer,
            "history" => $messageHistory,
        ]);
    }
}