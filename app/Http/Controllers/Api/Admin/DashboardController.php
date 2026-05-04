<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //menghitung semua user (jumlah) & user baru di satu minggu
    //menghitung semua transaksi (jumlah) & yang terjadi di satu minggu
    //pertumbuhan user di tiap minggu
    public function index()
    {
        $totalUsers = User::where('role', 'pelanggan/turis')->count();
        $newUsersThisWeek = User::where('role', 'pelanggan/turis')->where('created_at', '>=', Carbon::now()->subWeek())->count();
        $totalTransaksi = Transaksi::count();
        $transaksiThisWeek = Transaksi::where('tanggal', '>=', Carbon::now()->subWeek())->count();

        $userGrowth = User::where('role', 'pelanggan/turis')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(fn($u) => Carbon::parse($u->created_at)->format('Y-m-d'))
            ->map(fn($items) => $items->count());

        return response()->json([
            'total_users' => $totalUsers,
            'new_users_this_week' => $newUsersThisWeek,
            'total_transaksi' => $totalTransaksi,
            'transaksi_this_week' => $transaksiThisWeek,
            'user_growth' => $userGrowth,
        ]);
    }
}