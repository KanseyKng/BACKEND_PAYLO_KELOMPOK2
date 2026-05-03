<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cashflow;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $saldo = $user->saldo->jumlah_saldo ?? 0;

        $start = Carbon::now()->subDays(6)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $dailyData = Cashflow::where('id_user', $user->id_user)
            ->whereBetween('tanggal_dibuat', [$start, $end])
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal_dibuat)->format('Y-m-d'))
            ->map(function ($dayItems) {
                return [
                    'tanggal' => Carbon::parse($dayItems->first()->tanggal_dibuat)->format('d M'),
                    'pemasukan' => $dayItems->where('jenis', 'Pemasukan')->sum('jumlah'),
                    'pengeluaran' => $dayItems->where('jenis', 'Pengeluaran')->sum('jumlah'),
                    'total_transaksi' => $dayItems->count(),
                ];
            })->values();

        $totalTransaksiMinggu = $dailyData->sum('total_transaksi');

        return response()->json([
            'saldo' => $saldo,
            'total_transaksi_minggu' => $totalTransaksiMinggu,
            'grafik_harian' => $dailyData,
        ]);
    }
}