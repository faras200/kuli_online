<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;


class KuliExport implements FromView
{
    use Exportable;

    private $dari;

    public function __construct($dari)
    {
        $this->dari = $dari;

    }

    public function view(): View
    {
        $user = Auth::user();

        $data = User::select(
            'users.id',
            'users.name',
            'users.identity_card_number',
            \DB::raw('MIN(transactions.tanggal) as first_transaction_date'),
            \DB::raw('MAX(transactions.tanggal) as last_transaction_date'),
            \DB::raw('COUNT(DISTINCT transactions.tanggal) as days_since_first_transaction')
        )
        ->join('transaction_details', 'users.id', '=', 'transaction_details.kuli_id')
        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
        ->where('transactions.wilayah_id', $user->wilayah_id)
        ->groupBy('users.id', 'users.name', 'users.identity_card_number')
        ->whereBetween('transactions.tanggal', [$this->dari, date('Y-m-d')]);

        return view('backend.kuli.excel3', [
            'transactions' => $data->get()
        ]);
    }
}
