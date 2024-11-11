<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;

class UserController extends Controller
{
    public function index()
    {
        return User::with('roles')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role_id);

        // Send email with credentials
        // Mail::to($user->email)->send(new UserCredentialsMail($user, $request->password));

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'role_id' => 'sometimes|required|exists:roles,id',
        ]);

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->all());

        if ($request->has('role_id')) {
            $user->roles()->sync([$request->role_id]);
        }

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();

        return response()->json(null, 204);
    }
}
