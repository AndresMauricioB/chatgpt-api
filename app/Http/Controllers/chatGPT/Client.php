<?php

namespace App\Http\Controllers\chatGPT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Client extends Controller
{
    private $privateKey;
    private $modelInstructions;
    private $model;

    function __construct($privateKey, $model, $modelInstructions) {
        $this->privateKey = $privateKey;
        $this->modelInstructions = $modelInstructions;
        $this->model = $model;
    }

    function getAnswer($chat = []) {
        $request = $this->requestChat($chat);
        $response = json_decode($request, true);
        return $response["choices"][0]["message"]["content"];
    }

    function requestChat($chat = []) {
        $messages = [];

        $messages[0] = [
            "role" => "system",
            "content" => $this->modelInstructions,
        ];

        for ($i = 0; $i < count($chat); $i++) {
            $messages[] = [
                "role" => $chat[$i]["role"],
                "content" => $chat[$i]["content"],
            ];
        }

        $data = [
            "model" => $this->model,
            "messages" => $messages
        ];

        $data_string = json_encode($data);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->privateKey,
            'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
