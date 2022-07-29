<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public function ResetPassword(ResetRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        //cek email
        $checkEmail = DB::table('password_resets')->where('email', $email)->first();
        $pinCheck = DB::table('password_resets')->where('token', $token)->first();

        if(!$checkEmail){
            return response([
                'message' => "Email not found"
            ], 401);
        }

        if(!$pinCheck)
        {
            return response([
                'message' => "pin code invalid"
            ], 401);
        }

        // update password jik semua benar
        DB::table('users')->where('email', $email)->update(['password' => $password]);

        // hapus password reset
        DB::table('password_resets')->where('email', $email)->delete();

        return response([
            'message' => "password change successfully",
        ], 200);
    }
}
