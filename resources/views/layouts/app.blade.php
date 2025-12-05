// hi
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Business Expense &amp; Income Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at top left, #e0f2fe, #f4f6fb 40%, #e5e7eb 100%);
            color: #111827;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .app-navbar {
            background: linear-gradient(135deg, #1f2937, #111827);
        }
        .app-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .app-content {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 2.5rem;
        }
        .app-card {
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 1rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
            background-color: #ffffff;
        }
        .stat-card {
            border: none;
            border-radius: 1rem;
        }
        footer {
            font-size: 0.85rem;
            color: #9ca3af;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('transactions.index') }}">
                Expense &amp; Income Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transactions.summary') ? 'active' : '' }}" href="{{ route('transactions.summary') }}">
                            Monthly Summary
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="app-content">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="py-3 text-center">
        <div class="container">
            <span>Small Business Expense &amp; Income Tracker</span>
        </div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
