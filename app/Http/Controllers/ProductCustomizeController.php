<?php

namespace App\Http\Controllers;

use App\Models\ProductCustomize;
use App\Models\User;
use Illuminate\Http\Request;

class ProductCustomizeController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // Check the user permission
        if (!User::roleRequired('restaurant_owner')) {
            return response()->json(['success' => false, 'message' => "The user don't have permisstion to this route."], 403);
        }
        if (ProductCustomize::find($id)) {
            ProductCustomize::find($id)->delete();
            return Response()->json(['success' => true, 'message' => 'Delete the product customize is successfully.'], 200);
        } else {
            return Response()->json(['success' => false, 'message' => ['product_customize' => 'The product customize id is not found.']], 404);
        }
    }
}
