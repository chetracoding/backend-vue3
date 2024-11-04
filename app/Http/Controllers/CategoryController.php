<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Auth::user()->store->categories->sortByDesc('id');
        return response()->json(["success" => true, "data" => CategoryResource::collection($categories), "message" => "Get all categories successfully."], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        // Check exists aa categories in store
        if (Category::contains('name', $request->name)) {
            return response()->json(['success' => false, 'message' => "The category already exists."], 409);
        } else {
            return response()->json(['success' => true, 'data' => Category::storeCategory($request), 'message' => "Created a new category is successfully.", 'status' => 200]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, int $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        if (!Category::contains('id', $id)) {
            return response()->json(['success' => false, 'message' => "The category is not found."], 404);
        } else if (
            // Check category in store without its self
            Auth::user()->store->categories
            ->where('id', '!=', $id)
            ->where('name', $request->name)->count() > 0
        ) {
            return response()->json(['success' => false, 'message' => "The category already exists."], 409);
        } else {
            return response()->json(['success' => true, 'data' => Category::storeCategory($request, $id), 'message' => "Updated the category is successfully.", 'status' => 200]);
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
        }
        if (!Category::contains('id', $id)) {
            return response()->json(['success' => false, 'message' => "The category is not found."], 404);
        }
        Category::find($id)->delete();
        return response()->json(['success' => true, 'message' => "Delete the category is successfully."], 200);
    }
}
