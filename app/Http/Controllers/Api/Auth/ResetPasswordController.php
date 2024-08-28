<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function recovery(Request $request): JsonResponse
    {

        /* Validation */
        $rules = [

            'username' => 'required',

        ];

        $messages = [

            'username.required' => 'Please enter your username or e-mail',

        ];

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
        }

        /* Find a User */
        $user = User::where(['username' => $request->username])->orWhere(['email' => $request->username])->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'errors' => ['wrong' => 'User not found!']], 422);
        }

        /*

        */
        $token = rand(1000, 9999);
        $token = Hash::make($token);
        $token = md5($token);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $url = env('APP_URL') . '/password/reset/'.$token.'/?email='.urlencode($user->email);
        Mail::send('emails.recovery', ['url' => $url], function($message) {
            $message->to('v71739432@gmail.com')->subject('Password Reset');
        });

        return response()->json(['status' => 'success', 'message' => 'We\'ve just sent a password reset link. Please check your E-mail!'], 200);

    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Password updated!']);
    }


    public function resetPasswordByToken(ResetPasswordRequest $request, $token,): JsonResponse
    {

        $ex = DB::table('password_resets')
            ->where(['token' => $token])
            ->first();
        if (!$ex) {
            return response()->json(['status' => 'error'], 404);
        }

        $email = $ex->email;
        $user = User::where(['email' => $email])->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Password updated!']);

    }

}
