<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConnectController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }

        $token = JWTAuth::attempt($credentials);
        // return $token;
        if($token){
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => User::where('email', $credentials['email'])->first()
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Por favor verifica tus credenciales',
                'error' => ['revise sus credenciales']
            ],401);
        }
        
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        try{

            $token = JWTAuth::refresh($token);

            return response()->json([
                'success' => true,
                'token' => $token,

            ],200);
        }catch(TokenExpiredException $ex){
            return response()->json([
                'success' => false,
                'errors' => $ex,
                'message' => 'su token ha expirado'
            ],422);
        }catch(TokenBlacklistedException $ex){
            return response()->json([
                'success' => false,
                'errors' => $ex,
                'message' => 'necesito que te loguees de nuevo (BlackListed)'
            ],422);
        }
    }

    public function expireToken()
    {
        $token = JWTAuth::getToken();
        try{
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'se cerro su sesion con exito',
            ],200);
        }catch(JWTException $ex){
            return response()->json([
                'success' => false,
                'message' => 'no se pudo cerrar su sesion, intentalo nuevamente',
                'errors' => $ex
            ],422);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
