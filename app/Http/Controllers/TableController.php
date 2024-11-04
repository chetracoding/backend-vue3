<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTableRequest;
use App\Http\Resources\TableResource;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Auth::user()->store->tables->sortByDesc('id');
        return response()->json(["success" => true, "data" => TableResource::collection($tables), "message" => "Get all tables are successfully."], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTableRequest $request)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        if (Table::contains('table_number', $request->table_number)) {
            return response()->json(['success' => false, 'message' => ["table" => "The table number already exists."]], 409);
        } else {
            return Response()->json(['success' => true, 'message' => 'Create a new table is successfully.', 'data' => new TableResource(Table::storeTable($request))], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTableRequest $request, string $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        if (!Table::contains('id', $id)) {
            return response()->json(['success' => false, 'message' => "The table is not found."], 404);
        } else if (
            // Check table in store without its self
            Auth::user()->store->tables
            ->where('id', '!=', $id)
            ->where('table_number', $request->table_number)->count() > 0
        ) {
            return response()->json(['success' => false, 'message' => ["table" => "The table number already exists."]], 409);
        } else {
            return response()->json(['success' => true, 'data' => Table::storeTable($request, $id), 'message' => "Updated the table is successfully.", 'status' => 200]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        if (Table::contains('id', $id)) {
            Table::find($id)->delete();
            return Response()->json(['success' => true, 'message' => 'Delete the table is successfully.'], 200);
        } else {
            return Response()->json(['success' => false, 'message' => ['table' => 'The table id is not found.']], 404);
        }
    }
}
