<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Cashflow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SaldoController extends Controller
{
    //mengambil data saldo
    public function show(Request $request)
    {
        $saldo = $request->user()->saldo;
        return response()->json($saldo);
    }

    //untuk top up validasi pin dan tambah jumlah saldo
    //dan menambah data di table transaksi dan cashflow
    public function topup(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
            'pin'    => 'required|digits:6',
        ]);

        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN salah.'], 422);
        }

        $saldo = $user->saldo;
        $saldo->jumlah_saldo += $request->jumlah;
        $saldo->save();

        Transaksi::create([
            'id_user'         => $user->id_user,
            'jenis_transaksi' => 'TopUp',
            'jumlah'          => $request->jumlah,
            'status'          => 'berhasil',
            'tanggal'         => now(),
        ]);

        Cashflow::create([
            'id_user'        => $user->id_user,
            'jenis'          => 'Pemasukan',
            'kategori'       => 'TopUp',
            'jumlah'         => $request->jumlah,
            'tanggal_dibuat' => now(),
        ]);

        return response()->json(['message' => 'Top up berhasil', 'saldo' => $saldo->jumlah_saldo]);
    }

    //melakukan transfer saldo (Tidak boleh ke no_hp yang sama dengan pengirim)
    public function transfer(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'no_hp'  => 'required|exists:users,no_hp',
            'jumlah' => 'required|numeric|min:1000',
            'pin'    => 'required|digits:6',
        ]);

        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN salah.'], 422);
        }

        $saldo = $user->saldo;
        if ($saldo->jumlah_saldo < $request->jumlah) {
            return response()->json(['message' => 'Saldo tidak mencukupi.'], 400);
        }


        //set penerima
        $penerima = User::where('no_hp', $request->no_hp)->first();
        if ($penerima->id_user == $user->id_user) {
            return response()->json(['message' => 'Tidak bisa transfer ke diri sendiri.'], 400);
        }

        $saldo->jumlah_saldo -= $request->jumlah;
        $saldo->save();

        $saldoPenerima = $penerima->saldo;
        $saldoPenerima->jumlah_saldo += $request->jumlah;
        $saldoPenerima->save();

        Transaksi::create([
            'id_user'         => $user->id_user,
            'jenis_transaksi' => 'Kirim_saldo',
            'id_penerima'     => $penerima->id_user,
            'jumlah'          => $request->jumlah,
            'status'          => 'berhasil',
            'tanggal'         => now(),
        ]);

        Cashflow::create([
            'id_user'  => $user->id_user,
            'jenis'    => 'Pengeluaran',
            'kategori' => 'Transfer Keluar',
            'jumlah'   => $request->jumlah,
            'tanggal_dibuat' => now(),
        ]);

        Cashflow::create([
            'id_user'  => $penerima->id_user,
            'jenis'    => 'Pemasukan',
            'kategori' => 'Transfer Masuk',
            'jumlah'   => $request->jumlah,
            'tanggal_dibuat' => now(),
        ]);

        return response()->json(['message' => 'Transfer berhasil', 'saldo_tersisa' => $saldo->jumlah_saldo]);
    }
}