<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cashflow;
use Illuminate\Http\Request;

class CashflowController extends Controller
{
    public function laporan(Request $request)
    {
        $user = $request->user();
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);

        $data = Cashflow::where('id_user', $user->id_user)
            ->whereYear('tanggal_dibuat', $tahun)
            ->whereMonth('tanggal_dibuat', $bulanNum)
            ->get();

        $totalPemasukan = $data->where('jenis', 'Pemasukan')->sum('jumlah');
        $totalPengeluaran = $data->where('jenis', 'Pengeluaran')->sum('jumlah');

        $rincianPemasukan = $data->where('jenis', 'Pemasukan')->groupBy('kategori')->map(function ($items) use ($totalPemasukan) {
            $sum = $items->sum('jumlah');
            return [
                'kategori' => $items->first()->kategori,
                'total' => $sum,
                'persentase' => $totalPemasukan > 0 ? round(($sum / $totalPemasukan) * 100, 2) : 0,
            ];
        })->values();

        $rincianPengeluaran = $data->where('jenis', 'Pengeluaran')->groupBy('kategori')->map(function ($items) use ($totalPengeluaran) {
            $sum = $items->sum('jumlah');
            return [
                'kategori' => $items->first()->kategori,
                'total' => $sum,
                'persentase' => $totalPengeluaran > 0 ? round(($sum / $totalPengeluaran) * 100, 2) : 0,
            ];
        })->values();

        return response()->json([
            'bulan' => $bulan,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'pemasukan_detail' => $rincianPemasukan,
            'pengeluaran_detail' => $rincianPengeluaran,
        ]);
    }
}