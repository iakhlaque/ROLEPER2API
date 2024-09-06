<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('permission:create-user|edit-user|delete-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['store']]);
        $this->middleware('permission:edit-user', ['only' => ['update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::latest('id')->paginate(10);
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        // Create the user
        $user = User::create($input);
        // Assign role(s) to the user
        $user->assignRole($request->roles);
        // Load roles into the user model to include in the response
        $user->load('roles');

        return response()->json(['message' => 'New user is added successfully.', 'user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        // Load the roles relationship
        $user->load('roles');

        return response()->json([
            'message' => 'User details retrieved successfully.',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $input = $request->all();

        // Check if a new password is provided and hash it
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            // Exclude the password if it's not being updated
            $input = $request->except('password');
        }
        // Update the user details
        $user->update($input);
        // Sync the roles with the provided roles
        $user->syncRoles($request->roles);
        // Load roles to include them in the response
        $user->load('roles');

        return response()->json(['message' => 'User is updated successfully.', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id) {
            return response()->json(['message' => 'USER DOES NOT HAVE THE RIGHT PERMISSIONS'], 403);
        }

        $user->syncRoles([]);
        $user->delete();

        return response()->json(['message' => 'User is deleted successfully.', 'user' => $user]);
    }
}
