<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Office Directory</title>
    
    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Base Reset & Variables */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-page: #fafafa;
            --bg-surface: #ffffff;
            --text-primary: #09090b;
            --text-secondary: #52525b;
            --text-muted: #71717a;
            --border-subtle: #e4e4e7;
            --border-hover: #d4d4d8;
            --btn-bg: #09090b;
            --btn-text: #ffffff;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .office-content-area {
            padding: 32px 36px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .header-title-group p {
            font-size: 0.8438rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-primary, .btn-outline {
            height: 40px;
            padding: 0 16px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
            text-decoration: none;
            outline: none;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--btn-bg);
            color: var(--btn-text);
            border: 1px solid var(--btn-bg);
        }

        .btn-primary:hover {
            background-color: #27272a;
            border-color: #27272a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-outline {
            background-color: #ffffff;
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
        }

        .btn-outline:hover {
            border-color: var(--border-hover);
            background-color: #fafafa;
        }

        .btn-danger {
            background-color: #dc2626;
            color: #ffffff;
            border: 1px solid #dc2626;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        .btn-primary svg, .btn-outline svg, .btn-danger svg {
            width: 16px;
            height: 16px;
        }

        /* Metrics Summary Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease;
        }

        .metric-card:hover {
            border-color: var(--border-hover);
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .metric-number {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin: 6px 0 2px 0;
        }

        .metric-desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Filter & Search Bar */
        .filter-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a1aa;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .search-icon svg {
            width: 16px;
            height: 16px;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0 14px 0 36px;
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.15s ease;
        }

        .search-input:focus {
            background-color: #ffffff;
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .filters-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Table Card Container */
        .table-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .office-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8438rem;
        }

        .office-table th {
            background-color: #fafafa;
            color: var(--text-muted);
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .office-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .office-table tbody tr {
            transition: background-color 0.1s ease;
        }

        .office-table tbody tr:hover {
            background-color: #fafafa;
        }

        .office-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Office Title & Icon */
        .office-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .office-icon-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background-color: #f4f4f5;
            color: #18181b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
            border: 1px solid #e4e4e7;
        }

        .office-icon-avatar svg {
            width: 18px;
            height: 18px;
            color: #52525b;
        }

        .office-text-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .office-name-title {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .office-code-pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            background-color: #f4f4f5;
            color: #18181b;
            border-radius: 6px;
            font-family: var(--font-mono);
            font-size: 0.7813rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            border: 1px solid #e4e4e7;
        }

        /* Action Buttons */
        .actions-cell {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .action-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
            background-color: #ffffff;
            color: #52525b;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .action-icon-btn:hover {
            color: var(--text-primary);
            border-color: var(--border-hover);
            background-color: #fafafa;
        }

        .action-icon-btn.danger:hover {
            color: #dc2626;
            border-color: #fca5a5;
            background-color: #fef2f2;
        }

        .action-icon-btn svg {
            width: 15px;
            height: 15px;
        }

        /* Table Card Footer */
        .table-card-footer {
            padding: 14px 20px;
            background-color: #ffffff;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
            color: var(--text-muted);
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Empty State */
        .empty-state {
            padding: 56px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 14px auto;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f5;
            border-radius: 12px;
        }

        /* Modal Overlay & Styling */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.45);
            backdrop-filter: blur(2px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-dialog {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        .modal-close-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-input {
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.15s ease;
            width: 100%;
        }

        .modal-input:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        .modal-footer {
            padding: 16px 24px;
            background-color: #fafafa;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Alert Banners */
        .alert-banner {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.8438rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-banner.error {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-banner svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .office-content-area {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    <x-sidebar>
        <div class="office-content-area">

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Offices Directory</h1>
                    <p>PGSO Property & Supply Master List • Provincial Government Departments & Divisions</p>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn-outline" onclick="window.print()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        <span>Print Directory</span>
                    </button>

                    <button type="button" class="btn-primary" id="openAddOfficeModalBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Add New Office</span>
                    </button>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('status'))
                <div class="alert-banner">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-banner error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @php
                $officesList = $offices ?? collect();
                $totalOffices = $officesList->count();
            @endphp

            <!-- Summary Metric Cards -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-label">Total Offices</div>
                    <div class="metric-number">{{ $totalOffices }}</div>
                    <div class="metric-desc">Registered provincial departments & offices</div>
                </div>

                <div class="metric-card">
                    <div class="metric-label">Directory Status</div>
                    <div class="metric-number" style="color: #16a34a;">Active</div>
                    <div class="metric-desc">Ready for inventory custody & issuances</div>
                </div>

                <div class="metric-card">
                    <div class="metric-label">Administrative Scope</div>
                    <div class="metric-number">PGSO</div>
                    <div class="metric-desc">Property & Supply Custody System</div>
                </div>
            </div>

            <!-- Filter & Search Panel -->
            <div class="filter-panel">
                <div class="search-box">
                    <span class="search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        id="officeSearchInput" 
                        class="search-input" 
                        placeholder="Search by office name or office code..."
                        autocomplete="off"
                    >
                </div>

                <div class="filters-group">
                    <button type="button" class="btn-outline" id="resetOfficeFiltersBtn" style="height: 38px;">
                        Reset
                    </button>
                </div>
            </div>

            <!-- Office List Table Card -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="office-table" id="officesTable">
                        <thead>
                            <tr>
                                <th>Office Details</th>
                                <th>Office Code</th>
                                <th>Date Added</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($officesList as $office)
                                <tr 
                                    data-office-id="{{ $office->id }}"
                                    data-office-name="{{ strtolower($office->office_name) }}"
                                    data-office-code="{{ strtolower($office->office_code) }}"
                                >
                                    <!-- Office Name -->
                                    <td>
                                        <div class="office-name-cell">
                                            <div class="office-icon-avatar" title="{{ $office->office_name }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                                                    <path d="M9 22v-4h6v4"/>
                                                    <path d="M8 6h.01"/>
                                                    <path d="M16 6h.01"/>
                                                    <path d="M8 10h.01"/>
                                                    <path d="M16 10h.01"/>
                                                    <path d="M8 14h.01"/>
                                                    <path d="M16 14h.01"/>
                                                </svg>
                                            </div>
                                            <div class="office-text-group">
                                                <span class="office-name-title">{{ $office->office_name }}</span>
                                                <span style="font-size: 0.75rem; color: var(--text-muted);">Provincial Department / Unit</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Office Code -->
                                    <td>
                                        <span class="office-code-pill">{{ $office->office_code }}</span>
                                    </td>

                                    <!-- Date Added -->
                                    <td style="font-size: 0.8125rem;">
                                        {{ $office->created_at ? $office->created_at->format('M d, Y') : '—' }}
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="actions-cell">
                                            <a 
                                                href="{{ route('admin.offices.inventory', $office) }}" 
                                                class="action-icon-btn" 
                                                title="Monitor Office Inventory"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </a>

                                            <button 
                                                type="button" 
                                                class="action-icon-btn" 
                                                title="Edit Office"
                                                onclick="openEditOfficeModal({{ json_encode($office) }})"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </button>

                                            <button 
                                                type="button" 
                                                class="action-icon-btn danger" 
                                                title="Delete Office"
                                                onclick="openDeleteOfficeModal({{ $office->id }}, {{ json_encode($office->office_name) }})"
                                            >
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                                                    <path d="M9 22v-4h6v4"/>
                                                    <path d="M8 6h.01"/>
                                                    <path d="M16 6h.01"/>
                                                </svg>
                                            </div>
                                            <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">No Offices Registered Yet</h3>
                                            <p style="font-size: 0.8438rem; color: var(--text-muted); max-width: 360px; margin: 0 auto 18px auto;">
                                                Start by creating an office record to manage government departments and property custody.
                                            </p>
                                            <button type="button" class="btn-primary" onclick="openAddOfficeModal()">
                                                + Add First Office
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-card-footer">
                    <div>
                        Showing <strong id="visibleOfficeCount">{{ $totalOffices }}</strong> of <strong>{{ $totalOffices }}</strong> office records
                    </div>
                    <div>
                        Property & Supply Directory • Provincial Government
                    </div>
                </div>
            </div>

        </div>
    </x-sidebar>

    <!-- Add Office Modal -->
    <div class="modal-overlay" id="addOfficeModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h2>Add New Office</h2>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Register a provincial department or custody unit</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeAddOfficeModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="addOfficeForm" method="POST" action="{{ route('admin.offices.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="modal-field">
                        <label class="modal-label" for="newOfficeCode">Office Code <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            id="newOfficeCode" 
                            name="office_code" 
                            class="modal-input" 
                            placeholder="e.g. OFF-PGO-001, OFF-GSO-002" 
                            required 
                            autocomplete="off"
                        >
                        <span style="font-size: 0.7188rem; color: var(--text-muted);">Unique department code used in inventory custody records.</span>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="newOfficeName">Office Name <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            id="newOfficeName" 
                            name="office_name" 
                            class="modal-input" 
                            placeholder="e.g. Provincial Governor's Office (PGO)" 
                            required 
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelAddOfficeBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Office</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Office Modal -->
    <div class="modal-overlay" id="editOfficeModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h2>Edit Office</h2>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Update department details and office code</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeEditOfficeModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="editOfficeForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="modal-field">
                        <label class="modal-label" for="editOfficeCode">Office Code <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            id="editOfficeCode" 
                            name="office_code" 
                            class="modal-input" 
                            required 
                            autocomplete="off"
                        >
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="editOfficeName">Office Name <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            id="editOfficeName" 
                            name="office_name" 
                            class="modal-input" 
                            required 
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelEditOfficeBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Update Office</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteOfficeModal">
        <div class="modal-dialog" style="max-width: 440px;">
            <div class="modal-header">
                <h2>Confirm Deletion</h2>
                <button type="button" class="modal-close-btn" id="closeDeleteOfficeModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="deleteOfficeForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body" style="padding: 20px 24px;">
                    <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                        Are you sure you want to delete <strong id="deleteOfficeTargetName" style="color: var(--text-primary);">this office</strong>?
                    </p>
                    <p style="font-size: 0.75rem; color: #dc2626; margin-top: 6px;">
                        This action will permanently remove the office record from the directory.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelDeleteOfficeBtn">Cancel</button>
                    <button type="submit" class="btn-danger btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>Yes, Delete Office</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Live Filtering & Modals -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Modals
            const addModal = document.getElementById('addOfficeModal');
            const editModal = document.getElementById('editOfficeModal');
            const deleteModal = document.getElementById('deleteOfficeModal');

            // Open/Close Add
            const openAddBtn = document.getElementById('openAddOfficeModalBtn');
            const closeAddBtn = document.getElementById('closeAddOfficeModalBtn');
            const cancelAddBtn = document.getElementById('cancelAddOfficeBtn');
            const newOfficeCodeInput = document.getElementById('newOfficeCode');

            window.openAddOfficeModal = function() {
                if (addModal) {
                    addModal.classList.add('active');
                    if (newOfficeCodeInput) {
                        setTimeout(() => newOfficeCodeInput.focus(), 60);
                    }
                }
            };

            const closeAddModal = () => {
                if (addModal) addModal.classList.remove('active');
            };

            if (openAddBtn) openAddBtn.addEventListener('click', openAddOfficeModal);
            if (closeAddBtn) closeAddBtn.addEventListener('click', closeAddModal);
            if (cancelAddBtn) cancelAddBtn.addEventListener('click', closeAddModal);

            // Open/Close Edit
            const editForm = document.getElementById('editOfficeForm');
            const closeEditBtn = document.getElementById('closeEditOfficeModalBtn');
            const cancelEditBtn = document.getElementById('cancelEditOfficeBtn');

            window.openEditOfficeModal = function(office) {
                if (!editModal || !editForm) return;
                editForm.action = `/admin/offices/${office.id}`;
                document.getElementById('editOfficeCode').value = office.office_code || '';
                document.getElementById('editOfficeName').value = office.office_name || '';
                editModal.classList.add('active');
                setTimeout(() => document.getElementById('editOfficeName').focus(), 60);
            };

            const closeEditModal = () => {
                if (editModal) editModal.classList.remove('active');
            };

            if (closeEditBtn) closeEditBtn.addEventListener('click', closeEditModal);
            if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditModal);

            // Open/Close Delete
            const deleteForm = document.getElementById('deleteOfficeForm');
            const deleteTarget = document.getElementById('deleteOfficeTargetName');
            const closeDeleteBtn = document.getElementById('closeDeleteOfficeModalBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteOfficeBtn');

            window.openDeleteOfficeModal = function(id, name) {
                if (!deleteModal || !deleteForm) return;
                deleteForm.action = `/admin/offices/${id}`;
                if (deleteTarget) deleteTarget.textContent = name;
                deleteModal.classList.add('active');
            };

            const closeDeleteModal = () => {
                if (deleteModal) deleteModal.classList.remove('active');
            };

            if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDeleteModal);
            if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);

            // Background Clicks
            window.addEventListener('click', (e) => {
                if (e.target === addModal) closeAddModal();
                if (e.target === editModal) closeEditModal();
                if (e.target === deleteModal) closeDeleteModal();
            });

            // Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                    closeDeleteModal();
                }
            });

            // Live Search & Filtering
            const searchInput = document.getElementById('officeSearchInput');
            const resetBtn = document.getElementById('resetOfficeFiltersBtn');
            const table = document.getElementById('officesTable');
            const countDisplay = document.getElementById('visibleOfficeCount');

            function filterOffices() {
                if (!table) return;
                const query = (searchInput ? searchInput.value : '').toLowerCase().trim();

                const rows = table.querySelectorAll('tbody tr[data-office-id]');
                let visible = 0;

                rows.forEach(row => {
                    const name = row.getAttribute('data-office-name') || '';
                    const code = row.getAttribute('data-office-code') || '';

                    const matchesQuery = !query || name.includes(query) || code.includes(query);

                    if (matchesQuery) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (countDisplay) {
                    countDisplay.textContent = visible;
                }
            }

            if (searchInput) searchInput.addEventListener('input', filterOffices);
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    filterOffices();
                });
            }
        });
    </script>
</body>
</html>
