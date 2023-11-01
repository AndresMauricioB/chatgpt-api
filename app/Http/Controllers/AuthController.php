<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function check(Request $request) {
        if (Auth::check()) {
            return response()->json([
                "success" => true,
                "user" => Auth::user(),
            ]);
        } else {
            return response()->json([
                "success" => false,
            ]);
        }
    }

    public function login(Request $request) {
        try {
            $email = $request->input()["email"] ?? "";
            $password = $request->input()["password"] ?? "";

            if (trim($email) === "" || trim($password) === "") {
                return response()->json([
                    "success" => false,
                    "error_code" => "fields_required"
                ]);
            }

            $user = User::where('email', '=', $email)->first();

            if ($user === null || !Hash::check($password, $user->password)) {
                return response()->json([
                    "success" => false,
                    "error_code" => "wrong_credentials"
                ]);
            }

            Auth::login($user);
            
            return response()->json([
                "success" => true,
                "user" => $user,
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "error_code" => "server_error",
                "msg" => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "success" => false,
                "error_code" => "server_error",
                "msg" => $e->getMessage(),
            ]);
        }
    }
}
