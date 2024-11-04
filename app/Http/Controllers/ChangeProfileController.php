<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChangeProfileController extends Controller
{
    public function updateProfile(ChangeProfileRequest $request, int $id)
    {
        $request['store_id'] = Auth::user()->store->id;
        $request['role_id'] = Auth::user()->role->id;
        $request['password'] = Auth::user()->password;
        return response()->json(['success' => true, 'message' => 'User updated successfully', 'data' => new UserResource(User::storeUser($request, $id))], 200);
    }
}
