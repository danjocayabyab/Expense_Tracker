@extends('layouts.app')

@section('content')
    <div class="mx-auto" style="max-width: 1120px;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-1">Overview</h1>
                <p class="text-muted mb-0">Track your small business income and expenses in one clean dashboard.</p>
            </div>
            <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                <form method="GET" action="{{ route('transactions.index') }}" class="d-flex flex-wrap gap-2">
                    <input type="text" name="q" class="form-control form-control-sm" placeholder="Search..."
                           value="{{ $currentSearch ?? '' }}" style="min-width: 160px;">

                    <select name="type" class="form-select form-select-sm" style="min-width: 130px;">
                        <option value="">All Types</option>
                        <option value="income" {{ ($currentType ?? '') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ ($currentType ?? '') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>

                    <input type="month" name="month" class="form-control form-control-sm"
                           value="{{ $currentMonth ?? '' }}" style="min-width: 150px;">

                    <select name="sort" class="form-select form-select-sm" style="min-width: 150px;">
                        <option value="date_desc" {{ ($currentSort ?? 'date_desc') === 'date_desc' ? 'selected' : '' }}>Date: Newest</option>
                        <option value="date_asc" {{ ($currentSort ?? 'date_desc') === 'date_asc' ? 'selected' : '' }}>Date: Oldest</option>
                        <option value="amount_desc" {{ ($currentSort ?? 'date_desc') === 'amount_desc' ? 'selected' : '' }}>Amount: High to Low</option>
                        <option value="amount_asc" {{ ($currentSort ?? 'date_desc') === 'amount_asc' ? 'selected' : '' }}>Amount: Low to High</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    <a href="{{ route('transactions.export.csv', request()->query()) }}" class="btn btn-sm btn-outline-success">Export CSV</a>
                </form>
                <div class="d-flex flex-row gap-2 mt-2 mt-md-0 ms-md-2">
                    <a href="{{ route('transactions.summary') }}" class="btn btn-outline-dark btn-sm shadow-sm">Monthly Summary</a>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm shadow-sm">
                        + Add Transaction
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card text-bg-success">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Total Income</p>
                    <h4 class="mb-0">₱{{ number_format($totalIncome, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-bg-danger">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Total Expense</p>
                    <h4 class="mb-0">₱{{ number_format($totalExpense, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card text-bg-info">
                <div class="card-body">
                    <p class="text-uppercase small mb-1">Net Balance</p>
                    <h4 class="mb-0">₱{{ number_format($netBalance, 2) }}</h4>
                </div>
            </div>
        </div>
        </div>

        <div class="card app-card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <h2 class="h5 mb-0">Transactions</h2>
            <span class="text-muted small mt-2 mt-md-0">{{ $transactions->total() }} records</span>
        </div>
        <div class="card-body p-0">
            @if ($transactions->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th class="text-end">Amount (₱)</th>
                            <th>Description</th>
                            <th class="text-end" style="width: 150px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->date->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $transaction->type === 'income' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->category }}</td>
                                <td class="text-end fw-semibold">{{ number_format($transaction->amount, 2) }}</td>
                                <td class="text-muted">{{ $transaction->description }}</td>
                                <td class="text-end">
                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                       class="btn btn-sm btn-outline-secondary me-1">Edit</a>

                                    <form action="{{ route('transactions.destroy', $transaction) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this transaction?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center text-muted">
                    <p class="mb-1">No transactions yet.</p>
                    <p class="mb-0">Click <strong>"+ Add Transaction"</strong> to get started.</p>
                </div>
            @endif
        </div>
        @if ($transactions->hasPages())
            <div class="card-footer d-flex justify-content-center">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
