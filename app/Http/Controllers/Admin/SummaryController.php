<?php


namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Exports\KuliExport;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Laravolt\Indonesia\Models\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\KuliUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\Village;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use DB;

class SummaryController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = 'Summary';
        $this->middleware([
            'role:author|admin',
            'permission:index admin/summary|create admin/summary/create|store admin/summary/store|edit admin/summary/edit|update admin/summary/update|delete admin/summary/delete|show admin/summary/show'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Jika request adalah AJAX untuk DataTables
        if ($request->ajax()) {
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
            ->groupBy('users.id', 'users.name', 'users.identity_card_number');

            if ($request->has('dari') && $request->has('sampai')) {
                $data->whereBetween('transactions.tanggal', [$request->dari, $request->sampai]);
            }

            return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && !empty($request->input('search')['value'])) {
                    $searchValue = $request->input('search')['value'];
                    $query->where('users.name', 'like', "%{$searchValue}%");
                }
            })
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', function ($row) {
                return $row->id;
            })
            ->make(true);

        }

        // Set default rentang tanggal
        $hariini = date('Y-m-d');
        $blnawal = '2024-03-25';
        $blnakhir = $hariini;

        $data = [
            'page_title' => 'Kuli',
            'blnawal'    => $blnawal,
            'blnakhir'   => $blnakhir
        ];

        return view('backend.summary.index', $data);
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

        return view('backend.kuli.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $user = Auth::user();
        // return $request;
        $data                = $request->validated();
        $data['created_by']  = auth()->id();
        $data['wilayah_id']  = $user->wilayah_id;

        if ($request->password != null) {
            $data['password'] = Hash::make($request->password);
        }

        $user = User::create($data);
        // Assign roles based on the input role_id
        if ($request->filled('role_id')) {
            $roles = Role::whereIn('id', $request->role_id)->get();
            $user->syncRoles($roles);
        }


        Alert::success('Success', $this->module . ' added successfully.');
        return redirect()->route('kuli.index');
    }

    public function export(Request $request)
    {

        date_default_timezone_set('Asia/Jakarta');
        return (new KuliExport())->download('kuli' . date('dMY') . '.xlsx');
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

        return view('backend.kuli.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KuliUpdateRequest $request, User $kuli)
    {
        $data                = $request->validated();
        $data['updated_by']  = auth()->id();

        // check password field is filled/not
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('profile_image')) {
            // Menghapus file lama jika ada
            if ($kuli->profile_image) {
                Storage::disk('public')->delete('images/users/' . $kuli->profile_image);
            }

            // Menyimpan file baru
            $profile_image = $request->file('profile_image');
            $profile_imageName = now()->format('YmdHis') . '_.' . $profile_image->extension();
            Storage::disk('public')->put('images/users/' . $profile_imageName, file_get_contents($profile_image));
            $data['profile_image'] = $profile_imageName;
        }

        $kuli->update($data);

        if ($request->filled('role_id')) {
            $roles = Role::whereIn('id', $request->role_id)->get();
            $kuli->syncRoles($roles);
        }

        Alert::success('Success', $this->module . ' updated successfully.');
        return redirect()->route('kuli.index');
    }

    public function show()
    {
        $data = [
            'page_title'    => 'Daftar ' . $this->module,
            'users'  => User::getUserList(),
        ];

        if (session('message')) {
            Alert::success('Success', session('message'));
        }

        return view('user.index', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $kuli)
    {
        if (!$kuli) {
            return back()->with('error', $this->module . ' not found.');
        }

        // Mengambil foto profil yang ada sebelumnya
        $profileImage = $kuli->profile_image;

        // Menghapus foto profil yang ada sebelumnya
        if ($profileImage) {
            Storage::disk('public')->delete('images/users/' . $profileImage);
        }

        $kuli->delete();

        Alert::success('Success', $this->module . ' deleted successfully.');
        return redirect()->route('kuli.index');
    }

    
}
