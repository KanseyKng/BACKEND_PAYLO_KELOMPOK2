<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['user:id_user,nama,email', 'penerima:id_user,nama,email']);
        if ($request->has('jenis')) $query->where('jenis_transaksi', $request->jenis);
        if ($request->has('tanggal')) $query->whereDate('tanggal', $request->tanggal);
        if ($request->has('status')) $query->where('status', $request->status);
        return response()->json($query->orderBy('tanggal', 'desc')->paginate(20));
    }

    public function show($id)
    {
        return response()->json(Transaksi::with([
            'user:id_user,nama,email,no_hp', 'penerima:id_user,nama,email,no_hp'
        ])->findOrFail($id));
    }
}