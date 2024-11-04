<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Register new user by admin ----------------
    public function register(RegisterRequest $request)
    {
        $role = Role::find($request->role_id)->name;
        if (Auth::user()->role->name !== 'admin') return response()->json(['success' => false, 'message' => ["roles" => "You don't have permission to access this route."]], 403);
        if ($role === 'admin') return response()->json(['success' => false, 'message' => ["roles" => "You can't asign this role."]], 403);
        return Response()->json(['success' => true, 'message' => 'You have register a new account into the system successfully.', 'data' => new UserResource(User::storeUser($request))], 200);
    }

    // Get user already login ----------------
    public function getUser()
    {
        return response()->json(["success" => true, "data" => new UserResource(Auth::user()), "message" => "Get user login is successfully."], 200);
    }

    // User login ----------------
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'message' => 'Login is successfully.'
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid email or password.'
        ], 401);
    }

    // User logout ---------------
    public function logout(Request $request)
    {
        // The user is logged in, delete their tokens
        $request->user()->tokens()->delete();
        // Return a success JSON response
        return response()->json(['message' => 'Logout is successfully.'], 200);
    }
}
