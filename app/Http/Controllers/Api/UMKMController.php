<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use Illuminate\Http\Request;

class UMKMController extends Controller
{
    public function index()
    {
        return response()->json(UMKM::with('produk')->orderBy('nama_umkm')->get());
    }

    public function show($id)
    {
        return response()->json(UMKM::with('produk')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'deskripsi' => 'nullable|string',
            'link_lokasi_umkm' => 'nullable|url',
            'kode_qr' => 'required|string|unique:umkm,kode_qr',
            'rating' => 'nullable|in:1,2,3,4,5',
        ]);
        $umkm = UMKM::create($request->all());
        return response()->json(['message' => 'UMKM berhasil ditambahkan.', 'data' => $umkm], 201);
    }

    public function update(Request $request, $id)
    {
        $umkm = UMKM::findOrFail($id);
        $request->validate([
            'nama_umkm' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string',
            'no_hp' => 'sometimes|string|max:15',
            'deskripsi' => 'nullable|string',
            'link_lokasi_umkm' => 'nullable|url',
            'kode_qr' => 'sometimes|string|unique:umkm,kode_qr,'.$umkm->id_umkm.',id_umkm',
            'rating' => 'nullable|in:1,2,3,4,5',
        ]);
        $umkm->update($request->only(['nama_umkm','alamat','no_hp','deskripsi','link_lokasi_umkm','kode_qr','rating']));
        return response()->json(['message' => 'UMKM berhasil diperbarui.', 'data' => $umkm]);
    }

    public function destroy($id)
    {
        UMKM::destroy($id);
        return response()->json(['message' => 'UMKM berhasil dihapus.']);
    }
}