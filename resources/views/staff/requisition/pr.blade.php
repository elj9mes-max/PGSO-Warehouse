<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Purchase Request (PR)</title>

    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-page: #f8fafc;
            --bg-surface: #ffffff;
            --text-primary: #09090b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-subtle: #e2e8f0;
            --border-hover: #cbd5e1;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --primary: #0f172a;
            --brand-blue: #2563eb;
            --brand-blue-light: #eff6ff;
            --brand-blue-dark: #1e40af;
            --accent-emerald: #059669;
            --accent-emerald-light: #ecfdf5;
            --accent-amber: #d97706;
            --accent-rose: #e11d48;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top Brand Header */
        .staff-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #09090b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .brand-icon svg {
            width: 19px;
            height: 19px;
        }

        .brand-text strong {
            font-size: 0.9375rem;
            display: block;
            line-height: 1.2;
            color: var(--text-primary);
            font-weight: 700;
        }

        .brand-text span {
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            padding: 4px 12px 4px 6px;
            border-radius: 9999px;
            border: 1px solid var(--border-subtle);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .user-name-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .cart-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: var(--brand-blue);
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .cart-toggle-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .cart-toggle-btn:active {
            transform: translateY(0);
        }

        .cart-badge-pill {
            background: rgba(255, 255, 255, 0.25);
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 800;
            font-family: var(--font-mono);
            min-width: 22px;
            text-align: center;
        }

        /* Layout Container */
        .pr-container {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 24px 28px 120px;
            flex: 1;
        }

        /* Subheader & Breadcrumb */
        .pr-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 22px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            margin-bottom: 6px;
        }

        .back-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--brand-blue);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .back-nav-link:hover {
            color: var(--brand-blue-dark);
            text-decoration: underline;
        }

        .crumb-sep {
            color: var(--text-muted);
        }

        .crumb-active {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .pr-title-area h1 {
            font-size: 1.625rem;
            font-weight: 800;
            color: #09090b;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-code-badge {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            font-weight: 700;
            background: var(--brand-blue-light);
            color: var(--brand-blue-dark);
            border: 1px solid #bfdbfe;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .pr-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 4px;
            max-width: 780px;
        }

        .office-duty-card {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 8px 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .station-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--brand-blue-light);
            color: var(--brand-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .station-text strong {
            display: block;
            font-size: 0.8125rem;
            color: var(--text-primary);
        }

        .station-text span {
            font-size: 0.6875rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-weight: 600;
        }

        /* Filter & Search Toolbar */
        .toolbar-panel {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
            max-width: 480px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 40px;
            padding: 0 14px 0 38px;
            border-radius: 10px;
            border: 1px solid var(--border-subtle);
            font-family: inherit;
            font-size: 0.875rem;
            background: #f8fafc;
            color: var(--text-primary);
            transition: all 0.15s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--brand-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .category-pill-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cat-pill {
            background: #f1f5f9;
            color: var(--text-secondary);
            border: 1px solid transparent;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.7813rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .cat-pill:hover {
            background: #e2e8f0;
            color: var(--text-primary);
        }

        .cat-pill.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        .catalog-meta-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8125rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Items Grid */
        .variant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        /* Clean Variant Card - Only Name, Code, Barcode & Action */
        .variant-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .variant-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .variant-card.in-cart {
            border-color: #93c5fd;
            background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.08);
        }

        .card-top-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .category-tag {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--brand-blue-dark);
            background: var(--brand-blue-light);
            padding: 3px 8px;
            border-radius: 6px;
        }

        .unit-tag {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            font-family: var(--font-mono);
        }

        .variant-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: #09090b;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .parent-item-subtitle {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        /* Identifiers Box: Code & Barcode */
        .id-details-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .id-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
        }

        .id-label {
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .id-label svg {
            width: 14px;
            height: 14px;
        }

        .id-value {
            font-family: var(--font-mono);
            font-weight: 600;
            color: #09090b;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 2px 7px;
            border-radius: 5px;
            font-size: 0.7188rem;
        }

        .id-value.barcode {
            color: #1e40af;
            background: #eff6ff;
            border-color: #dbeafe;
        }

        /* Card Action Row */
        .card-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-subtle);
        }

        .est-cost-text {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .est-cost-text strong {
            color: var(--text-primary);
            font-family: var(--font-mono);
            font-size: 0.9375rem;
        }

        .add-to-pr-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .add-to-pr-btn:hover {
            background: #27272a;
            transform: translateY(-1px);
        }

        .add-to-pr-btn.active {
            background: var(--brand-blue);
            color: #ffffff;
        }

        .selected-counter {
            display: none;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            border: 1px solid #93c5fd;
            border-radius: 8px;
            padding: 4px;
        }

        .qty-mini-btn {
            width: 26px;
            height: 26px;
            border: none;
            border-radius: 6px;
            background: #f1f5f9;
            color: var(--text-primary);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.1s ease;
        }

        .qty-mini-btn:hover {
            background: #e2e8f0;
        }

        .qty-mini-val {
            min-width: 24px;
            text-align: center;
            font-size: 0.8125rem;
            font-family: var(--font-mono);
            font-weight: 700;
        }

        /* Empty state */
        .empty-state {
            grid-column: 1 / -1;
            background: #ffffff;
            border: 1px dashed var(--border-subtle);
            border-radius: 16px;
            padding: 48px 24px;
            text-align: center;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            color: var(--text-muted);
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .empty-state h3 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Side Drawer / Panel for PR Draft */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 200;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .pr-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            max-width: 520px;
            height: 93%;
            background: #ffffff;
            z-index: 210;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .pr-drawer.open {
            transform: translateX(0);
        }

        .drawer-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .drawer-title-group h2 {
            font-size: 1.1875rem;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .drawer-title-group p {
            font-size: 0.7813rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .drawer-close-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .drawer-close-btn:hover {
            color: var(--text-primary);
            background: #f8fafc;
        }

        .drawer-content {
            padding: 20px 24px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-section-card {
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-label span {
            color: var(--accent-rose);
        }

        .form-textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            padding: 10px 12px;
            font-family: inherit;
            font-size: 0.8438rem;
            resize: vertical;
            min-height: 75px;
            color: var(--text-primary);
            background: #ffffff;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .drawer-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .drawer-item-row {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .item-main-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .item-text-info strong {
            font-size: 0.875rem;
            display: block;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .item-text-info span {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: block;
            margin-top: 2px;
        }

        .remove-item-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .remove-item-btn:hover {
            color: var(--accent-rose);
        }

        .item-control-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 8px;
        }

        .stepper-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .step-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            color: var(--text-primary);
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.1s ease;
        }

        .step-btn:hover {
            background: #f1f5f9;
        }

        .step-qty-input {
            width: 44px;
            height: 28px;
            text-align: center;
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            font-family: var(--font-mono);
            font-size: 0.8125rem;
            font-weight: 700;
        }

        .row-cost-calc {
            text-align: right;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .row-cost-calc strong {
            display: block;
            font-size: 0.875rem;
            font-family: var(--font-mono);
            color: var(--text-primary);
        }

        .drawer-footer {
            padding: 18px 24px;
            border-top: 1px solid var(--border-subtle);
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .totals-summary-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .total-label-box span {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .total-label-box strong {
            font-size: 0.9375rem;
            color: var(--text-primary);
        }

        .total-amount-box {
            text-align: right;
        }

        .total-amount-box span {
            display: block;
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
        }

        .total-amount-box strong {
            font-size: 1.3125rem;
            font-family: var(--font-mono);
            color: var(--brand-blue-dark);
            font-weight: 800;
        }

        .submit-pr-btn {
            width: 100%;
            height: 46px;
            background: var(--brand-blue);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }

        .submit-pr-btn:hover:not(:disabled) {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .submit-pr-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Floating Alert/Toast */
        .toast-notify {
            position: fixed;
            bottom: 84px;
            right: 24px;
            background: #0f172a;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 300;
            opacity: 0;
            transform: translateY(12px);
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-notify.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;
    @endphp

    <!-- Topbar -->
    <header class="staff-topbar">
        <a href="{{ route('staff.requisition.index') }}" class="brand-group">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div class="brand-text">
                <strong>PGSO Inventory System</strong>
                <span>Provincial Warehouse Operations</span>
            </div>
        </a>

        <div class="topbar-actions">
            <div class="user-pill">
                <div class="user-avatar">{{ strtoupper(substr($user->first_name ?? 'S', 0, 1)) }}</div>
                <span class="user-name-label">{{ $user->full_name }}</span>
            </div>

            <button type="button" class="cart-toggle-btn" id="openDrawerBtn" aria-label="Open Purchase Request items tray">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span>PR Draft</span>
                <span class="cart-badge-pill" id="cartCountBadge">0</span>
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <main class="pr-container">
        <!-- Subheader & Breadcrumbs -->
        <div class="pr-header-row">
            <div class="pr-title-area">
                <div class="breadcrumb-wrap">
                    <a href="{{ route('staff.requisition.index') }}" class="back-nav-link">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        <span>Requisitions</span>
                    </a>
                    <span class="crumb-sep">/</span>
                    <span class="crumb-active">Purchase Request (PR)</span>
                </div>
                <h1>
                    <span>Purchase Request (PR)</span>
                    <span class="form-code-badge">Appendix 60 • LGU Standard</span>
                </h1>
                <p class="pr-subtitle">
                    Select items from the existing inventory catalog below to prepare your Purchase Request. Item descriptions and specifications match existing warehouse stock definitions.
                </p>
            </div>

            <div class="office-duty-card">
                <div class="station-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/><path d="m14 8 3 3-3 3"/></svg>
                </div>
                <div class="station-text">
                    <strong>{{ $activeOffice->office_name ?? 'Central Office' }}</strong>
                    <span>{{ $activeOffice->office_code ?? 'OFF-PGSO' }} • Center 1061</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="toolbar-panel">
            <div class="search-input-wrap">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="searchInput" class="search-input" placeholder="Search variant name, item code, or barcode..." autocomplete="off">
            </div>

            <div class="category-pill-group" id="categoryFilters">
                <button type="button" class="cat-pill active" data-category="ALL">All Categories</button>
                @foreach($categories as $category)
                    <button type="button" class="cat-pill" data-category="{{ $category }}">{{ $category }}</button>
                @endforeach
            </div>

            <div class="catalog-meta-info">
                <span>Showing</span>
                <strong id="visibleCount">{{ $variants->count() }}</strong>
                <span>variants</span>
            </div>
        </div>

        <!-- Variants Grid: ONLY displays name, item code/SKU, and barcode. Warehouse stock, location, and meters are hidden. -->
        <div class="variant-grid" id="variantGrid">
            @forelse($variants as $variant)
                @php
                    $code = $variant->sku ?: ($variant->inventoryItem?->item_code ?: 'N/A');
                    $barcode = $variant->barcode ?: 'No Barcode';
                    $category = $variant->inventoryItem?->category ?: 'General';
                    $unit = $variant->unit ?: ($variant->inventoryItem?->unit ?: 'pcs');
                    $estCost = (float) ($variant->unit_cost ?: ($variant->inventoryItem?->unit_cost ?: 0));
                @endphp
                <div class="variant-card"
                     id="variant-card-{{ $variant->id }}"
                     data-id="{{ $variant->id }}"
                     data-name="{{ $variant->variant_name }}"
                     data-item-name="{{ $variant->inventoryItem?->name ?? '' }}"
                     data-code="{{ $code }}"
                     data-barcode="{{ $barcode }}"
                     data-unit="{{ $unit }}"
                     data-cost="{{ $estCost }}"
                     data-category="{{ $category }}">

                    <div>
                        <div class="card-top-row">
                            <span class="category-tag">{{ $category }}</span>
                            <span class="unit-tag">{{ $unit }}</span>
                        </div>

                        <!-- Variant Name -->
                        <h2 class="variant-title">{{ $variant->variant_name }}</h2>

                        <!-- Parent Item Reference -->
                        @if($variant->inventoryItem?->name)
                            <p class="parent-item-subtitle">{{ $variant->inventoryItem->name }}</p>
                        @endif

                        <!-- Identifiers (Code/SKU and Barcode) -->
                        <div class="id-details-box">
                            <div class="id-row">
                                <span class="id-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="5" y="5" rx="2"/><path d="M9 9h6v6H9z"/></svg>
                                    Item / SKU Code
                                </span>
                                <span class="id-value">{{ $code }}</span>
                            </div>
                            <div class="id-row">
                                <span class="id-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                                    Barcode
                                </span>
                                <span class="id-value barcode">{{ $barcode }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="card-action-row">
                        <div class="est-cost-text">
                            <span>Est.</span>
                            <strong>₱{{ number_format($estCost, 2) }}</strong>
                            <span style="font-size: 0.7188rem; color: var(--text-muted);">/ {{ $unit }}</span>
                        </div>

                        <button type="button" class="add-to-pr-btn" id="btn-add-{{ $variant->id }}" onclick="toggleCartItem({{ $variant->id }})">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            <span>Add to PR</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    <h3>No Inventory Item Variants Found</h3>
                    <p>There are currently no item variants recorded in the warehouse inventory database.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Side Drawer for PR Draft & Creation -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
    <div class="pr-drawer" id="prDrawer" aria-modal="true" role="dialog">
        <div class="drawer-header">
            <div class="drawer-title-group">
                <h2>
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span>Purchase Request Draft</span>
                </h2>
                <p>Appendix 60 Procurement Form</p>
            </div>
            <button type="button" class="drawer-close-btn" onclick="closeDrawer()" aria-label="Close draft">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="drawer-content">
            <!-- PR Metadata / Purpose Card -->
            <div class="form-section-card">
                <label for="prPurposeInput" class="form-label">
                    Purpose / Justification <span>*</span>
                </label>
                <textarea id="prPurposeInput" class="form-textarea" placeholder="State the official justification for this purchase request (e.g., Procurement of office consumables for administrative documentation)..."></textarea>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; font-size: 0.75rem; color: var(--text-muted);">
                    <span>Requesting Office: <strong>{{ $activeOffice->office_name ?? 'Central Office' }}</strong></span>
                    <span>Center: <strong>1061</strong></span>
                </div>
            </div>

            <!-- Selected Items Section -->
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <span style="font-size: 0.8125rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-muted);">
                        Selected Items (<span id="drawerItemsCount">0</span>)
                    </span>
                    <button type="button" onclick="clearAllDraftItems()" style="background:none; border:none; color: var(--accent-rose); font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                        Clear All
                    </button>
                </div>

                <div class="drawer-items-list" id="drawerItemsList">
                    <!-- Populated by JavaScript -->
                </div>

                <div id="drawerEmptyPlaceholder" style="display: block; text-align: center; padding: 36px 16px; background: #f8fafc; border: 1px dashed var(--border-subtle); border-radius: 12px;">
                    <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--text-muted); margin-bottom: 8px;"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <p style="font-size: 0.8125rem; color: var(--text-muted);">No variants selected yet. Browse the catalog and click <strong>Add to PR</strong> to include items.</p>
                </div>
            </div>
        </div>

        <div class="drawer-footer">
            <div class="totals-summary-card">
                <div class="total-label-box">
                    <span>Total Quantity</span>
                    <strong id="drawerTotalUnits">0 units</strong>
                </div>
                <div class="total-amount-box">
                    <span>Estimated Total</span>
                    <strong id="drawerTotalAmount">₱0.00</strong>
                </div>
            </div>

            <button type="button" class="submit-pr-btn" id="submitPrBtn" onclick="submitPurchaseRequest()">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>Submit Purchase Request</span>
            </button>
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="toast-notify" id="toastNotify">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span id="toastMsg">Purchase Request submitted successfully!</span>
    </div>

    <x-staffNav />

    <script>
        // State
        const prDraft = {}; // key: variantId, value: { id, name, itemName, code, barcode, unit, cost, quantity }

        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const categoryFilters = document.getElementById('categoryFilters');
        const variantGrid = document.getElementById('variantGrid');
        const visibleCount = document.getElementById('visibleCount');
        const cartCountBadge = document.getElementById('cartCountBadge');
        const drawerOverlay = document.getElementById('drawerOverlay');
        const prDrawer = document.getElementById('prDrawer');
        const openDrawerBtn = document.getElementById('openDrawerBtn');
        const drawerItemsList = document.getElementById('drawerItemsList');
        const drawerEmptyPlaceholder = document.getElementById('drawerEmptyPlaceholder');
        const drawerItemsCount = document.getElementById('drawerItemsCount');
        const drawerTotalUnits = document.getElementById('drawerTotalUnits');
        const drawerTotalAmount = document.getElementById('drawerTotalAmount');
        const submitPrBtn = document.getElementById('submitPrBtn');
        const prPurposeInput = document.getElementById('prPurposeInput');
        const toastNotify = document.getElementById('toastNotify');
        const toastMsg = document.getElementById('toastMsg');

        // Drawer Controls
        function openDrawer() {
            drawerOverlay.classList.add('open');
            prDrawer.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            drawerOverlay.classList.remove('open');
            prDrawer.classList.remove('open');
            document.body.style.overflow = '';
        }

        openDrawerBtn.addEventListener('click', openDrawer);

        // Toast Helper
        let toastTimeout;
        function showToast(message) {
            clearTimeout(toastTimeout);
            toastMsg.textContent = message;
            toastNotify.classList.add('visible');
            toastTimeout = setTimeout(() => {
                toastNotify.classList.remove('visible');
            }, 4000);
        }

        // Toggle Variant in Cart
        function toggleCartItem(id) {
            const card = document.getElementById(`variant-card-${id}`);
            if (!card) return;

            if (prDraft[id]) {
                // Remove from cart
                delete prDraft[id];
                updateCardVisual(id, false);
                showToast(`Removed from PR draft`);
            } else {
                // Add to cart
                prDraft[id] = {
                    id: parseInt(id),
                    name: card.dataset.name,
                    itemName: card.dataset.itemName,
                    code: card.dataset.code,
                    barcode: card.dataset.barcode,
                    unit: card.dataset.unit,
                    cost: parseFloat(card.dataset.cost) || 0,
                    quantity: 1
                };
                updateCardVisual(id, true);
                showToast(`Added "${card.dataset.name}" to PR draft`);
            }

            renderDrawer();
        }

        function updateCardVisual(id, inCart) {
            const card = document.getElementById(`variant-card-${id}`);
            const btn = document.getElementById(`btn-add-${id}`);
            if (!card || !btn) return;

            if (inCart) {
                card.classList.add('in-cart');
                btn.classList.add('active');
                btn.innerHTML = `
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Added (${prDraft[id]?.quantity || 1})</span>
                `;
            } else {
                card.classList.remove('in-cart');
                btn.classList.remove('active');
                btn.innerHTML = `
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    <span>Add to PR</span>
                `;
            }
        }

        function updateItemQuantity(id, qty) {
            const parsed = parseInt(qty);
            if (isNaN(parsed) || parsed < 1) {
                delete prDraft[id];
                updateCardVisual(id, false);
            } else {
                if (prDraft[id]) {
                    prDraft[id].quantity = parsed;
                    updateCardVisual(id, true);
                }
            }
            renderDrawer();
        }

        function clearAllDraftItems() {
            Object.keys(prDraft).forEach(id => {
                updateCardVisual(id, false);
                delete prDraft[id];
            });
            renderDrawer();
        }

        // Render Side Drawer Items
        function renderDrawer() {
            const items = Object.values(prDraft);
            const count = items.length;

            cartCountBadge.textContent = count;
            drawerItemsCount.textContent = count;

            if (count === 0) {
                drawerItemsList.innerHTML = '';
                drawerEmptyPlaceholder.style.display = 'block';
                drawerTotalUnits.textContent = '0 units';
                drawerTotalAmount.textContent = '₱0.00';
                submitPrBtn.disabled = true;
                return;
            }

            drawerEmptyPlaceholder.style.display = 'none';
            submitPrBtn.disabled = false;

            let totalUnits = 0;
            let totalAmount = 0;

            let html = '';
            items.forEach(item => {
                const lineTotal = item.quantity * item.cost;
                totalUnits += item.quantity;
                totalAmount += lineTotal;

                html += `
                    <div class="drawer-item-row" id="drawer-item-${item.id}">
                        <div class="item-main-header">
                            <div class="item-text-info">
                                <strong>${escapeHtml(item.name)}</strong>
                                <span>Code: ${escapeHtml(item.code)} • Barcode: ${escapeHtml(item.barcode)}</span>
                            </div>
                            <button type="button" class="remove-item-btn" onclick="toggleCartItem(${item.id})" title="Remove item">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <div class="item-control-row">
                            <div class="stepper-wrap">
                                <button type="button" class="step-btn" onclick="updateItemQuantity(${item.id}, ${item.quantity - 1})">-</button>
                                <input type="number" class="step-qty-input" value="${item.quantity}" min="1" onchange="updateItemQuantity(${item.id}, this.value)">
                                <button type="button" class="step-btn" onclick="updateItemQuantity(${item.id}, ${item.quantity + 1})">+</button>
                                <span style="font-size: 0.75rem; color: var(--text-muted); margin-left: 4px;">${escapeHtml(item.unit)}</span>
                            </div>
                            <div class="row-cost-calc">
                                <span>₱${item.cost.toFixed(2)} ea</span>
                                <strong>₱${lineTotal.toFixed(2)}</strong>
                            </div>
                        </div>
                    </div>
                `;
            });

            drawerItemsList.innerHTML = html;
            drawerTotalUnits.textContent = `${totalUnits} units`;
            drawerTotalAmount.textContent = `₱${totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        // Live Search & Category Filtering
        let activeCategory = 'ALL';

        function filterVariants() {
            const query = searchInput.value.toLowerCase().trim();
            const cards = variantGrid.querySelectorAll('.variant-card');
            let visible = 0;

            cards.forEach(card => {
                const name = (card.dataset.name || '').toLowerCase();
                const itemName = (card.dataset.itemName || '').toLowerCase();
                const code = (card.dataset.code || '').toLowerCase();
                const barcode = (card.dataset.barcode || '').toLowerCase();
                const cat = card.dataset.category || '';

                const matchesQuery = !query || name.includes(query) || itemName.includes(query) || code.includes(query) || barcode.includes(query);
                const matchesCat = activeCategory === 'ALL' || cat === activeCategory;

                if (matchesQuery && matchesCat) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            visibleCount.textContent = visible;
        }

        searchInput.addEventListener('input', filterVariants);

        categoryFilters.addEventListener('click', (e) => {
            const btn = e.target.closest('.cat-pill');
            if (!btn) return;

            categoryFilters.querySelectorAll('.cat-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCategory = btn.dataset.category;
            filterVariants();
        });

        // Submit Purchase Request
        async function submitPurchaseRequest() {
            const items = Object.values(prDraft);
            if (items.length === 0) {
                alert('Please select at least one inventory item variant.');
                return;
            }

            const purpose = prPurposeInput.value.trim();
            if (!purpose) {
                alert('Please enter a Purpose / Justification for this Purchase Request.');
                prPurposeInput.focus();
                return;
            }

            if (!confirm(`Are you sure you want to submit this Purchase Request with ${items.length} item(s)?`)) {
                return;
            }

            submitPrBtn.disabled = true;
            submitPrBtn.innerHTML = `
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="spin"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                <span>Submitting PR...</span>
            `;

            const payload = {
                purpose: purpose,
                responsibility_center_code: '1061',
                items: items.map(it => ({
                    id: it.id,
                    name: it.itemName || it.name,
                    variant_name: it.name,
                    quantity: it.quantity,
                    estimated_unit_cost: it.cost,
                    unit: it.unit
                }))
            };

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            try {
                const response = await fetch("{{ route('staff.requisition.pr.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast(data.message || 'Purchase Request successfully submitted!');
                    setTimeout(() => {
                        window.location.href = data.redirect_url || "{{ route('staff.request-stat') }}";
                    }, 1200);
                } else {
                    alert(data.message || 'Failed to submit Purchase Request. Please check your inputs and try again.');
                    submitPrBtn.disabled = false;
                    submitPrBtn.innerHTML = `
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Submit Purchase Request</span>
                    `;
                }
            } catch (err) {
                console.error(err);
                alert('An unexpected error occurred while submitting your Purchase Request. Please check your network and try again.');
                submitPrBtn.disabled = false;
                submitPrBtn.innerHTML = `
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Submit Purchase Request</span>
                `;
            }
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return (text || '').replace(/[&<>"']/g, m => map[m]);
        }
    </script>
</body>
</html>
