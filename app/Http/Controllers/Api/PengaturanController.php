<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return response()->json(PengaturanSistem::first());
    }

    public function update(Request $request)
    {
        $pengaturan = PengaturanSistem::first();
        $pengaturan->update($request->only('batas_transfer', 'biaya_admin', 'nama_aplikasi'));
        return response()->json(['message' => 'Pengaturan berhasil diperbarui.', 'data' => $pengaturan]);
    }
}