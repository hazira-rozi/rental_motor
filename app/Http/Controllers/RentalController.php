<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    /** --- FITUR ADMIN --- **/

    public function index() {
        $rentals = Rental::with(['user', 'motor'])->latest()->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    // Fitur Baru: Form Tambah Transaksi oleh Admin
    public function create() {
        $motors = Motor::where('status', 'tersedia')->get();
        $users = User::where('role', 'pelanggan')->get();
        return view('admin.rentals.create', compact('motors', 'users'));
    }

    // Fitur Baru: Simpan Transaksi dari Admin
    public function storeAdmin(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'motor_id' => 'required',
            'tgl_pinjam' => 'required|date',
        ]);

        Rental::create([
            'user_id' => $request->user_id,
            'motor_id' => $request->motor_id,
            'tgl_pinjam' => $request->tgl_pinjam,
            'status_transaksi' => 'proses', // Admin buat biasanya langsung jalan
        ]);

        Motor::where('id', $request->motor_id)->update(['status' => 'disewa']);

        return redirect()->route('admin.laporan')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function returnMotor($id) {
        $rental = Rental::with('motor')->findOrFail($id);
        $tgl_kembali = now();
        $tgl_pinjam = Carbon::parse($rental->tgl_pinjam);
        
        // Hitung Lama Peminjaman (Hari)
        $durasi = $tgl_pinjam->diffInDays($tgl_kembali) ?: 1; 

        $rental->update([
            'tgl_kembali' => $tgl_kembali,
            'lama_pinjam' => $durasi, // Simpan lama pinjam
            'total_bayar' => $durasi * $rental->motor->harga_sewa,
            'status_transaksi' => 'selesai',
        ]);
        
        $rental->motor->update(['status' => 'tersedia']);
        return redirect()->back()->with('success', "Motor Kembali. Lama sewa: $durasi Hari.");
    }

    public function edit($id) {
        $rental = Rental::findOrFail($id);
        $motors = Motor::all();
        $users = User::where('role', 'pelanggan')->get();
        return view('admin.rentals.edit', compact('rental', 'motors', 'users'));
    }

    public function update(Request $request, $id) {
        $rental = Rental::findOrFail($id);
        $rental->update($request->all());
        return redirect()->route('admin.laporan')->with('success', 'Data diperbarui.');
    }

    public function destroy($id) {
        $rental = Rental::findOrFail($id);
        if ($rental->status_transaksi != 'selesai') {
            $rental->motor->update(['status' => 'tersedia']);
        }
        $rental->delete();
        return redirect()->back()->with('success', 'Transaksi dihapus.');
    }

    /** --- FITUR PELANGGAN --- **/
    public function userDashboard() {
        $motors = Motor::where('status', 'tersedia')->get();
        return view('user.dashboard', compact('motors'));
    }

    public function history() {
        $rentals = Rental::with('motor')->where('user_id', Auth::id())->latest()->get();
        return view('user.history', compact('rentals'));
    }
}