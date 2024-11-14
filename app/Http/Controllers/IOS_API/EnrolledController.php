<?php

namespace App\Http\Controllers\IOS_API;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrolledResource;
use App\Models\tblenroled;
use Illuminate\Http\Request;

class EnrolledController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     //
    // }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show($trainee_id)
    {
        try {
            $enroled_data = tblenroled::where('traineeid', $trainee_id)
                                  ->get();
            
            if(!$enroled_data){
                return response()->json(['message'=> 'Enrollment data not found!'], 404);
            }
            return EnrolledResource::collection($enroled_data);        
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 404);
        }                
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(tblenroled $tblenroled)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, tblenroled $tblenroled)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(tblenroled $tblenroled)
    // {
    //     //
    // }
}
