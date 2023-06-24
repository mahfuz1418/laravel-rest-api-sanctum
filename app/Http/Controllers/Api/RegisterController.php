<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'             => 'required|max:255',
            'email'            => 'required|max:255|email|unique:users',
            'password'         => 'required|max:12|min:6',
            'confirm_password' => 'required|max:12|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error! ', $validator->errors());
        }

        $password = bcrypt($request->password);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
        ]);

        $success['token'] = $user->createToken('RestApi')->plainTextToken;
        $success['name'] = $user->name;

        return $this->successResponse($success, 'User Registrated Succressfully'); 
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Email Or Password Does Not Match', $validator->errors());
        }
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('RestApi')->plainTextToken;

            return $this->successResponse($success, 'Login successfully');
        } else {
            return $this->sendError('Unauthorized Login', ['Error' => 'Unauthorized']);
        }
        
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->successResponse([], 'Loged out!');
    }
}
