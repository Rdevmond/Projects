<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserTemplateExport;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 0)->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'student_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('excel_file'));
            return back()->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
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
