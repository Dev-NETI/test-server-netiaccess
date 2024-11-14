<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\tbltraineeaccount;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateTraineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __invoke(LoginRequest $request)
    {
        $trainee = User::where('email', $request->email)->first();

        if (!$trainee || !Hash::check($request->password, $trainee->password)) {
            return response()->json([
                'message' => 'The credentials you entered are incorrect!'
            ]);
        }

        return response()->json([
            'user' => $trainee,
            'token' => $trainee->createToken('laravel_api_token')->plainTextToken
        ]);
    }
}
