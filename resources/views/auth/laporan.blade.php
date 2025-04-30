@extends('layouts.auth')

@section('content')
    <div class="row">
        <div class="col-lg d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-9">
                        <h5 class="card-title fw-semibold">Laporan Penjualan</h5>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporanPenjualan as $laporan)
                                <tr>
                                    <td>{{ $laporan['tanggal'] }}</td>
                                    <td>{{ $laporan['jumlah_transaksi'] }}</td>
                                    <td>{{ $laporan['total_pendapatan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
