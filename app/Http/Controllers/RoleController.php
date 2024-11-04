<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = (Auth::user()->role === 'admin')? Role::where('name', '!=', 'admin')->get() : 
                 Role::where([['name', '!=', 'admin'], ['name', '!=', 'restaurant_owner']])->get();
        return response()->json(["success" => true, "data" => RoleResource::collection($roles), "message" => "Get all roles are successfully."], 200);
    }
}