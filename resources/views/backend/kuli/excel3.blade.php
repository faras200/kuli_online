<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>LOKASI</th>
        </tr>

    </thead>
    <tbody>
        
        @foreach ($transactions as $tran)
        
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tran->name }}</td>
                <td>'{{ $tran->identity_card_number }}</td>
                <td>{{ $tran->wilayahname }}</td>
                <
            </tr>
        @endforeach
        
    </tbody>
</table>
