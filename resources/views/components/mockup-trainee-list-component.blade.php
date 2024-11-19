@props(['traineeListData'])
<table wire:ignore.self class="table table-hover table-striped border rounded-end" style="font-size: 10px;">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Vessel</th>
            <th>Rank</th>
            <th>Company</th>
            <th>Nationality</th>
            <th>Serial Number</th>
        </tr>
    </thead>
    <tbody>
        <style>
            #hoverA a:hover {
                color: #0477bf;
                text-decoration: none;
            }
        </style>
        
        @foreach ($traineeListData as $row)
            <tr>
                <td>{{$loop->index + 1}}</td>
                <td>{{$row['name']}}</td>
                <td>{{$row['vessel']}}</td>
                <td>{{$row['rank']}}</td>
                <td>{{$row['company']}}</td>
                <td>{{$row['nationality']}}</td>
                <td>{{$row['serialnumber']}}</td>
            </tr>
        @endforeach
        
    </tbody>
</table>