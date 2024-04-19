<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class KuliExport implements FromView
{
    use Exportable;


    public function view(): View
    {

        $data = User::select("users.*","wilayahs.name as wilayahname")
        ->leftJoin('wilayahs', 'users.wilayah_id', '=', 'wilayahs.id')
        ->where("identity_card_number", "!=", null);

        return view('backend.kuli.excel3', [
            'transactions' => $data->get()
        ]);
    }
}
