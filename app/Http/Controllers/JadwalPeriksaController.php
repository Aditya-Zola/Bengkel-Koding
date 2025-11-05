<?php

namespace App\Http\Controllers;

use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    /**
     * Menampilkan daftar semua Jadwal Periksa milik dokter yang sedang login.
     */
    public function index()
    {
        // 1. Ambil user dari auth [cite: 1460]
        $dokter = Auth::user();

        // 2. Ambil jadwal periksa hanya milik dokter yang sedang login [cite: 1462]
        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->orderBy('hari')
            ->get();

        // 3. Kembalikan view index dengan data [cite: 1463]
        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    /**
     * Menampilkan form untuk membuat Jadwal Periksa baru.
     */
    public function create()
    {
        return view('dokter.jadwal-periksa.create'); // Menampilkan form input jadwal [cite: 1465, 1471]
    }

    /**
     * Menyimpan Jadwal Periksa yang baru dibuat di database.
     */
    public function store(Request $request)
    {
        // Validasi data input [cite: 1467, 1477]
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Menyimpan data ke tabel jadwal_periksas [cite: 1468, 1490]
        JadwalPeriksa::create([
            'id_dokter' => Auth::id(), // ID Dokter diambil dari user yang login [cite: 1492]
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Data Berhasil di Simpan')
            ->with('type', 'success');
    }

    /**
     * Menampilkan form untuk mengedit Jadwal Periksa yang spesifik.
     */
    public function edit(string $id)
    {
        // Mengambil data jadwal berdasarkan ID [cite: 1498, 1503]
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    /**
     * Memperbarui Jadwal Periksa yang spesifik di database.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data baru [cite: 1499, 1507]
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        // Cari data lama dan update [cite: 1514]
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksa->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Berhasil Melakukan Update Data')
            ->with('type', 'success');
    }

    /**
     * Menghapus Jadwal Periksa yang spesifik dari database.
     */
    public function destroy(string $id)
    {
        // Mencari jadwal periksa berdasarkan ID, kemudian menghapusnya [cite: 1529, 1537]
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksa->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Berhasil Melakukan Hapus Data')
            ->with('type', 'success');
    }
}
