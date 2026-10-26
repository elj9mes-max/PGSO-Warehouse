<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - {{ $inventoryItem->name }} & Variants</title>

    <!-- Modern Sans Font for Clean SaaS Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        /* Base Reset & Variables */
        *,
        *::before,
        *::after {
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
            --btn-hover: #27272a;
            --btn-text: #ffffff;
            --badge-bg: #f4f4f5;
            --badge-text: #18181b;
            --status-green: #10b981;
            --status-amber: #f59e0b;
            --status-red: #ef4444;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .item-detail-area {
            padding: 32px 36px;
            max-width: 1360px;
            margin: 0 auto;
            width: 100%;
        }

        /* Breadcrumbs */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .breadcrumb-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s ease;
        }

        .breadcrumb-link:hover {
            color: var(--text-primary);
        }

        .breadcrumb-separator {
            color: #d4d4d8;
        }

        .breadcrumb-current {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Page Header */
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .item-title-group h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .item-title-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .code-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #f4f4f5;
            color: #27272a;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
        }

        .category-pill {
            display: inline-block;
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-secondary);
            background-color: #f4f4f5;
            padding: 3px 8px;
            border-radius: 9999px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline {
            height: 40px;
            padding: 0 16px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-outline:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .btn-primary {
            height: 40px;
            padding: 0 18px;
            background-color: var(--btn-bg);
            border: 1px solid var(--btn-bg);
            border-radius: 8px;
            color: var(--btn-text);
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background-color: var(--btn-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-primary svg,
        .btn-outline svg,
        .btn-danger svg {
            width: 16px;
            height: 16px;
        }

        .btn-danger {
            height: 40px;
            padding: 0 18px;
            background-color: #dc2626;
            border: 1px solid #dc2626;
            border-radius: 8px;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-icon-btn {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            padding: 5px 8px;
            color: var(--text-secondary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        .action-icon-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
            border-color: var(--border-hover);
        }

        .action-icon-btn.danger {
            color: #dc2626;
            border-color: #fecaca;
        }

        .action-icon-btn.danger:hover {
            background-color: #fef2f2;
            border-color: #f87171;
            color: #b91c1c;
        }

        .qty-badge-btn {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 5px 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .qty-badge-btn:hover {
            background-color: #fafafa;
            border-color: var(--text-primary);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        /* Metric Cards Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 8px;
            display: block;
        }

        .metric-number {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .metric-desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 6px;
            display: block;
        }

        /* Item Spec Card */
        .specs-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .specs-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-width: 700px;
        }

        .specs-label {
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
        }

        .specs-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .specs-pills {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .spec-item-title {
            font-size: 0.7188rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .spec-item-val {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Variants Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-title-group h2 {
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title-group p {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Filter & Search Bar */
        .filter-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #a1a1aa;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 38px;
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
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        /* Table Card */
        .table-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-bottom: 32px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .variants-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8438rem;
        }

        .variants-table th {
            background-color: #fafafa;
            padding: 12px 18px;
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .variants-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .variants-table tbody tr {
            transition: background-color 0.12s ease;
        }

        .variants-table tbody tr:hover {
            background-color: #fafafa;
        }

        .variants-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Variant Tag with Swatch */
        .variant-name-cell {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .color-swatch {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 1px #d4d4d8;
            flex-shrink: 0;
        }

        .variant-title {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        /* Location Tag */
        .location-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7813rem;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .location-tag svg {
            width: 13px;
            height: 13px;
            color: #a1a1aa;
        }

        .qty-strong {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }

        .qty-unit {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-left: 2px;
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7188rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .status-pill.in-stock {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-pill.low-stock {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-pill.out-of-stock {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-dot-sm {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-pill.in-stock .status-dot-sm {
            background-color: var(--status-green);
        }

        .status-pill.low-stock .status-dot-sm {
            background-color: var(--status-amber);
        }

        .status-pill.out-of-stock .status-dot-sm {
            background-color: var(--status-red);
        }

        /* Other Items Bar */
        .other-items-section {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--border-subtle);
        }

        .other-items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .other-item-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 14px 16px;
            text-decoration: none;
            color: inherit;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.15s ease;
        }

        .other-item-card:hover {
            border-color: var(--text-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-dialog {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            width: 100%;
            max-width: 580px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.97) translateY(8px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
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
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
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

        .modal-hint {
            font-size: 0.7188rem;
            color: var(--text-muted);
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

        @media (max-width: 768px) {
            .item-detail-area {
                padding: 20px 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <x-sidebar>
        <div class="item-detail-area">

            <!-- Breadcrumbs -->
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('admin.inventory') }}" class="breadcrumb-link">
                    <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Inventory Records</span>
                </a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">{{ $inventoryItem->name }}</span>
            </nav>

            <!-- Page Header -->
            <div class="item-header">
                <div class="item-title-group">
                    <h1>
                        <span>{{ $inventoryItem->name }}</span>
                    </h1>
                    <div class="item-title-meta">
                        <span class="code-pill">{{ $inventoryItem->item_code }}</span>
                        <span class="category-pill">{{ $inventoryItem->category }}</span>
                        <span
                            class="status-pill {{ $inventoryItem->status === 'In Stock' ? 'in-stock' : ($inventoryItem->status === 'Low Stock' ? 'low-stock' : 'out-of-stock') }}">
                            <span class="status-dot-sm"></span>
                            {{ $inventoryItem->status }}
                        </span>
                    </div>
                </div>

                <div class="header-actions">
                    <a href="{{ route('admin.inventory') }}" class="btn-outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Back to Inventory</span>
                    </a>

                    <button type="button" class="btn-primary" id="openAddVariantModalBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Add Variant</span>
                    </button>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('status'))
                <div
                    style="background-color: #f4fdf8; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
                    <svg style="width: 18px; height: 18px; color: #16a34a; flex-shrink: 0;" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.875rem;">
                    <strong>Please check the form inputs:</strong>
                    <ul style="margin-top: 6px; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- Variants Section Header -->
            <div class="section-header">
                <div class="section-title-group">
                    <h2>
                        <span>Product Variants</span>
                        <span class="code-pill">{{ $inventoryItem->variants->count() }} {{ Str::plural('variant', $inventoryItem->variants->count()) }}</span>
                    </h2>
                    <p>Manage colorways, barcodes, quantities, and storage bays for this inventory item.</p>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="filter-panel">
                <div class="search-box">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="variantSearch" class="search-input"
                        placeholder="Search variants by name (e.g. Blue, Red), SKU, or location...">
                </div>
            </div>

            <!-- Variants Table Card -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="variants-table" id="variantsTable">
                        <thead>
                            <tr>
                                <th>Variant Name</th>
                                <th>Barcode & SKU</th>
                                <th>Description / Specification</th>
                                <th>Stock On Hand</th>
                                <th>Unit Cost</th>
                                <th>Location</th>
                                <th>Inspection Status</th>
                                <th>Status</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventoryItem->variants as $variant)
                                @php
                                    $lowerName = strtolower($variant->variant_name);
                                    $swatchColor = match (true) {
                                        str_contains($lowerName, 'blue') => '#2563eb',
                                        str_contains($lowerName, 'red') => '#dc2626',
                                        str_contains($lowerName, 'black') => '#09090b',
                                        str_contains($lowerName, 'green') => '#16a34a',
                                        str_contains($lowerName, 'yellow') => '#eab308',
                                        str_contains($lowerName, 'orange') => '#ea580c',
                                        str_contains($lowerName, 'purple') => '#9333ea',
                                        str_contains($lowerName, 'white') => '#f4f4f5',
                                        default => '#71717a',
                                    };
                                @endphp
                                <tr data-name="{{ $variant->variant_name }}" data-barcode="{{ $variant->barcode }}"
                                    data-sku="{{ $variant->sku }}" data-location="{{ $variant->location }}">
                                    <td>
                                        <div class="variant-name-cell">
                                            <span class="color-swatch" style="background-color: {{ $swatchColor }};"
                                                title="{{ $variant->variant_name }}"></span>
                                            <span class="variant-title">{{ $variant->variant_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 2px;">
                                            <span class="code-pill">{{ $variant->barcode }}</span>
                                            @if($variant->sku)
                                                <span
                                                    style="font-family: var(--font-mono); font-size: 0.6875rem; color: #71717a;">SKU:
                                                    {{ $variant->sku }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: var(--text-secondary);">
                                            {{ $variant->variant_description ?: 'Standard ' . $variant->variant_name . ' specification' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="qty-badge-btn"
                                            data-variant="{{ json_encode($variant) }}"
                                            onclick="openEditVariantModal(JSON.parse(this.dataset.variant))"
                                            title="Click to edit quantity & details">
                                            <span class="qty-strong">{{ $variant->quantity }}</span>
                                            <span class="qty-unit">{{ $variant->effective_unit }}</span>
                                            <svg style="width: 12px; height: 12px; color: #a1a1aa; margin-left: 2px;"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                    <td><strong>₱{{ number_format($variant->unit_cost ?: $inventoryItem->unit_cost, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="location-tag">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                            {{ $variant->location ?: ($inventoryItem->location ?: 'Unassigned') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($variant->is_for_inspection)
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; font-size: 0.7188rem; font-weight: 700; background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                                <svg style="width: 12px; height: 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                For Inspection
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; font-size: 0.7188rem; font-weight: 700; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;">
                                                <svg style="width: 12px; height: 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                Inspected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="status-pill {{ $variant->status === 'In Stock' ? 'in-stock' : ($variant->status === 'Low Stock' ? 'low-stock' : 'out-of-stock') }}">
                                            <span class="status-dot-sm"></span>
                                            {{ $variant->status }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="table-actions" style="justify-content: flex-end; gap: 6px;">
                                            @if($variant->is_for_inspection)
                                                <form method="POST" action="{{ route('admin.inventory.variants.inspection', [$inventoryItem, $variant]) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_for_inspection" value="0">
                                                    <button type="submit" class="action-icon-btn" style="color: #15803d; border-color: #86efac; background: #f0fdf4;" title="Approve inspection and display in active inventory">
                                                        <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                        <span>Pass Inspection</span>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.inventory.variants.inspection', [$inventoryItem, $variant]) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_for_inspection" value="1">
                                                    <button type="submit" class="action-icon-btn" style="color: #b45309; border-color: #fde68a; background: #fffbeb;" title="Hold for inspection (hide from active inventory)">
                                                        <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        <span>Hold</span>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" class="action-icon-btn"
                                                data-variant="{{ json_encode($variant) }}"
                                                onclick="openEditVariantModal(JSON.parse(this.dataset.variant))"
                                                title="Edit Variant Details & Quantity">
                                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                    </path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                    </path>
                                                </svg>
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" class="action-icon-btn danger"
                                                data-variant-id="{{ $variant->id }}"
                                                data-variant-name="{{ $variant->variant_name }}"
                                                onclick="confirmDeleteVariant(Number(this.dataset.variantId), this.dataset.variantName)"
                                                title="Delete Variant">
                                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10"
                                        style="text-align: center; padding: 48px 24px; color: var(--text-muted);">
                                        <svg style="width: 36px; height: 36px; color: #d4d4d8; margin: 0 auto 12px; display: block;"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                            <line x1="2" x2="22" y1="10" y2="10"></line>
                                        </svg>
                                        <p style="font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">No
                                            variants recorded yet</p>
                                        <p style="font-size: 0.8125rem; margin-bottom: 16px;">This inventory item currently
                                            has no sub-variants. You can add color, size, or model variants right now.</p>
                                        <button type="button" class="btn-primary"
                                            onclick="document.getElementById('addVariantModal').classList.add('open')">
                                            + Add First Variant
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </x-sidebar>

    <!-- Add Variant Modal -->
    <div class="modal-overlay" id="addVariantModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Add Variant for {{ $inventoryItem->name }}</h2>
                <button type="button" class="modal-close-btn" id="closeAddVariantModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="addVariantForm" method="POST"
                action="{{ route('admin.inventory.variants.store', $inventoryItem) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantName">Variant Name *</label>
                            <input type="text" id="newVariantName" name="variant_name" class="modal-input"
                                placeholder="e.g. Blue, Red, Black, Green" required autofocus>
                            <span class="modal-hint">Specific color, size, or configuration</span>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantBarcode">Barcode *</label>
                            <input type="text" id="newVariantBarcode" name="barcode" class="modal-input"
                                placeholder="e.g. 480088910014" required>
                            <span class="modal-hint">Unique item barcode (Required)</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantSku">Variant SKU / Code <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="text" id="newVariantSku" name="sku" class="modal-input"
                                placeholder="e.g. {{ $inventoryItem->item_code }}-BLU">
                            <span class="modal-hint">Optional stock keeping identifier</span>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantUnit">Unit of Measure</label>
                            <input type="text" id="newVariantUnit" name="unit" class="modal-input"
                                value="{{ $inventoryItem->unit }}" placeholder="e.g. pieces">
                        </div>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="newVariantDesc">Variant Description <span
                                style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                        <input type="text" id="newVariantDesc" name="variant_description" class="modal-input"
                            placeholder="e.g. 0.5mm Bullet Tip Standard Gel Ink">
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="newVariantQty">Initial Quantity *</label>
                        <input type="number" id="newVariantQty" name="quantity" class="modal-input" min="0"
                            value="50" required>
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantCost">Unit Cost (₱) <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="number" id="newVariantCost" name="unit_cost" step="0.01" class="modal-input"
                                value="{{ $inventoryItem->unit_cost }}" placeholder="₱0.00">
                            <span class="modal-hint">Defaults to parent item cost if left blank</span>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantLocation">Storage Location <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="text" id="newVariantLocation" name="location" class="modal-input"
                                value="{{ $inventoryItem->location }}" placeholder="e.g. Aisle 1 • Shelf C-2">
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="newVariantInspection">Inspection Status</label>
                            <select id="newVariantInspection" name="is_for_inspection" class="modal-input">
                                <option value="1" selected>🔍 For Inspection (Pending approval)</option>
                                <option value="0">✓ Inspected (Active in Inventory)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelAddVariantBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Variant</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Variant Modal -->
    <div class="modal-overlay" id="editVariantModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Edit Variant Details & Quantity</h2>
                <button type="button" class="modal-close-btn" id="closeEditVariantModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="editVariantForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantName">Variant Name *</label>
                            <input type="text" id="editVariantName" name="variant_name" class="modal-input" required>
                            <span class="modal-hint">e.g. Blue, Red, Black, Green</span>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantBarcode">Barcode *</label>
                            <input type="text" id="editVariantBarcode" name="barcode" class="modal-input" required>
                            <span class="modal-hint">Unique item barcode</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantSku">Variant SKU / Code <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="text" id="editVariantSku" name="sku" class="modal-input">
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantUnit">Unit of Measure</label>
                            <input type="text" id="editVariantUnit" name="unit" class="modal-input"
                                placeholder="e.g. pieces">
                        </div>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="editVariantDesc">Description <span
                                style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                        <input type="text" id="editVariantDesc" name="variant_description" class="modal-input">
                    </div>

                    <!-- Highlighted Editable Quantity Row -->
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin: 4px 0;">
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantQty"
                                style="color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                <span>Quantity on Hand *</span>
                                <span
                                    style="background-color: #dbeafe; color: #1e40af; font-size: 0.6875rem; padding: 1px 6px; border-radius: 4px; font-weight: 700;">Editable</span>
                            </label>
                            <input type="number" id="editVariantQty" name="quantity" class="modal-input" min="0"
                                required style="font-weight: 700; font-size: 1rem;">
                            <span class="modal-hint">Live units available in stock</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantCost">Unit Cost (₱) <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="number" id="editVariantCost" name="unit_cost" step="0.01" class="modal-input"
                                placeholder="₱0.00">
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantLocation">Storage Location <span
                                    style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="text" id="editVariantLocation" name="location" class="modal-input"
                                placeholder="e.g. Aisle 1 • Shelf C-2">
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="editVariantInspection">Inspection Status</label>
                            <select id="editVariantInspection" name="is_for_inspection" class="modal-input">
                                <option value="1">🔍 For Inspection (Pending approval)</option>
                                <option value="0">✓ Inspected (Active in Inventory)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelEditVariantBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Update Variant</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Variant Confirmation Modal -->
    <div class="modal-overlay" id="deleteVariantModal">
        <div class="modal-dialog" style="max-width: 440px;">
            <div class="modal-header">
                <h2>Delete Variant</h2>
                <button type="button" class="modal-close-btn" id="closeDeleteVariantModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="deleteVariantForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                        Are you sure you want to delete variant <strong id="deleteVariantTargetName"
                            style="color: var(--text-primary);"></strong>?
                    </p>
                    <p style="font-size: 0.7813rem; color: #dc2626; margin-top: 6px;">
                        This will permanently remove this variant and deduct its stock from the parent item.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelDeleteVariantBtn">Cancel</button>
                    <button type="submit" class="btn-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                            </path>
                        </svg>
                        <span>Yes, Delete Variant</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripting for Modals & Live Variant Filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Add Variant Modal Elements
            const addModal = document.getElementById('addVariantModal');
            const openAddBtn = document.getElementById('openAddVariantModalBtn');
            const openAddTopBtn = document.getElementById('openAddVariantTopBtn');
            const closeAddBtn = document.getElementById('closeAddVariantModalBtn');
            const cancelAddBtn = document.getElementById('cancelAddVariantBtn');
            const variantNameInput = document.getElementById('newVariantName');
            const variantSkuInput = document.getElementById('newVariantSku');
            const baseItemCode = @json($inventoryItem->item_code);

            // Edit Variant Modal Elements
            const editModal = document.getElementById('editVariantModal');
            const editForm = document.getElementById('editVariantForm');
            const closeEditBtn = document.getElementById('closeEditVariantModalBtn');
            const cancelEditBtn = document.getElementById('cancelEditVariantBtn');

            // Delete Variant Modal Elements
            const deleteModal = document.getElementById('deleteVariantModal');
            const deleteForm = document.getElementById('deleteVariantForm');
            const deleteTargetName = document.getElementById('deleteVariantTargetName');
            const closeDeleteBtn = document.getElementById('closeDeleteVariantModalBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteVariantBtn');

            const openAddModal = () => {
                addModal.classList.add('open');
                variantNameInput.focus();
            };

            const closeAddModal = () => {
                addModal.classList.remove('open');
            };

            if (openAddBtn) openAddBtn.addEventListener('click', openAddModal);
            if (openAddTopBtn) openAddTopBtn.addEventListener('click', openAddModal);
            if (closeAddBtn) closeAddBtn.addEventListener('click', closeAddModal);
            if (cancelAddBtn) cancelAddBtn.addEventListener('click', closeAddModal);

            addModal.addEventListener('click', (e) => {
                if (e.target === addModal) closeAddModal();
            });

            // Edit Modal Functions
            window.openEditVariantModal = function (variant) {
                editForm.action = `/admin/inventory/{{ $inventoryItem->id }}/variants/${variant.id}`;
                document.getElementById('editVariantName').value = variant.variant_name || '';
                document.getElementById('editVariantBarcode').value = variant.barcode || '';
                document.getElementById('editVariantSku').value = variant.sku || '';
                document.getElementById('editVariantUnit').value = variant.unit || @json($inventoryItem->unit);
                document.getElementById('editVariantDesc').value = variant.variant_description || '';
                document.getElementById('editVariantQty').value = variant.quantity !== undefined ? variant.quantity : 0;
                document.getElementById('editVariantCost').value = variant.unit_cost || '';
                document.getElementById('editVariantLocation').value = variant.location || '';
                const editInspection = document.getElementById('editVariantInspection');
                if (editInspection) {
                    editInspection.value = (variant.is_for_inspection === true || variant.is_for_inspection === 1 || variant.is_for_inspection === "1") ? "1" : "0";
                }

                editModal.classList.add('open');
                document.getElementById('editVariantQty').focus();
                document.getElementById('editVariantQty').select();
            };

            const closeEditModal = () => {
                editModal.classList.remove('open');
            };

            if (closeEditBtn) closeEditBtn.addEventListener('click', closeEditModal);
            if (cancelEditBtn) cancelEditBtn.addEventListener('click', closeEditModal);

            editModal.addEventListener('click', (e) => {
                if (e.target === editModal) closeEditModal();
            });

            // Delete Modal Functions
            window.confirmDeleteVariant = function (variantId, variantName) {
                deleteForm.action = `/admin/inventory/{{ $inventoryItem->id }}/variants/${variantId}`;
                deleteTargetName.textContent = variantName;
                deleteModal.classList.add('open');
            };

            const closeDeleteModal = () => {
                deleteModal.classList.remove('open');
            };

            if (closeDeleteBtn) closeDeleteBtn.addEventListener('click', closeDeleteModal);
            if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);

            deleteModal.addEventListener('click', (e) => {
                if (e.target === deleteModal) closeDeleteModal();
            });

            // Global Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeAddModal();
                    closeEditModal();
                    closeDeleteModal();
                }
            });

            // Auto-suggest SKU based on variant name in Add modal
            if (variantNameInput && variantSkuInput) {
                variantNameInput.addEventListener('input', () => {
                    const val = variantNameInput.value.trim().toUpperCase().replace(/[^A-Z0-9]/g, '');
                    if (val.length > 0) {
                        const abbr = val.length <= 4 ? val : val.substring(0, 3);
                        variantSkuInput.value = `${baseItemCode}-${abbr}`;
                    }
                });
            }

            // Live search for variants table
            const searchInput = document.getElementById('variantSearch');
            const table = document.getElementById('variantsTable');
            if (searchInput && table) {
                const rows = table.querySelectorAll('tbody tr');
                searchInput.addEventListener('input', () => {
                    const q = searchInput.value.toLowerCase().trim();
                    rows.forEach(row => {
                        const name = (row.getAttribute('data-name') || '').toLowerCase();
                        const barcode = (row.getAttribute('data-barcode') || '').toLowerCase();
                        const sku = (row.getAttribute('data-sku') || '').toLowerCase();
                        const loc = (row.getAttribute('data-location') || '').toLowerCase();
                        const text = row.innerText.toLowerCase();

                        if (!q || name.includes(q) || barcode.includes(q) || sku.includes(q) || loc.includes(q) || text.includes(q)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>