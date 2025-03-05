@extends('layouts.app')
<style type="text/css">
    .btn {

        padding: 0.1rem 0.5rem;
    }

    #dataTable td, #dataTable th {
    padding: 0.4rem;
}


</style>
@section('content')
    @if ($message = Session::get('message'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
        <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <div class="col-8 d-flex align-items-center">
                        
                        <button id="cetakexcel" type="button" class="btn btn-success mr-2">
                            <i class="fa fa-print"></i>
                            Print Excel
                        </button>

                    </div>
                    <div class="col-4 d-flex justify-content-between">
                        <div class="col-12">
                            <label>Dari Tanggal :</label>
                            <input type="date" class="form-control" value="{{ $blnawal }}" id="dari">
                            <input type="hidden" class="form-control" value="{{ $blnakhir }}" id="sampai">
                        </div>
                        
                    </div>
                </div>

            </div>
            <!-- /.card-header -->
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <th>No.</th>
                        <th>Name</th>    
                        <th>NIK</th>
                        <th>Tanggal Masuk Pertama</th>
                        <th>Banyaknya Hari</th>
                        <th>Tanggal Terakhir</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('script_addon')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript">

        var table;

        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('summary.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.dari = $('#dari').val();
                        d.sampai = $('#sampai').val();
                        console.log("Sending request with:", d); // Debug request data
                    },
                    error: function(xhr, error, thrown) {
                        console.error("AJAX Error:", xhr.responseText); // Debug error
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center' },
                    { data: 'name', name: 'name' },
                    { data: 'identity_card_number', name: 'identity_card_number' },
                    { 
                        data: 'first_transaction_date', 
                        name: 'first_transaction_date',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var options = { day: '2-digit', month: 'short', year: 'numeric' };
                                return date.toLocaleDateString('id-ID', options);
                            }
                            return '-';
                        }
                    },
                    { data: 'days_since_first_transaction', name: 'days_since_first_transaction' },
                    { 
                        data: 'last_transaction_date', 
                        name: 'last_transaction_date',
                        render: function(data, type, row) {
                            if (data) {
                                var date = new Date(data);
                                var options = { day: '2-digit', month: 'short', year: 'numeric' };
                                return date.toLocaleDateString('id-ID', options);
                            }
                            return '-';
                        }
                    },
                ],
                order: [[0, 'desc']],
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
            });

            // Fungsi reload table
            function reload_table() {
                table.ajax.reload(null, false);
            }

            $('#dari').on('change', function() {
                table.ajax.reload();
            });

            // Tombol cetak Excel

            $("#cetakexcel").click(() => {
                const dari = $('#dari').val();
                window.location.href = `/admin/kuli/print-excel?dari=${dari}`;
            });
        });
    </script>
@endsection
