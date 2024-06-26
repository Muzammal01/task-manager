<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json(['users' => $users], 200);
    }

    // Show a specific user
    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return response()->json(['user' => $user], 200);
    }

    // Create a new user
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return response()->json(['user' => $user], 201);
    }

    // Update an existing user
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        $user->update($data);

        return response()->json(['user' => $user], 200);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
