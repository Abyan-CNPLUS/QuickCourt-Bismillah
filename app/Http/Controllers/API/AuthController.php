<?php

namespace App\Http\Controllers\API;


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

    public function firebaseRegister(Request $request, FirebaseAuth $firebaseAuth)
{
    $idToken = $request->input('idToken');

    if (!$idToken) {
        return response()->json(['error' => 'ID token is required'], 400);
    }

    try {
        $verifiedIdToken = $firebaseAuth->verifyIdToken($idToken);
        $firebaseUid = $verifiedIdToken->claims()->get('sub');

        $firebaseUser = $firebaseAuth->getUser($firebaseUid);

        $name = $request->input('display_name', 'No Name'); // <- pakai nama dari request

        $user = User::where('firebase_uid', $firebaseUid)->first();

        if (!$user) {
            $user = User::create([
                'firebase_uid' => $firebaseUid,
                'email' => $firebaseUser->email,
                'name' => $name,
                'phone' => $request->input('phone', null),
                'role' => $request->input('role', 'user'),
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



}
