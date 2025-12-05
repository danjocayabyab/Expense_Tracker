@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card app-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-0">Add Transaction</h2>
                        <small class="text-muted">Record a new income or expense for your business.</small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST" class="mt-2">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Select Type --</option>
                                <option value="income" {{ old('type')=='income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ old('type')=='expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category"
                                   value="{{ old('category') }}"
                                   placeholder="e.g. Sales, Rent, Utilities"
                                   class="form-control @error('category') is-invalid @enderror">
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount"
                                   value="{{ old('amount') }}"
                                   class="form-control @error('amount') is-invalid @enderror">
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date"
                                   value="{{ old('date') }}"
                                   class="form-control @error('date') is-invalid @enderror">
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description (optional)</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Add any notes or details (optional)">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
