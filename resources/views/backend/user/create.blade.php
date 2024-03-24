@extends('layouts.app')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    @endif

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <ul class="nav nav-pills ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#biodata" data-toggle="tab"><i class="far fa-id-card"></i>
                                Biodata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#login" data-toggle="tab"><i class="fas fa-key"></i> Login</a>
                        </li>
                    </ul>
                </h3>
                <div class="card-tools">
                </div>
            </div><!-- /.card-header -->

            <div class="card-body">
                <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div class="chart tab-pane active" id="biodata">
                        <div class="row">
                            <div class="col col-12 col-lg-3">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col col-12 col-lg-3">
                                <div class="form-group">
                                    <label for="identity_card_number">NIK</label>
                                    <input type="number" class="form-control" name="identity_card_number"
                                        id="identity_card_number" value="{{ old('identity_card_number') }}">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-3">
                                <div class="form-group">
                                    <label for="phone">No. HP</label>
                                    <input type="number" class="form-control" name="phone" id="phone"
                                        value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-3">
                                <div class="form-group">
                                    <label for="gender">Jenis Kelamin</label>
                                    <br>
                                    <input type="radio" name="gender" id="gender" value="1" checked> Pria
                                    <input type="radio" name="gender" id="gender" value="0"> Wanita
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea type="address" class="form-control" name="address" id="address" rows="2">{{ old('address') }}</textarea>
                        </div>

                        
                        
                        
                    </div>

                    <div class="chart tab-pane" id="login">
                        <div class="row">
                            <div class="col col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" name="role_id[]" id="role_id" multiple>
                                        <option value="">Silahkan Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col col-12 col-lg-4">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        value="{{ old('password') }}" required autocomplete="false">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" name="submit" id="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                    Simpan</button>
                <button type="reset" name="reset" class="btn btn-danger"><i class="fa fa-sync"></i>
                    Reset</button>
                <a href="{{ route('users.index') }}" name="reset" class="btn btn-dark">
                    <i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </form>
@endsection

@section('script_addon')
    <!-- select2 -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        // Menonaktifkan tombol submit setelah diklik
        $('form').submit(function() {
            $('#submit').attr('disabled', 'disabled');
            return true; // proses form
        });

        $('#role_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Role --",
        });
        $('#province_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Provinsi --",
        });
        $('#city_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kota --",
        });
        $('#district_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kecamatan --",
        });
        $('#village_id').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih Kelurahan / Desa --",
        });

        const profile_image = document.getElementById("profile_image");
        const imagePreview = document.getElementById("imagePreview");

        profile_image.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = "";
                imagePreview.style.display = "none";
            }
        });

        // Show region data
        $(document).ready(function() {
            // show province
            $('#province_id').change(function() {
                var provinceId = $(this).val();
                $.get('/region/getCity', {
                    province_id: provinceId
                }, function(data) {
                    $('#city_id').empty();
                    $('#city_id').append('<option value="">Silahkan Pilih Kota</option>');
                    $.each(data, function(key, value) {
                        $('#city_id').append('<option value="' + value.code + '">' + value
                            .name + '</option>');
                    });
                });
            });

            // show city based on province id
            $('#city_id').change(function() {
                var cityId = $(this).val();
                $.get('/region/getDistrict', {
                    city_id: cityId
                }, function(data) {
                    $('#district_id').empty();
                    $('#district_id').append('<option value="">Silahkan Pilih Kecamatan</option>');
                    $.each(data, function(key, value) {
                        $('#district_id').append('<option value="' + value.code + '">' +
                            value.name + '</option>');
                    });
                });
            });

            // show district based on city code
            $('#district_id').change(function() {
                var districtId = $(this).val();
                $.get('/region/getVillage', {
                    district_id: districtId
                }, function(data) {
                    $('#village_id').empty();
                    $('#village_id').append('<option value="">Silahkan Pilih Kecamatan</option>');
                    $.each(data, function(key, value) {
                        $('#village_id').append('<option value="' + value.code + '">' +
                            value.name + '</option>');
                    });
                });
            });
        });
    </script>
@endsection
