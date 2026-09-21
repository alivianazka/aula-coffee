<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKategori;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->paginate(15);
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = BarangKategori::all();
        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:barang_kategori,id',
            'nama' => 'required|string|unique:barang,nama',
            'deskripsi' => 'nullable|string',
            'stok_awal' => 'required|integer|min:0',
            'stok_opname' => 'required|integer|min:0',
            'unit' => 'required|string',
        ]);

        $data = $request->all();
        $data['stok_akhir'] = $request->stok_awal;
        $data['qty'] = $request->stok_awal;

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategori = BarangKategori::all();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:barang_kategori,id',
            'nama' => 'required|string|unique:barang,nama,' . $barang->id,
            'deskripsi' => 'nullable|string',
            'stok_opname' => 'required|integer|min:0',
            'unit' => 'required|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
