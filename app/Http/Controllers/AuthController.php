<?php

namespace App\Http\Controllers;

use App\Http\Requests\Socks5Request;
use App\Services\TrialPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Payment;
use Password;
use Mail;
use DB;

/**
 * @deprecated
 */
class AuthController extends Controller {

    /**
     * @deprecated
     */
	public function login(Request $request) {

		/* Validation */
		$rules = [

			'username' => 'required',
			'password' => 'required',

		];

		$messages = [

			'username.required' => 'Please enter your username',
			'password.required' => 'Please enter password',

		];

		$validation = Validator::make($request->all(), $rules, $messages);
		if ($validation->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
		}

		/* Attempt to login */
		$credentials = $request->only('username', 'password');

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();
			return response()->json(['status' => 'success', 'data' =>$user]);

		}

		return response()->json(['status' => 'error', 'errors' => ['wrong' => 'Wrong username or password']], 401);

	}

    /**
     * @deprecated
     */
	public function register(Request $request) {

		/* Validation */
		$rules = [

			'name' => 'required',
			'email' => 'required|email|unique:users',
			'username' => 'required|unique:users',
			'password' => 'required|min:6|confirmed',

		];

		$messages = [

			'name.required' => 'Please enter your name',
			'email.required' => 'Please provide your email',
			'email.email' => 'Wrong Email',
			'email.unique' => 'You cannot use this Email',
			'username.required' => 'Please enter your username',
			'username.unique' => 'The username is busy',
			'password.required' => 'Please enter password',
			'password.confirmed' => 'Please confirm your password',

		];

		$validation = Validator::make($request->all(), $rules, $messages);
		if ($validation->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
		}

		/* */
		$new = new User;

		$new->name = $request->name;
		$new->username = $request->username;
		$new->email = $request->email;
		$new->password = Hash::make($request->password);

		$new->save();

        $trialService = new TrialPeriodService($new);
        $trialService->createTrialPeriod();

		return response()->json(['status' => 'success', 'message' => 'Your account has been created!'], 200);

	}

    /**
     * @deprecated
     */
	public function recovery(Request $request) {

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

		$url = 'https://shopper.devinit.site/password/reset/'.$token.'/?email='.urlencode($user->email);
		Mail::send('emails.recovery', ['url' => $url], function($message) {
			$message->to('v71739432@gmail.com')->subject('Password Reset');
		});

		return response()->json(['status' => 'success', 'message' => 'We\'ve just sent a password reset link. Please check your E-mail!'], 200);

	}

	public function topup(Request $request) {

		/* Validation */
		$rules = [

			'amount' => 'required',

		];

		$messages = [

			'amount.required' => 'Please enter amount to deposit!',

		];

		$validation = Validator::make($request->all(), $rules, $messages);
		if ($validation->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
		}

		$data = [

			'user_id' => Auth::user()->id,
			'is_deposit' => true,
			'amount' => $request->amount,
			'status' => 1,

		];

		$payment = new Payment;
		$payment->fill($data);
		$payment->save();

		return response()->json(['status' => 'success', 'message' => 'Thank you! Your deposit is confirmed!'], 200);

	}

    /**
     * @deprecated
     */
	public function socks(Socks5Request $request): JsonResponse
    {

        Auth::user()->update([
            'socks5_username' => $request->input('username'),
            'socks5_password' => $request->input('password')
        ]);

		return response()->json(['status' => 'success', 'message' => 'Credentials updated!'], 200);

	}

    /**
     * @deprecated
     */
    public function getSocks()
    {

        $user = Auth::user();

        if (!is_null($user)) {
            $json = [

                'status' => 'success',
                'result' => [
                    'username' => $user->socks5_username,
                    'password' => $user->socks5_password,
                ],

            ];
            return response()->json($json);
        }
        return response()->json(['status' => 'error', 'errors' => ['unauthorized'] ], 401);

    }

    /**
     * @deprecated
     */
	public function password(Request $request) {

		/* Validation */
		$rules = [

			'password' => 'required|confirmed',

		];

		$messages = [

			'password.required' => 'Please enter new password',
			'password.confirmed' => 'Please confirm your new password',

		];

		$validation = Validator::make($request->all(), $rules, $messages);
		if ($validation->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
		}

		$user = User::find(Auth::user()->id);
		$user->password = Hash::make($request->password);
		$user->save();

		return response()->json(['status' => 'success', 'message' => 'Password updated!'], 200);

	}

    /**
     * @deprecated
     */
	public function reset($token, Request $request) {

		$ex = DB::table('password_resets')->where('token', '=', $token)->first();
		if (!$ex) {
			return abort(404);
		}

		$email = $ex->email;
		$user = User::where(['email' => $email])->first();

		/* Validation */
		if ($request->isMethod('post')) {

			$rules = [

				'password' => 'required|confirmed',

			];

			$messages = [

				'password.required' => 'Please enter new password',
				'password.confirmed' => 'Please confirm your new password',

			];

			$validation = Validator::make($request->all(), $rules, $messages);
			if ($validation->fails()) {
				return response()->json(['status' => 'error', 'errors' => $validation->errors()], 422);
			}

			$user = User::find($user->id);
			$user->password = Hash::make($request->password);
			$user->save();

			return response()->json(['status' => 'success', 'message' => 'Password updated!'], 200);

		}

		return view('root');

	}

    /**
     * @deprecated
     */
	public function logout(Request $request) {

		Auth::logout();
		return redirect('/');

	}

}
