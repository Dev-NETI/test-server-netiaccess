<?php

namespace App\Http\Controllers\IOS_API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\tblcourses;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_course = tblcourses::all();

        return CourseResource::collection($all_course);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $course_data = tblcourses::find($id);

            if(!$course_data){
                return response()->json(['message' => 'Course data not found!'], 404);
            }

            return CourseResource::make($course_data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
    
}
