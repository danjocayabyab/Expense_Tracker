<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource with optional filters.
     */
    public function index(Request $request)
    {
        $type = $request->query('type'); // income, expense, or null
        $month = $request->query('month'); // format: YYYY-MM or null
        $search = $request->query('q'); // search in category or description
        $sort = $request->query('sort', 'date_desc'); // date_desc, date_asc, amount_desc, amount_asc

        $query = Transaction::query();

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        if ($month) {
            // Expecting format YYYY-MM from an <input type="month">
            [$year, $monthNumber] = explode('-', $month) + [null, null];
            if ($year && $monthNumber) {
                $query->whereYear('date', $year)
                      ->whereMonth('date', $monthNumber);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Determine sorting
        $sortColumn = 'date';
        $sortDirection = 'desc';
        if ($sort === 'date_asc') {
            $sortColumn = 'date';
            $sortDirection = 'asc';
        } elseif ($sort === 'amount_desc') {
            $sortColumn = 'amount';
            $sortDirection = 'desc';
        } elseif ($sort === 'amount_asc') {
            $sortColumn = 'amount';
            $sortDirection = 'asc';
        }

        $transactions = $query->orderBy($sortColumn, $sortDirection)->paginate(10)->withQueryString();

        // Totals should respect the same filters
        $totalsQuery = clone $query;
        $totalIncome = (clone $totalsQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $totalsQuery)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('transactions.index', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'currentType' => $type,
            'currentMonth' => $month,
            'currentSearch' => $search,
            'currentSort' => $sort,
        ]);
    }

    /**
     * Show a monthly summary of income, expenses, and net balance.
     */
    public function summary()
    {
        $summaries = Transaction::selectRaw('YEAR(date) as year, MONTH(date) as month')
            ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income')
            ->selectRaw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('transactions.summary', compact('summaries'));
    }

    /**
     * Export filtered transactions as a CSV file.
     */
    public function exportCsv(Request $request)
    {
        $type = $request->query('type');
        $month = $request->query('month');
        $search = $request->query('q');
        $sort = $request->query('sort', 'date_desc');

        $query = Transaction::query();

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        if ($month) {
            [$year, $monthNumber] = explode('-', $month) + [null, null];
            if ($year && $monthNumber) {
                $query->whereYear('date', $year)
                      ->whereMonth('date', $monthNumber);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortColumn = 'date';
        $sortDirection = 'desc';
        if ($sort === 'date_asc') {
            $sortColumn = 'date';
            $sortDirection = 'asc';
        } elseif ($sort === 'amount_desc') {
            $sortColumn = 'amount';
            $sortDirection = 'desc';
        } elseif ($sort === 'amount_asc') {
            $sortColumn = 'amount';
            $sortDirection = 'asc';
        }

        $transactions = $query->orderBy($sortColumn, $sortDirection)->get();

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['Date', 'Type', 'Category', 'Amount', 'Description', 'Created At']);

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->date->format('Y-m-d'),
                    $transaction->type,
                    $transaction->category,
                    $transaction->amount,
                    $transaction->description,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
