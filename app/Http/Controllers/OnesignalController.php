<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOnesignalRequest;
use App\Models\Onesignal;
use Illuminate\Http\Request;

class OnesignalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOnesignalRequest $request)
    {
        $userOneSignal =  Onesignal::create($request->all());
        return response()->json(["success" => true, "data" => $userOneSignal, "message" => "Create a user onesignal is successfully."], 200);
    }
}