<table id="customers" class="datatables" width="100%">
    <thead>
        <tr>
            <th rowspan="1" style="background-color: #dcdcdc;">No</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Tanggal</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Nama Kuli</th>
            <th rowspan="1" style="background-color: #dcdcdc;">NPWP</th>
            <th rowspan="1" style="background-color: #dcdcdc;">KTP</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Upah</th>
            <th rowspan="1" style="background-color: #dcdcdc;">Deskripsi</th>
            {{-- <th rowspan="1" style="background-color: #dcdcdc;">Amount</th> --}}
        </tr>

    </thead>
    <tbody>
        @php
        $sal=0;
        @endphp
        @foreach ($transactions as $tran)
        @php
        $sal += $tran->salary;
        @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tran->tanggal }}</td>
                <td>{{ $tran->name }}</td>
                <td>{{ $tran->npwp }}</td>
                @if($tran->identity_card_number == '')
                    <td></td>
                @else
                    <td>'{{ $tran->identity_card_number }}</td>
                @endif
                
                <td>{{ $tran->salary }}</td>
                <td>{{ $tran->description }}</td>
            </tr>
        @endforeach
        <tr>
                <th colspan="5">TOTAL</th>
                <th>{{ $sal }}</th>
                
            </tr>
    </tbody>
</table>
