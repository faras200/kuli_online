@extends('layouts.app')

<style type="text/css">
    .btn {

        padding: 0.1rem 0.5rem;
    }

    #dataTable td,
    #dataTable th {
        padding: 0.4rem;
    }
</style>

@section('content')
    @if ($message = Session::get('message'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <style>
        .header-fixed {
            width: 100%
        }

        .header-fixed>thead,
        .header-fixed>tbody,
        .header-fixed>thead>tr,
        .header-fixed>tbody>tr,
        .header-fixed>thead>tr>th,
        .header-fixed>tbody>tr>td {
            display: block;
        }

        .header-fixed>tbody>tr:after,
        .header-fixed>thead>tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }

        .header-fixed>tbody {
            overflow-y: auto;
            height: 250px;
        }

        .header-fixed>tbody>tr>td,
        .header-fixed>thead>tr>th {

            float: left;
        }
    </style>

    <div class="card">
        <div class="card-header">

            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <div class="col-6 d-flex align-items-center">
                        <button onclick="Tambah()" type="button" class="btn btn-primary mr-2">
                            <i class="fa fa-plus"></i>
                            Add Data
                        </button>
                        <button id="cetakexcel" type="button" class="btn btn-success mr-2">
                            <i class="fa fa-print"></i>
                            Print Excel
                        </button>

                    </div>
                    <div class="col-6 d-flex justify-content-between">
                        <div class="col-4">
                            <label>Filter Category</label>
                            <div style="font-size: 17px;">
                                <select class="form-control" id="filterkuli">
                                    <option value="all">All</option>
                                    <option value="beras">Kuli Beras</option>
                                    <option value="padi">Kuli Padi</option>
                                    <option value="peletsekam">Kuli Pelet/Sekam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Dari Tanggal :</label>
                            <input type="date" class="form-control" value="{{ $blnawal }}" id="dari">
                        </div>
                        <div class="col-4">
                            <label>Sampai Tanggal :</label>
                            <input type="date" class="form-control" value="{{ $blnakhir }}" id="sampai">
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTablexx" class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Total Upah</th>
                                <th>Kuli</th>
                                <th>Kategori Kuli</th>
                                <th>Lokasi</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tfoot class="table-dark">
                            <tr>
                                <th></th>
                                <th></th>
                                <th id="totalSalaryFooter"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- /.card-body -->
        </div>


        <div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="modal-default"
            aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 class="modal-title" id="modal-title-default">Tambah Transaksi</h3>
                        <hr>
                    </div>
                    <form id="form" action="">
                        <div class="modal-body" style="padding-bottom: 0px;padding-top: 0px;">
                            <div class="row">
                                <div class="col-12">
                                    <label>Kategori Kuli</label>
                                    <div style="font-size: 17px;">
                                        <select class="form-control" id="categorykuli">
                                            <option value="beras">Kuli Beras</option>
                                            <option value="padi">Kuli Padi</option>
                                            <option value="peletsekam">Kuli Pelet/Sekam</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <label>Tanggal</label>
                                    <br>
                                    <input type="date" id="tanggal" required class="form-control">
                                    <hr>
                                    <label>Upah</label>
                                    <br>
                                    <input type="upah" id="upah" required class="form-control">
                                    <hr>
                                    <label>Nama Kuli</label>
                                    <br>
                                    <div class="row mb-3">
                                        <div class="col-md-9">
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Search...">
                                        </div>
                                        <div class="col-md-3">
                                            <div id="contcheck" style="font-size: 18px;font-weight:bold;">0</div>
                                            <div style="font-size: 11px;">Kuli Yang Dipilih</div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="dataTable2" class="table table-striped header-fixed">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 30%">#</th>
                                                    <th style=" width: 70%;">Nama Kuli</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($kuli as $sal)
                                                    <tr>
                                                        <td style="width: 30%"><input type="checkbox" class="kuliCheckbox"
                                                                name="kuli" value="{{ $sal->id }}"></td>
                                                        <td style="width: 70%">{{ $sal->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer tombolnya2">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-secondary btn-block ml-auto"
                                            data-dismiss="modal">Tutup</button>
                                    </td>
                                    <td width="5%">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <button type="button" onclick="Simpan();"
                                            class="btn btn-primary btn-block btn-absen ml-auto menusxx">Simpan</button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modal-default"
            aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 class="modal-title" id="modal-title-default">Ubah Transaksi</h3>
                        <hr>
                    </div>
                    <form id="formedit" action="">
                        <div class="modal-body" style="padding-bottom: 0px;padding-top: 0px;">
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" id="editid" value="" class="form-control">
                                    <label>Kategori Kuli</label>
                                    <div style="font-size: 17px;">
                                        <select class="form-control" id="editcategorykuli">

                                        </select>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-9">
                                            <input type="text" id="searchInput3" class="form-control"
                                                placeholder="Search...">
                                        </div>
                                        <div class="col-md-3">
                                            <div id="contcheck3" style="font-size: 18px;font-weight:bold;">0</div>
                                            <div style="font-size: 11px;">Kuli Yang Dipilih</div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="dataTable3" class="table table-striped header-fixed">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 30%">#</th>
                                                    <th style=" width: 70%;">Nama Kuli</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($kuli as $sal)
                                                    <tr>
                                                        <td style="width: 30%"><input type="checkbox" name="editkuli" 
                                                                value="{{ $sal->id }}" class="kuliCheckbox3"
                                                                id="editkuli{{ $sal->id }}">
                                                        </td>
                                                        <td style="width: 70%">{{ $sal->name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <label>Upah</label>
                                    <br>
                                    <input type="text" id="editupah" value="" required class="form-control">
                                    <hr>
                                    <label>Tanggal</label>
                                    <br>
                                    <input type="date" id="edittanggal" value="" required class="form-control">
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer tombolnya2">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-secondary btn-block ml-auto"
                                            data-dismiss="modal">Tutup</button>
                                    </td>
                                    <td width="5%">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <button type="button" onclick="Update();"
                                            class="btn btn-primary btn-block btn-absen ml-auto menusxx">Simpan</button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="show" tabindex="-1" role="dialog" aria-labelledby="modal-default"
            aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 class="modal-title" id="modal-title-default">Detail Transaksi</h3>
                        <hr>
                    </div>
                    <form id="formedit" action="">
                        <div class="modal-body" style="padding-bottom: 0px;padding-top: 0px;">
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" id="editid" value="" class="form-control">
                                    <label>Kategori Kuli</label>
                                    <br>
                                    <div style="font-size: 17px;" id="showcategory"> </div>
                                    <hr>
                                    <label>Tanggal</label>
                                    <br>
                                    <div style="font-size: 17px;" id="showtanggal"> </div>
                                    <hr>
                                    <div class="table-responsive">
                                        <table id="dataTable4" class="table table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 10%">#</th>
                                                    <th style=" width: 40%;">Nama Kuli</th>
                                                    <th style=" width: 40%;">Upah</th>

                                                </tr>
                                            </thead>
                                            <tbody id="showcontentnya">

                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer tombolnya2">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-secondary btn-block ml-auto"
                                            data-dismiss="modal">Tutup</button>
                                    </td>

                                </tr>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('script_addon')
        <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#searchInput').on('keyup', function() {
                    var searchText = $(this).val().toLowerCase();
                    $('#dataTable2 tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1)
                    });
                });

                $('.kuliCheckbox').on('change', function() {
                    var countChecked = $('input.kuliCheckbox:checked').length;

                    $('#contcheck').html(countChecked)
                });

                $('#searchInput3').on('keyup', function() {
                    var searchText = $(this).val().toLowerCase();
                    $('#dataTable3 tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1)
                    });
                });

                $('.kuliCheckbox3').on('change', function() {
                    var countChecked = $('input.kuliCheckbox3:checked').length;

                    $('#contcheck3').html(countChecked)
                });

                
            });

            var table;

            $(document).ready(function() {

                var table = $('#dataTablexx').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('transactions.index') }}',
                        data: function(d) {
                            d.kuli = $('#filterkuli').val();
                            d.dari = $('#dari').val();
                            d.sampai = $('#sampai').val();
                        }
                    },
                    columnDefs: [{
                        "targets": [2],
                        "render": $.fn.dataTable.render.number(',', '.', 0, 'Rp. ')
                    }],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: 'text-center'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'total_salary',
                            name: 'total_salary'
                        },
                        {
                            data: 'CountKuli',
                            name: 'CountKuli'
                        },
                        {
                            data: 'category',
                            name: 'category'
                        },
                        {
                            data: 'wilayah_name',
                            name: 'wilayah_name'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    ],
                    aaSorting: [
                        [0, 'desc']
                    ],
                    oLanguage: {
                        "sEmptyTable": "Belum ada data",
                        "sProcessing": "Sedang memproses...",
                        "sLengthMenu": "Tampilkan _MENU_ data",
                        "sZeroRecords": "Tidak ditemukan data yang sesuai",
                        "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                        "sInfoFiltered": "(disaring dari total _MAX_ data)",
                        "sSearch": "Cari:",
                        "oPaginate": {
                            "sFirst": "Pertama",
                            "sPrevious": "Sebelumnya",
                            "sNext": "Selanjutnya",
                            "sLast": "Terakhir"
                        }
                    },
                    lengthMenu: [
                        [10, 25, 50, 100, 500, 1000, -1],
                        [10, 25, 50, 100, 500, 1000, "Semua"]
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        total = api
                            .column(2)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $('#totalSalaryFooter').html(
                            'Rp. ' + total.toLocaleString('id-ID')
                        );
                    }
                });

                $('#dari').on('change', function() {
                    table.ajax.reload();
                });

                $('#sampai').on('change', function() {

                    table.ajax.reload();

                });

                $('#filterkuli').on('change', function() {

                    table.ajax.reload();

                });
            });

        </script>

        <script type="text/javascript">
            $('#edit').on('hidden.bs.modal', function() {
                $('#editkuli').empty();
            });

            $('#kuli').select2({
                theme: 'bootstrap4',
                placeholder: "-- Pilih Kuli --",
                dropdownParent: '#new',
            });

            function Tambah() {
                $('#new').modal('show');
            }

            function edit(id) {
                $('#edit').modal('show');

                $('.loading').attr('style', 'display: block');
                $.ajax({
                    type: 'GET',
                    url: "{{ route('transactions.show') }}",
                    data: {
                        'id': id,
                    },
                    success: function(response) {
                        var kuli = {{ Js::from($kuli) }};
                        var content_data = '';
                        $('#editupah').val(formatRupiah1(response[0].total_salary));
                        $('#edittanggal').val(response[0].tanggal);
                        $('#editid').val(response[0].id);
                        $('#editcategorykuli').html(
                            ' <option ' + (response[0].category == 'beras' ? 'selected' : '') +
                            ' value="beras">Kuli Beras</option><option ' + (response[0].category == 'padi' ?
                                'selected' : '') +
                            ' value="padi">Kuli Padi</option><option ' + (response[0].category == 'peletsekam' ?
                                'selected' : '') + ' value="peletsekam">Kuli Pelet/Sekam</option>'
                        );

                        $.each(response, function(index, data) {
                            $('#editkuli' + data.kuli_id).attr('checked', true);
                        });

                        $('#buttonupdate').html(
                            '<button type="button" onclick="Update(' + response[0].id +
                            ');" class="btn btn-block btn-absen ml-auto menusxx">Update</button>'
                        )

                        $('#contcheck3').html(response.length);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        NotifError()
                    },
                    complete: function(response) {
                        $('.loading').attr('style', 'display: none');
                    }
                });
            }

            function show(id) {
                $('#show').modal('show');

                $('.loading').attr('style', 'display: block');
                $.ajax({
                    type: 'GET',
                    url: "{{ route('transactions.show') }}",
                    data: {
                        'id': id,
                    },
                    success: function(response) {
                        var content_data = '';
                        $('#showtanggal').html(response[0].tanggal);
                        $('#showcategory').html(response[0].category);

                        var content_data = '';
                        var no = 0;



                        $.each(response, function(index, data) {

                            content_data += '<tr>';
                            content_data += '<td>' + (no += 1) + '</td>';
                            content_data += '<td>' + (data.name ?? '0') + '</td>';
                            content_data += '<td style="text-align:right;">' + formatRupiah1(data.salary ??
                                '0') + '</td>';
                            content_data += '</tr>';

                        });
                        content_data += "<tr>"
                        content_data += "<th colspan='2'>GRANDTOTAL</th>"
                        content_data += '<th style="text-align:right;" colspan="2" id="grandtotal">' +
                            formatRupiah1(
                                response[0].total_salary ?? '0') +
                            '</th>'
                        content_data += "</tr>"
                        $('#showcontentnya').html(content_data);
                        $('#buttonupdate').html(
                            '<button type="button" onclick="Update(' + response.id +
                            ');" class="btn btn-block btn-absen ml-auto menusxx">Update</button>'
                        )
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        NotifError()
                    },
                    complete: function(response) {
                        $('.loading').attr('style', 'display: none');
                    }
                });
            }

            function Simpan() {
                for (const el of document.getElementById('form').querySelectorAll("[required]")) {
                    if (!el.reportValidity()) {
                        return 0;
                    }
                }

                swal({
                    title: "Yakin simpan data??",
                    text: "Pastikan data-data sudah benar!",
                    icon: "warning",
                    buttons: ["Cancel", "Yakin"],
                }).then((willDelete) => {
                    if (willDelete) {
                        var rupiah = $('#upah').val();

                        var selectedKuli = new Array();
                        $('input[name="kuli"]:checked').each(function() {
                            selectedKuli.push(this.value);
                        });

                        if (selectedKuli.length == 0) {
                            NotifWarning('Kuli Harus Di Pilih')
                            return 0;
                        }

                        $('.loading').attr('style', 'display: block');
                        $('#new').modal('hide');
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('transactions.store') }}",
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                                'kuli': selectedKuli,
                                'category': $('#categorykuli').val(),
                                'salary': parseInt(rupiah.replace(/Rp\.|\./g, '')),
                                'tanggal': $('#tanggal').val(),
                            },
                            success: function(data) {
                                swal({
                                    title: "Berhasil",
                                    text: "Data sudah berhasil Di Simpan!",
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                                setTimeout(function() {
                                    window.location.href = '/admin/transactions';
                                }, 1500);
                                $('.loading').attr('style', 'display: none');
                                table.ajax.reload();
                                
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                NotifError()
                            },
                            complete: function(response) {
                                $('#tambah').modal('hide');
                                $('#upah').val('')
                                $('#tanggal').val('')
                                $('#deskripsi').val('')
                                $('#kuli').val(null)
                            }
                        });
                    }
                });
            }

            function Update() {
                for (const el of document.getElementById('formedit').querySelectorAll("[required]")) {
                    if (!el.reportValidity()) {
                        return 0;
                    }
                }

                swal({
                    title: "Yakin simpan data??",
                    text: "Pastikan data-data sudah benar!",
                    icon: "warning",
                    buttons: ["Cancel", "Yakin"],
                }).then((willDelete) => {
                    if (willDelete) {
                        $('.loading').attr('style', 'display: block');
                        var rupiah = $('#editupah').val();
                        var kuli = $('#editkuli').val();

                        var selectededitKuli = new Array();
                        $('input[name="editkuli"]:checked').each(function() {
                            selectededitKuli.push(this.value);
                        });

                        if (selectededitKuli.length == 0) {
                            NotifWarning('Kuli Harus Di Pilih')
                            return 0;
                        }

                        $('#edit').modal('hide');

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('transactions.update') }}",
                            data: {
                                '_token': $('meta[name="csrf-token"]').attr('content'),
                                'kuli': selectededitKuli,
                                'salary': parseInt(rupiah.replace(/Rp\.|\./g, '')),
                                'tanggal': $('#edittanggal').val(),
                                'category': $('#editcategorykuli').val(),
                                'id': $('#editid').val(),
                            },
                            success: function(data) {
                                swal({
                                    title: "Berhasil",
                                    text: "Data sudah berhasil Di Update!",
                                    icon: "success",
                                    buttons: false,
                                    timer: 1000,
                                });
                                setTimeout(function() {
                                    window.location.href = '/admin/transactions';
                                }, 1500);
                                $('.loading').attr('style', 'display: none');
                                table.ajax.reload();
                                $('#edit').modal('hide');
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                NotifError()
                            },
                            complete: function(response) {
                                $('#edit').modal('hide');
                                $('#editupah').val('')
                                $('#edittanggal').val('')
                                $('#editdeskripsi').val('')
                                $('#editid').val('')
                                $('#editkuli').val(null)
                            }
                        });
                    }
                });
            }

            $("#cetakexcel").click(() => {
                const dari = $('#dari').val();
                const sampai = $('#sampai').val();
                const kuli = $('#filterkuli').val();
                window.location.href = `/admin/transactions/print-excel?dari=${dari}&sampai=${sampai}&kuli=${kuli}`;
            });


            function NotifError(texts) {
                swal({
                    title: "Kesalahan!!",
                    text: "Ada kesalahan, coba lagi nanti!",
                    icon: "error",
                    buttons: false,
                    timer: 2000,
                });
                $('.loading').attr('style', 'display: none');
            }

            function NotifWarning(texts) {
                swal({
                    title: "Peringatan!!",
                    text: texts,
                    icon: "warning",
                    buttons: false,
                    timer: 2000,
                });
                $('.loading').attr('style', 'display: none');
            }

            var rupiah = document.getElementById('editupah');
            rupiah.addEventListener('keyup', function(e) {
                rupiah.value = formatRupiah(this.value, 'Rp. ');
            });

            var tanpa_rupiah = document.getElementById('upah');
            tanpa_rupiah.addEventListener('keyup', function(e) {
                tanpa_rupiah.value = formatRupiah(this.value, 'Rp. ');
            });

            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }

            function formatRupiah1(angka) {
                let reverse = angka.toString().split('').reverse().join('');
                let ribuan = reverse.match(/\d{1,3}/g);
                let hasil = ribuan.join('.').split('').reverse().join('');
                return 'Rp. ' + hasil;
            }
        </script>
    @endsection
