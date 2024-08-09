@extends('layouts.auth');
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <form class="d-flex" role="search">
                    @csrf
                    <input class="form-control me-3" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="row">
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tanggal Pesanan</th>
                        <th scope="col">Jumlah Tiket</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan as $pesananItem)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $pesananItem->pesanan->tanggal_pesanan }}</td>
                            <td>{{ $pesananItem->pesanan->jumlah_tiket }}</td>
                            <td>Rp.
                                {{ number_format($pesananItem->pesanan->jumlah_tiket * $pesananItem->pesanan->harga, 0, ',', '.') }}
                            </td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('pembeli', $pesananItem->id) }}"
                                    role="button">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
