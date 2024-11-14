<?php

namespace App\Http\Controllers\IOS_API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TraineeResource;
use App\Models\tbltraineeaccount;
use Illuminate\Http\Request;

class TraineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($tbltraineeaccount)
    {
        try {
            $trainee_data = tbltraineeaccount::find($tbltraineeaccount);

            if (!$trainee_data) {
                return response()->json(['message' => 'Trainee not found'], 404);
            }

            return TraineeResource::make($trainee_data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()] , 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tbltraineeaccount $tbltraineeaccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tbltraineeaccount $tbltraineeaccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tbltraineeaccount $tbltraineeaccount)
    {
        //
    }
}
