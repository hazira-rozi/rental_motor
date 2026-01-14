<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::where('role', 'pelanggan')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    public function create() {
        return view('admin.user.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pelanggan',
        ]);

        return redirect()->route('user.index')->with('success', 'user baru berhasil ditambahkan.');
    }

    public function edit($id) {
    $user = User::findOrFail($id);
    return view('admin.user.edit', compact('user'));
}

public function update(Request $request, $id) {
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|unique:users,username,' . $user->id,
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6', // Password boleh kosong jika tidak diganti
    ]);

    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
}

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('user.index')->with('success', 'user telah dihapus.');
    }
}