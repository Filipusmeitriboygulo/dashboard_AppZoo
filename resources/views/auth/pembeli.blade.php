@extends('layouts.auth');
@section('content');

    <div class="container">
        <h1>Detail Pesanan</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pesanan #{{ $pembeli->id }}</h5>
                <p><strong>Nama:</strong> {{ $pembeli->nama }}</p>
                <p><strong>Email:</strong> {{ $pembeli->email }}</p>
                <p><strong>Kontak:</strong> {{ $pembeli->nohp }}</p>
                <p><strong>Status:</strong> {{ $pembeli->status }}</p>

                {{-- <form action="{{ route('admin.orders.update', $pembeli->order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="Unpaid" {{ $pembeli->order->status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Paid" {{ $pembeli->order->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </form> --}}
            </div>
        </div>
    </div>
@endsection
