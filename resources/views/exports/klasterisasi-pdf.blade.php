<!DOCTYPE html>
<html>

<head>
    <title>Hasil Klasterisasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Hasil Klasterisasi Upload ID: {{ $upload->id }}</h2>
    <p>Tanggal: {{ $upload->created_at->format('d M Y H:i') }}</p>
    <p>Cakupan : {{ $upload->cakupan }}</p>
    <p>Nama File : {{ $upload->file_name }}</p>
    <p>Unit Nama : {{ $upload->unit_nama }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Listening</th>
                <th>Structure</th>
                <th>Reading</th>
                <th>Total</th>
                <th>Cluster</th>
                <th>Insight</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results as $i => $result)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $result->toeflScoreEntry->nama ?? '-' }}</td>
                    <td>{{ $result->toeflScoreEntry->nim ?? '-' }}</td>
                    <td>{{ $result->toeflScoreEntry->listening ?? '-' }}</td>
                    <td>{{ $result->toeflScoreEntry->structure ?? '-' }}</td>
                    <td>{{ $result->toeflScoreEntry->reading ?? '-' }}</td>
                    <td>{{ $result->toeflScoreEntry->total_score ?? '-' }}</td>
                    <td>Cluster {{ $result->cluster }}</td>
                    <td>{{ $result->insight ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
