<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    use ValidatesRequests;

    public function postLogin(Request $request){
        try{
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return Auth::user()->createToken('authToken')->plainTextToken;
        }

        return response(['error' => 'Invalid credentials'], 401);
    }

    public function postRegister(Request $request){
        try{
            $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = $user->createToken('authToken');

        return $token->plainTextToken;
    }



}
