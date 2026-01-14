<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index()
    {
        $motors = Motor::latest()->get();
        return view('admin.motor.index', compact('motors'));
    }

    public function create()
    {
        return view('admin.motor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required',
            'nopol' => 'required|unique:motors',
            'harga_sewa' => 'required|numeric',
        ]);

        Motor::create([
            'merk' => $request->merk,
            'nopol' => $request->nopol,
            'harga_sewa' => $request->harga_sewa,
            'status' => 'tersedia', // Default saat input baru
        ]);

        return redirect()->route('motor.index')->with('success', 'Motor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $motor = Motor::findOrFail($id);
        return view('admin.motor.edit', compact('motor'));
    }

    public function update(Request $request, $id)
    {
        $motor = Motor::findOrFail($id);

        $request->validate([
            'merk' => 'required',
            'nopol' => 'required|unique:motors,nopol,' . $motor->id,
            'harga_sewa' => 'required|numeric',
            'status' => 'required'
        ]);

        $motor->update($request->all());

        return redirect()->route('motor.index')->with('success', 'Data motor diperbarui.');
    }

    public function destroy($id)
    {
        $motor = Motor::findOrFail($id);
        
        // Cek jika motor sedang disewa, jangan izinkan hapus
        if ($motor->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Motor tidak bisa dihapus karena sedang disewa/booking.');
        }

        $motor->delete();
        return redirect()->route('motor.index')->with('success', 'Motor berhasil dihapus.');
    }


    //USER Feature
    public function editProfil() {
    $user = Auth::user();
    return view('user.profil', compact('user'));
}

public function updateProfil(Request $request) {
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return back()->with('success', 'Profil Anda berhasil diperbarui.');
}
}