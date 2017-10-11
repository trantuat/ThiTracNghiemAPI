<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Model\Token;
use App\Model\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function OK($data) {
        return response()->json(['data'=>$data], 200);
    }
    public function BadRequest($error) {
        return response()->json(['error'=>$error], 400);
    }

    public function Unauthentication() {
        return response()->json(['error'=>"Token invalidate"], 401);
    }

    public function getRoleId($user_id) {
        $user = User::where('id',$user_id)->first();
        if ($user->role_user_id == 1) {
            return RoleUser::STUDENT;
        }
        if ($user->role_user_id == 2) {
            return RoleUser::TEACHER;
        }
        return RoleUser::ADMIN;
    }

    public function getUserId(Request $request) {
        $token = $request->header('api_token');
        $api_token = Token::where('api_token',$token)->first();
        if ($api_token == null) {
            return -1;
        }
        return  $api_token->user_id;
    }
}

interface RoleUser {
    const STUDENT = 1;
    const TEACHER = 2;
    const ADMIN = 3;
}