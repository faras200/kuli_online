<table id="customers" class="datatables" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Tanggal Transaksi Pertama</th>
            <th>Banyaknya Hari</th>
        </tr>

    </thead>
    <tbody>
        
        @foreach ($transactions as $tran)
        
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tran->name }}</td>
                <td>'{{ $tran->identity_card_number }}</td>
                <td>{{ $tran->wilayahname }}</td>
                <td>{{ $tran->first_transaction_date }}</td>
                <td>{{ $tran->days_since_first_transaction }}</td>
            </tr>
        @endforeach
        
    </tbody>
</table>
