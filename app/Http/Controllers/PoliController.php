<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Pastikan ini di-import jika berada di namespace yang berbeda

class PoliController extends Controller
{
    /**
     * Menampilkan daftar semua Poli.
     */
    public function index()
    {
        $polis = Poli::all();
        // Memastikan view yang direturn benar: resources/views/admin/polis/index.blade.php
        return view('admin.polis.index', compact('polis'));
    }

    /**
     * Menampilkan form untuk membuat Poli baru.
     */
    public function create()
    {
        return view('admin.polis.create');
    }

    /**
     * Menyimpan Poli yang baru dibuat di database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Poli::create($validated);

        // Menggunakan 'message' dan 'type' untuk konsistensi flash message
        return redirect()->route('polis.index')
            ->with('message', 'Poli berhasil ditambahkan.')
            ->with('type', 'success');
    }

    /**
     * Menampilkan form untuk mengedit Poli yang spesifik.
     */
    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        return view('admin.polis.edit', compact('poli'));
    }

    /**
     * Memperbarui Poli yang spesifik di database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_poli' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $poli = Poli::findOrFail($id);
        $poli->update($validated);

        // Menggunakan 'message' dan 'type' untuk konsistensi flash message
        return redirect()->route('polis.index')
            ->with('message', 'Poli berhasil diupdate.')
            ->with('type', 'success');
    }

    /**
     * Menghapus Poli yang spesifik dari database.
     */
    public function destroy($id)
    {
        $poli = Poli::findOrFail($id);
        $poli->delete(); // PERBAIKAN: Cukup menggunakan delete()

        // Menggunakan 'message' dan 'type' untuk konsistensi flash message
        return redirect()->route('polis.index')
            ->with('message', 'Poli berhasil dihapus.')
            ->with('type', 'success');
    }
}
