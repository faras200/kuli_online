<table id="customers" class="datatables" width="100%">
    <thead>
        <tr>
            <th rowspan="1" style="background-color: #dcdcdc;">No</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Tanggal</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Nama Kuli</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Upah</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Deskripsi</th>
            {{-- <th rowspan="1" style="background-color: #dcdcdc;">Amount</th> --}}
        </tr>

    </thead>
    <tbody>
        @foreach ($transactions as $tran)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tran->tanggal }}</td>
                <td>{{ $tran->name }}</td>
                <td>{{ $tran->salary }}</td>
                <td>{{ $tran->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
