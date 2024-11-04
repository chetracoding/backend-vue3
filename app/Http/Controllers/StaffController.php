<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        } else {
            $users = Auth::user()->store->users->where('id', '!==', Auth::user()->id)->sortByDesc('id');;
            return response()->json(["success" => true, "data" => UserResource::collection($users), "message" => "Get all staff are successfully."], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        } else {
            // Check asign role id
            $role = Role::find($request->role_id)->name;
            if ($role === 'admin' || $role === 'restaurant_owner') {
                return response()->json(['success' => false, 'message' => ["roles" => "You can't asign this role."]], 412);
            } else {
                $request['store_id'] = Auth::user()->store->id;
                return response()->json(['success' => true, 'message' => 'Created a new staff in the restaurant is successfully.', 'data' => new UserResource(User::storeUser($request))], 200);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, int $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        } else {
            // Check asign role id
            $role = Role::find($request->role_id)->name;
            if ($role === 'admin' || $role === 'restaurant_owner') {
                return response()->json(['success' => false, 'message' => ["roles" => "You can't asign this role."]], 412);
            } else {
                $request['store_id'] = Auth::user()->store->id;
                return response()->json(['success' => true, 'message' => 'Updated the staff in the restaurant is successfully.', 'data' => new UserResource(User::storeUser($request, $id))], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        } else {
            if (User::contains('id', $id)) {
                User::find($id)->delete();
                return Response()->json(['success' => true, 'message' => 'Delete the staff is successfully.'], 200);
            } else {
                return Response()->json(['success' => false, 'message' => ['staff' => 'The staff id is not found.']], 404);
            }
        }
    }
}
