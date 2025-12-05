@extends('layouts.app')

@section('content')
    <div class="mx-auto" style="max-width: 900px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h4 mb-1">Monthly Summary</h1>
                <p class="text-muted mb-0">Income, expenses, and net balance grouped by month.</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">Back to Transactions</a>
        </div>

        <div class="card app-card">
            <div class="card-body p-0">
                @if($summaries->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Total Income (₱)</th>
                                <th class="text-end">Total Expense (₱)</th>
                                <th class="text-end">Net Balance (₱)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($summaries as $row)
                                @php
                                    $date = \Carbon\Carbon::create($row->year, $row->month, 1);
                                    $net = $row->total_income - $row->total_expense;
                                @endphp
                                <tr>
                                    <td>{{ $date->format('F Y') }}</td>
                                    <td class="text-end">₱{{ number_format($row->total_income, 2) }}</td>
                                    <td class="text-end">₱{{ number_format($row->total_expense, 2) }}</td>
                                    <td class="text-end fw-semibold {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                                        ₱{{ number_format($net, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <p class="mb-1">No data available yet.</p>
                        <p class="mb-0">Add some transactions to see monthly summaries here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
