<?php

namespace ConsultaPessoaFisica\Utils\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Validate;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'login',
            'register'
        ]]);
    }

    public function register(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));

            $user->save();

            return response()->json(['success' => true, 'data' => $user], 201);

        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $credentials = $request->only(['email', 'password']);

            if (! $token = \Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autorizado',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => ['token' => $token]
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function me()
    {
        try{
            return response()->json(['success' => true, 'data' => Auth::user()], 200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
        
    }

    public function checkUserAuth()
    {
        return response()->json(['success' => true], 200);
    }
}
