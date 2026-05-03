<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['umkm', 'kategori']);
        if ($request->has('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }
        if ($request->has('id_umkm')) {
            $query->where('id_umkm', $request->id_umkm);
        }
        return response()->json($query->orderBy('nama_produk')->get());
    }

    public function show($id)
    {
        return response()->json(Produk::with(['umkm', 'kategori'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_umkm' => 'required|exists:umkm,id_umkm',
            'nama_produk' => 'required|string|max:255',
            'gambar' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:kategori_produk,id_kategori',
        ]);
        $produk = Produk::create($request->all());
        return response()->json(['message' => 'Produk berhasil ditambahkan.', 'data' => $produk], 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $request->validate([
            'id_umkm' => 'sometimes|exists:umkm,id_umkm',
            'nama_produk' => 'sometimes|string|max:255',
            'gambar' => 'nullable|string',
            'harga' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'sometimes|exists:kategori_produk,id_kategori',
        ]);
        $produk->update($request->only('id_umkm','nama_produk','gambar','harga','deskripsi','id_kategori'));
        return response()->json(['message' => 'Produk berhasil diperbarui.', 'data' => $produk]);
    }

    public function destroy($id)
    {
        Produk::destroy($id);
        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}