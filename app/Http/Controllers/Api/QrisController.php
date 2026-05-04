<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Qris;
use App\Models\UMKM;
use App\Models\Transaksi;
use App\Models\Cashflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QrisController extends Controller
{
    //membuat kode qr untuk usr yang misal belum punya saat login
    public function generate(Request $request)
    {
        $user = $request->user();
        if ($user->qris) {
            return response()->json(['kode_qr' => $user->qris->kode_qr]);
        }
        $kode = 'QR-' . strtoupper(Str::random(10));
        $qris = Qris::create(['id_user' => $user->id_user, 'kode_qr' => $kode]);
        return response()->json(['kode_qr' => $qris->kode_qr]);
    }

    public function scan(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'kode_qr' => 'required|string',
            'jumlah'  => 'required|numeric|min:100',
            'pin'     => 'required|digits:6',
        ]);

        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN salah.'], 422);
        }

        $saldo = $user->saldo;
        if ($saldo->jumlah_saldo < $request->jumlah) {
            return response()->json(['message' => 'Saldo tidak mencukupi.'], 400);
        }

        //jika bukan milih UMKM cek di qr pelanggan/turis
        $qris = Qris::where('kode_qr', $request->kode_qr)->first();
        if (!$qris) {
            return response()->json(['message' => 'QR tidak valid.'], 404);
        }

        $penerima = $qris->user;
        if ($penerima->id_user == $user->id_user) {
            return response()->json(['message' => 'Tidak bisa scan QR sendiri.'], 400);
        }

        //proses 
        $saldo->jumlah_saldo -= $request->jumlah;
        $saldo->save();

        $saldoPenerima = $penerima->saldo;
        $saldoPenerima->jumlah_saldo += $request->jumlah;
        $saldoPenerima->save();

        Transaksi::create([
            'id_user' => $user->id_user, 'jenis_transaksi' => 'Kirim_saldo',
            'id_penerima' => $penerima->id_user, 'jumlah' => $request->jumlah, 'status' => 'berhasil', 'tanggal' => now()
        ]);
        Cashflow::create([
            'id_user' => $user->id_user, 'jenis' => 'Pengeluaran', 'kategori' => 'Transfer Keluar',
            'jumlah' => $request->jumlah, 'tanggal_dibuat' => now()
        ]);
        Cashflow::create([
            'id_user' => $penerima->id_user, 'jenis' => 'Pemasukan', 'kategori' => 'Transfer Masuk',
            'jumlah' => $request->jumlah, 'tanggal_dibuat' => now()
        ]);

        return response()->json(['message' => 'Pembayaran QRIS berhasil (transfer)', 'saldo' => $saldo->jumlah_saldo]);
    }
}