<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Motor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    /**
     * ==========================================================
     * BAGIAN ADMIN (MANAJEMEN TRANSAKSI)
     * ==========================================================
     */

    // 1. Tampilkan Semua Data Peminjaman
    public function index() {
        // Logika Sortir: Status 'menunggu' paling atas, sisanya berdasarkan tanggal terbaru
        $rentals = Rental::with(['user', 'motor'])
            ->orderByRaw("FIELD(status_transaksi, 'menunggu', 'proses', 'selesai', 'ditolak')")
            ->latest()
            ->get();

        return view('admin.rentals.index', compact('rentals'));
    }

    // 2. Setujui Peminjaman (Menunggu -> Proses)
    public function approve($id) {
        $rental = Rental::findOrFail($id);

        if ($rental->status_transaksi == 'menunggu') {
            $rental->update(['status_transaksi' => 'proses']);
            return back()->with('success', 'Peminjaman disetujui. Motor resmi digunakan pelanggan.');
        }

        return back()->with('error', 'Aksi tidak valid untuk status ini.');
    }

    // 3. Tolak Peminjaman (Menunggu -> Ditolak)
    public function reject($id) {
        $rental = Rental::with('motor')->findOrFail($id);

        if ($rental->status_transaksi == 'menunggu') {
            // Gunakan Transaction agar aman
            DB::transaction(function () use ($rental) {
                // A. Ubah status sewa jadi ditolak
                $rental->update(['status_transaksi' => 'ditolak']);
                
                // B. Kembalikan stok motor jadi tersedia
                $rental->motor->update(['status' => 'tersedia']);
            });

            return back()->with('success', 'Peminjaman ditolak. Stok motor dikembalikan.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }

    // 4. Proses Pengembalian Motor (Proses -> Selesai + Hitung Denda)
    public function returnMotor($id) {
        $rental = Rental::with('motor')->findOrFail($id);

        if ($rental->status_transaksi == 'proses') {
            // A. Hitung Durasi
            $tgl_pinjam = Carbon::parse($rental->tgl_pinjam);
            $tgl_kembali = now(); // Waktu saat tombol diklik
            
            // Hitung selisih hari. Jika < 1 hari, dianggap 1 hari.
            $durasi = $tgl_pinjam->diffInDays($tgl_kembali);
            if ($durasi < 1) { $durasi = 1; }

            // B. Hitung Biaya
            $total_bayar = $durasi * $rental->motor->harga_sewa;

            // C. Simpan Perubahan
            DB::transaction(function () use ($rental, $tgl_kembali, $durasi, $total_bayar) {
                $rental->update([
                    'tgl_kembali' => $tgl_kembali,
                    'lama_sewa' => $durasi,
                    'total_bayar' => $total_bayar,
                    'status_transaksi' => 'selesai',
                ]);

                // D. Buka kunci motor
                $rental->motor->update(['status' => 'tersedia']);
            });

            return back()->with('success', "Motor dikembalikan. Total bayar: Rp " . number_format($total_bayar));
        }

        return back()->with('error', 'Motor belum disetujui atau sudah selesai.');
    }

    //5. Laporan
    
    public function laporan()
    {
        // Biasanya laporan hanya menampilkan transaksi yang sudah 'selesai'
        $rentals = Rental::with(['user', 'motor'])
                    ->where('status_transaksi', 'selesai')
                    ->latest()
                    ->get();

        // Anda bisa menggunakan view yang sama dengan index, atau buat view khusus cetak
        return view('admin.rentals.laporan', compact('rentals'));
    }


    /**
     * ==========================================================
     * BAGIAN PELANGGAN (FRONT END USER)
     * ==========================================================
     */

    // 1. Dashboard User (Katalog Motor)
    public function userDashboard() {
        // Hanya tampilkan motor yang tersedia
        $motors = Motor::where('status', 'tersedia')->get();
        return view('user.dashboard', compact('motors'));
    }

    // 2. Proses Pengajuan Sewa (User Klik Tombol Sewa)
    public function pinjam(Request $request) {
        // A. Validasi Input
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
        ]);

        // B. Cek Ketersediaan (Double check)
        $motor = Motor::find($request->motor_id);
        if ($motor->status != 'tersedia') {
            return back()->with('error', 'Maaf, motor ini baru saja diambil orang lain.');
        }

        // C. Simpan Data
        DB::transaction(function () use ($request, $motor) {
            Rental::create([
                'user_id' => Auth::id(),
                'motor_id' => $request->motor_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'status_transaksi' => 'menunggu', // Status awal
            ]);

            // Kunci motor sementara menunggu persetujuan admin
            $motor->update(['status' => 'disewa']);
        });

        return redirect()->route('user.history')->with('success', 'Pengajuan berhasil! Mohon tunggu persetujuan Admin.');
    }

    // 3. Riwayat Transaksi User
    public function history() {
        // Ambil data milik user yang sedang login saja
        $myRentals = Rental::with('motor')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Gunakan nama variabel 'myRentals' agar sesuai dengan View Anda
        return view('user.history', compact('myRentals'));
    }

    

    // Cetak Struk

public function printStruk($id)
{
    $rental = Rental::with(['user', 'motor'])->findOrFail($id);
    
    // --- SECURITY CHECK ---
    // Jika yang login bukan admin DAN bukan pemilik transaksi, tolak akses.
    if (Auth::user()->role !== 'admin' && $rental->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Pastikan hanya yang sudah selesai yang bisa dicetak
    if($rental->status_transaksi != 'selesai'){
        return back()->with('error', 'Transaksi belum selesai.');
    }

    // Kita gunakan view yang sama untuk admin maupun user
    return view('admin.rentals.struk', compact('rental'));
}
}