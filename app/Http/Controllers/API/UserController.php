<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();

            $result = array(
                'status' => 'true',
                'message' => count($users) . " User(s) fetched!",
                'data' => $users
            );
            $responseCode = 200; //Success

            return response()->json($result, $responseCode);

        } catch (Exception $e) {
            $result = array(
                'status' => 'false',
                'message' => "API failed due to an errors!",
                'error' => $e->getMessage()
            );
            return response()->json($result, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::pluck('name')->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required",
            "password"=> "required",
        ]);

        if ($validator->fails()) {
            $result = array(
                "status" => "false",
                "message" => "Validation error occured!",
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400); // Bad request
        }

        $product = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password"=> bcrypt($request->password),
        ]);

        if ($product->id) {
            $result = array(
                "status" => "true",
                "message" => "User Created!",
                "data" => $product
            );
            $responseCode = 200;
        } else {
            $result = array(
                "status" => "false",
                "message" => "Something went wrong!"
            );
            $responseCode = 400;
        }

        return response()->json($result, $responseCode);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => "User not Found!",
            ], 404);
        }

        $result = array(
            'status' => 'true',
            'message' => "User Found!",
            'data' => $user
        );
        $responseCode = 200; //Success

        return response()->json($result, $responseCode);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')) {
            if ($user->id != auth()->user()->id) {
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $user = User::find($user);
        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => "Product not Found!",
            ], 404);
        }

        //Validation
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required",
            "password"=> "required",
        ]);

        if ($validator->fails()) {
            $result = array(
                "status" => "false",
                "message" => "Validation error occured!",
                'error_message' => $validator->errors()
            );
            return response()->json($result, 400); // Bad request
        }

        //Update Code
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $result = array(
            "status" => "true",
            "message" => "Product updated Successfully!",
            'data' => $user
        );

        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'false',
                'message' => "User not Found!",
            ], 404);
        }

        $user->delete();
        $result = array(
            'status' => 'true',
            'message' => "User has been deleted Successfully!",
            'data' => $user
        );
        $responseCode = 200; //Success

        return response()->json($result, $responseCode);
    }
}