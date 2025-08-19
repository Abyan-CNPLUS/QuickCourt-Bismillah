<?php

namespace App\Http\Controllers\API;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth as FirebaseAuth;

class AuthController extends Controller
{
    public function firebaseLogin(Request $request, FirebaseAuth $firebaseAuth)
    {
        $idToken = $request->input('idToken');

        if (!$idToken) {
            return response()->json(['error' => 'ID token is required'], 400);
        }

        try {

            $verifiedIdToken = $firebaseAuth->verifyIdToken($idToken);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');


            $firebaseUser = $firebaseAuth->getUser($firebaseUid);


            $name = $firebaseUser->displayName ?? explode('@', $firebaseUser->email)[0];


            $user = User::where('firebase_uid', $firebaseUid)->first();

            if (!$user) {
                $user = User::create([
                    'firebase_uid' => $firebaseUid,
                    'email' => $firebaseUser->email,
                    'name' => $name,
                    'phone' => $firebaseUser->phoneNumber ?? null,
                    'role' => 'user',
                ]);
            }


            $user->tokens()->delete();


            $token = $user->createToken('firebase-login-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Invalid Firebase ID token',
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    // public function firebaseRegister(Request $request)
    // {
    //     Log::info('firebaseRegister called with data:', $request->all());

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:6',
    //         'phone' => 'nullable|string',
    //         'name' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         \Log::warning('Validation failed:', $validator->errors()->toArray());
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     try {
    //         $user = User::create([
    //             'name' => $request->input('name', 'No Name'),
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'phone' => $request->phone,
    //             'role' => 'user',
    //         ]);

    //         \Log::info('User created:', ['user_id' => $user->id]);

    //         $token = $user->createToken('api-token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'User registered successfully',
    //             'user' => $user,
    //             'token' => $token,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Error creating user:', ['error' => $e->getMessage()]);
    //         return response()->json(['error' => 'Failed to create user'], 500);
    //     }
    // }


}
