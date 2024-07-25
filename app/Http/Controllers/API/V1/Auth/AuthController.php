<?php

namespace App\Http\Controllers\ApI\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function register(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('Register_Token')->plainTextToken;

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'User registered successfully',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => 'Server Error',
                    'message' => $e->getMessage(),
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function login(Request $request)
    {
        $Credentials = $request->only('email', 'password');
        $user = User::findByEmail($request->email);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        if (!Auth::attempt($Credentials)) {
            if (!$user || !Hash::check($request['password'], $user->password)) {
                return response()->json(
                    [
                        'error' => 'Server Error',
                        'message' => 'invalid Credentials',
                        'status' => Response::HTTP_UNAUTHORIZED
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('Login_Token')->plainTextToken;
        $user = auth()->user();

        return response()->json(
            [
                'status' => Response::HTTP_OK,
                'message' => 'User logged in successfully',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            Response::HTTP_OK
        );
    }

    public function Logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(
            [
                'status' => Response::HTTP_OK,
                'message' => 'User logged OUT  successfully',
                'token_type' => 'Bearer',
            ],
            Response::HTTP_OK
        );
    }
}
