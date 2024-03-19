<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
// use App\Helpers\NavigationHelper;
class DashboardController extends Controller
{
    public function index()
    {
        $kuli = User::whereHas('roles', function ($query) {
            $query->where('name', 'kuli');
        })->get();

        $transaction = Transaction::all();

        $date10 = Carbon::today()->subDays(30)->tz('Asia/Jakarta');

        $tenDaysAgo = $date10;
        $tenDaysAgo->settings(['formatFunction' => 'translatedFormat']);

        $date_array = [];
        $data_date = [];

        for ($i = 0; $i < 31; $i++) {

            $date_array[] = $tenDaysAgo->format('d.m');
            $data_date[] = $tenDaysAgo->format('d F Y');


            $tenDaysAgo->addDay();
        }

        // return $data_date[array_key_first($data_date)];
        $date10 = Carbon::today()->subDays(30)->tz('Asia/Jakarta');
        $charttrx['kuli'] = Transaction::selectRaw('DATE_FORMAT(transactions.tanggal, "%d.%m") as date, COALESCE(SUM(transactions.salary), 0) as total')
            ->whereDate("transactions.tanggal", '>=', $date10)
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date');

        $bulan_terakhir = Carbon::now()->subMonths(11);
        $bulan = [];
        $data_bulan_amount = [];
        $data_bulan_count = [];

        for (
            $i = 0;
            $i < 12;
            $i++
        ) {
            $bulan_sekarang = $bulan_terakhir->copy()->addMonths($i);
            $bulan[] = $bulan_sekarang->format('M Y');

            $data_bulan_amount[] = Transaction::selectRaw('COALESCE(SUM(transactions.salary), 0) as total')
                ->whereDate("transactions.tanggal", '>=', $bulan_sekarang->format('Y-m-1'))
                ->whereDate("transactions.tanggal", '<', $bulan_sekarang->copy()->addMonth()->format('Y-m-1'))
                ->first()->total;
        }

        $data = [
            'page_title'        => 'Dashboard',
            'total_transactions'        => $transaction->count(),
            'total_users'       => $kuli->count(),
            'labels'            => $date_array,
            'charttrx'          => $charttrx,
            'charttrxbulan'     => $data_bulan_amount,
            'bulans'            => $bulan
        ];

        // return $data;

        return view('backend/dashboard/index', $data);
    }
}
