<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 0)->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|unique:users',
            'school'   => 'nullable|string|max:255',
        ]);

        // If password input is empty, generate 8 random characters
        $rawPassword = $request->filled('password') ? $request->password : Str::random(8);

        User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'school'         => $request->school,
            'password'       => Hash::make($rawPassword),
            'plain_password' => Crypt::encryptString($rawPassword),
            'role'           => 0,
        ]);

        return back()->with('success', "Student added! Password: $rawPassword");
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'school'   => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->school = $request->school;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->plain_password = Crypt::encryptString($request->password);
        }

        $user->save();
        return redirect()->route('admin.users.index')->with('success', 'Updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if($user->role == 1) return back()->with('error', 'Cannot delete admin.');
        $user->delete();
        return back()->with('success', 'Student removed.');
    }
}
