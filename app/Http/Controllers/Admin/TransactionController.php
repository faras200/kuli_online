<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionExport;
use App\Models\User;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Laravolt\Indonesia\Models\City;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Village;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use App\Http\Requests\KuliUpdateRequest;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = 'Transaksi';
        $this->middleware([
            'role:author|admin',
            'permission:index admin/transactions|create admin/transactions/create|store admin/transactions/store|edit admin/transactions/edit|update admin/transactions/update|delete admin/transactions/delete|show admin/transactions/show'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('transactions')
                ->leftJoin('users', 'transactions.kuli_id', '=', 'users.id')
                ->select('transactions.*', 'users.name as name')
                ->whereBetween('transactions.tanggal', [$request->dari, $request->sampai]);

            if ($request->kuli != 'all') {
                $data->where('transactions.kuli_id', $request->kuli);
            }

            return Datatables::of($data->get())
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) {
                    return $row->id;
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                    <form action="' . route('transactions.destroy', $row->id) . '" method="POST" class="d-inline delete-data">
                        ' . method_field('DELETE') . csrf_field() . '
                        <div class="btn-group">
                            <button type="button" onclick="edit(' . $row->id . ')" class="btn btn-warning">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                            <button type="submit" class="btn btn-danger" title="Delete">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $kuli = User::whereHas('roles', function ($query) {
            $query->where('name', 'kuli');
        });
        // Set date variables
        $hariini = date('Y-m-d');
        $blnawal = date('Y-m-01', strtotime($hariini));
        $blnakhir = date('Y-m-t', strtotime($hariini));

        $data = [
            'page_title'    => 'Transaksi',
            'kuli'          => $kuli->get(),
            'blnawal'       => $blnawal,
            'blnakhir'      => $blnakhir
        ];

        return view('backend.transaction.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'page_title'    => 'Tambah ' . $this->module,
            'roles'         => Role::orderBy('name')->get(),
            'provinces'     => Province::orderBy('name')->get(),
        ];

        return view('backend.transaction.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data                = $request->all();

        DB::table('transactions')->insert([
            'kuli_id'       => $data['kuli_id'],
            'tanggal'       => $data['tanggal'],
            'salary'        => $data['salary'],
            'description'   => $data['description'],
            'created_at'    => now(),
        ]);
        Alert::success('Success', $this->module . ' created successfully.');
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $kuli)
    {

        $data = [
            'page_title'    => 'Edit Data ' . $this->module,
            'users'         => $kuli,
            'userRole'      => $kuli->roles->pluck('name')->toArray(),
            'roles'         => Role::orderBy('name')->get(),
            'provinces'     => Province::orderBy('name')->get(),
            'cities'        => City::where('province_code', $kuli->province_id)->orderBy('name')->get(),
            'districts'     => District::where('city_code', $kuli->city_id)->orderBy('name')->get(),
            'villages'      => Village::where('district_code', $kuli->district_id)->orderBy('name')->get(),
        ];

        return view('backend.transaction.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data                = $request->all();

        DB::table('transactions')->where('id', $data['id'])->update([
            'kuli_id'       => $data['kuli_id'],
            'tanggal'       => $data['tanggal'],
            'salary'        => $data['salary'],
            'description'   => $data['description'],
            'created_at'    => now(),
        ]);

        Alert::success('Success', $this->module . ' updated successfully.');
        return response()->json($data);
    }

    public function show(Request $request)
    {
        $data = DB::table('transactions')->leftJoin('users', 'transactions.kuli_id', '=', 'users.id')->select('transactions.*', 'users.name as name')->where('transactions.id', $request->id)->first();

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if (!$transaction) {
            return back()->with('error', $this->module . ' not found.');
        }

        $transaction->delete();

        Alert::success('Success', $this->module . ' deleted successfully.');
        return redirect()->route('transactions.index');
    }

    public function export(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        return (new TransactionExport($request->dari, $request->sampai, $request->kuli))->download('transaksi' . date('dMY') . '.xlsx');
    }
}