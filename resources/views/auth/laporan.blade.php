@extends('layouts.auth');

@section('content')
    <div class="row">
        <div class="col-lg d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Penjualan Minggu Ini</h5>
                        </div>
                        <div>
                            <form action="{{ route('home') }}" method="GET">
                                <label for="period">Pilih Periode:</label>
                                <select name="period" id="period" onchange="this.form.submit()">
                                    <option value="week" {{ $selectedPeriod == 'week' ? 'selected' : '' }}>Minggu</option>
                                    <option value="month" {{ $selectedPeriod == 'month' ? 'selected' : '' }}>Bulan</option>
                                    <option value="year" {{ $selectedPeriod == 'year' ? 'selected' : '' }}>Tahun</option>
                                </select>

                                <label for="week">Pilih Minggu:</label>
                                <select name="week" id="week" onchange="this.form.submit()"
                                    {{ $selectedPeriod == 'week' ? '' : 'disabled' }}>
                                    @foreach ($weeks as $key => $week)
                                        <option value="{{ $key }}" {{ $selectedWeek == $key ? 'selected' : '' }}>
                                            {{ $week }}</option>
                                    @endforeach
                                </select>

                                <label for="month">Pilih Bulan:</label>
                                <select name="month" id="month" onchange="this.form.submit()"
                                    {{ $selectedPeriod == 'month' ? '' : 'disabled' }}>
                                    @foreach ($months as $key => $month)
                                        <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                            {{ $month }}</option>
                                    @endforeach
                                </select>

                                <label for="year">Pilih Tahun:</label>
                                <select name="year" id="year" onchange="this.form.submit()">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>

                        </div>
                    </div>
                    <div id="chart"></div>
                    <div id="earnings" data-json="{{ implode(', ', $earnings) }}"></div>
                    <div id="categories" data-json="{{ implode(', ', $categories) }}"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
