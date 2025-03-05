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

class KuliController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = 'Kuli';
        $this->middleware([
            'role:author|admin',
            'permission:index admin/kuli|create admin/kuli/create|store admin/kuli/store|edit admin/kuli/edit|update admin/kuli/update|delete admin/kuli/delete|show admin/kuli/show'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            $data = User::where("wilayah_id", $user->wilayah_id)->orderBy("id", "desc");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) {
                    return $row->id;
                })
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '
                    <form action="' . route('kuli.destroy', $row->id) . '" method="POST" class="d-inline delete-data">
                        ' . method_field('DELETE') . csrf_field() . '
                        <div class="btn-group">
                            <a href="' . route('kuli.edit', $row->id) . '" class="btn btn-warning">
                                <i class="fa fa-pencil-alt"></i>
                            </a>
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

        $data = [
            'page_title'    => 'Kuli',
        ];

        return view('backend.kuli.index', $data);
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
        return (new KuliExport($request->dari))->download('kuli' . date('dMY') . '.xlsx');
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
