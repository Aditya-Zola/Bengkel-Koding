<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class PoliController extends Controller
{
    /**
     * Menampilkan form pendaftaran Poli untuk pasien.
     */
    public function get()
    {
        $user = Auth::user();

        $polis = Poli::all();

        $jadwals = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwals,
        ]);
    }

    /**
     * Memproses dan menyimpan data pendaftaran Poli.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
        ]);

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        $no_antrian = $jumlahSudahDaftar + 1;

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $no_antrian,
            'tgl_periksa' => now(),
        ]);

        return redirect()->back()->with('message', 'Berhasil Mendaftar ke Poli')->with('type', 'success');
    }
}
