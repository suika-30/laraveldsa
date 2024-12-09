<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $login = User::all();
        return response(['users'=>$login]);
    }

    // Register user
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            //'additional_patient_data' => 'nullable|string'
        ]);
        //create user
        $user = User::create([
            'name' => $validator['name'],
            'email' => $validator['email'],
            'password' => bcrypt($validator['password']),
        ]);

        // $user->patient()->create([
        //     'additional_patient_data' => 'Default patient data', // Replace with actual data as needed
        // ]);

        return response()->json(["response" => "Record saved"], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('userToken')->plainTextToken;
            return response()->json([
                'message' => 'successful',
                'user' => $user,
                'token' => $token 
            ],200);
        }
            return response()->json(['message' => 'failed'], 401);
        
    }


        // Validate incoming request
        /**$credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (User::attempt($credentials)) {
            // Authentication passed, generate token
            $user = User::user();
            $token = $user->createToken('c_pharm')->plainTextToken;

            // Return token in the response
            return response()->json([
                'message' => 'Login successful',
                'token' => $token
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);**/

}