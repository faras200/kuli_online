<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class TransactionExport implements FromView
{
    use Exportable;

    private $dari;
    private $sampai;
    private $kuli;

    public function __construct($dari, $sampai, $kuli)
    {
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->kuli = $kuli;
    }

    public function view(): View
    {
        $data = DB::table('transaction_details')
            ->leftJoin('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('users', 'transaction_details.kuli_id', '=', 'users.id')
            ->select('transactions.*', 'users.name as name', 'users.npwp', 'users.identity_card_number', 'transaction_details.salary')
            ->whereBetween('transactions.tanggal', [$this->dari, $this->sampai]);

        // if ($this->kuli != 'all') {
        //     $data->where('transactions.kuli_id', $this->kuli);
        // }

        return view('backend.transaction.excel', [
            'transactions' => $data->get()
        ]);
    }
}
