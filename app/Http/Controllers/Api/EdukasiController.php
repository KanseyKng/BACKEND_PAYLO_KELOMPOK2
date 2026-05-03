<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Edukasi;
use Illuminate\Http\Request;

class EdukasiController extends Controller
{
    //ambil data edukasi yang ada, dan diurutkan dari yang terbaru
    public function index()
    {
        return response()->json(Edukasi::orderBy('tanggal_dibuat', 'desc')->get());
    }


    //menampilkan detail eduaksi (Dari mengambil seluruh kolom)
    public function show($id)
    {
        return response()->json(Edukasi::findOrFail($id));
    }

    //untuk menambah edkasi baru (untuk admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_edukasi' => 'required|string',
        ]);
        $edukasi = Edukasi::create($data);
        return response()->json(['message' => 'Edukasi berhasil ditambahkan.', 'data' => $edukasi], 201);
    }

    //untuk update edukasi yang sudah ada (untuk admin)
    public function update(Request $request, $id)
    {
        $edukasi = Edukasi::findOrFail($id);
        $edukasi->update($request->only('judul', 'isi_edukasi'));
        return response()->json(['message' => 'Edukasi berhasil diperbarui.', 'data' => $edukasi]);
    }

    //menghapus edukasi (untuk admin)
    public function destroy($id)
    {
        Edukasi::destroy($id);
        return response()->json(['message' => 'Edukasi berhasil dihapus.']);
    }
}