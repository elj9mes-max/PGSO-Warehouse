<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Stock Transfer to Offices</title>

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
            --primary-accent: #2563eb;
            --primary-light: #eff6ff;
            --success-color: #16a34a;
            --success-bg: #f0fdf4;
            --warning-color: #d97706;
            --warning-bg: #fffbeb;
            --danger-color: #dc2626;
            --danger-bg: #fef2f2;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .transfer-content-area {
            padding: 28px 32px;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .header-title-group p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .header-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .stat-pill strong {
            color: var(--text-primary);
            font-weight: 700;
        }

        .stat-pill-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
        }

        /* Alerts */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background-color: var(--danger-bg);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-banner svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* Two-Column Split Layout */
        .transfer-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 420px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .transfer-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Left Column: Filter & Search */
        .catalog-controls {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .search-and-toggles {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #a1a1aa;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: #fafafa;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #09090b;
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .filter-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            background: #ffffff;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .filter-toggle-btn.active {
            background: #09090b;
            color: #ffffff;
            border-color: #09090b;
        }

        .category-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            scrollbar-width: thin;
        }

        .category-pill-btn {
            padding: 6px 13px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .category-pill-btn:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        .category-pill-btn.active {
            background: #09090b;
            border-color: #09090b;
            color: #ffffff;
        }

        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 18px;
        }

        /* Item Card */
        .item-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .item-card:hover {
            border-color: #d4d4d8;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .item-card-header {
            padding: 18px 20px 14px 20px;
            border-bottom: 1px solid #f4f4f5;
        }

        .item-card-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .category-tag {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 3px 8px;
            background: #f4f4f5;
            color: var(--text-secondary);
            border-radius: 6px;
        }

        .code-tag {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 2px 7px;
            background: #f1f5f9;
            color: #334155;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .item-card-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.35;
            margin-bottom: 6px;
        }

        .item-card-desc {
            font-size: 0.8125rem;
            color: var(--text-muted);
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .item-card-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .meta-item svg {
            width: 14px;
            height: 14px;
            color: #a1a1aa;
        }

        /* Variants Section inside Card */
        .variants-list {
            padding: 14px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
            background: #fafafa;
        }

        .variants-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .variant-row {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.15s ease;
        }

        .variant-row:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        .variant-row.staged {
            border-color: #3b82f6;
            background: #f8faff;
        }

        .variant-row-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .variant-info {
            display: flex;
            align-items: center;
            gap: 9px;
            flex: 1;
        }

        .color-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .variant-name-code {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .variant-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .variant-subtext {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #71717a;
        }

        .available-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .available-badge.has-stock {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .available-badge.out-stock {
            background: #f4f4f5;
            color: #71717a;
            border: 1px solid #e4e4e7;
        }

        /* Transfer Control within Variant Row */
        .variant-action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 6px;
            border-top: 1px solid #f4f4f5;
        }

        .qty-stepper {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        .qty-step-btn {
            background: transparent;
            border: none;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-weight: 700;
            transition: background 0.15s ease;
        }

        .qty-step-btn:hover {
            background: #f4f4f5;
            color: var(--text-primary);
        }

        .qty-input {
            width: 44px;
            height: 28px;
            border: none;
            text-align: center;
            font-size: 0.8125rem;
            font-weight: 700;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: transparent;
        }

        .qty-input:focus {
            outline: none;
            background: #f8fafc;
        }

        .btn-add-transfer {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #09090b;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-add-transfer:hover {
            background: #27272a;
            transform: translateY(-1px);
        }

        .btn-add-transfer.staged-btn {
            background: #2563eb;
        }

        .btn-add-transfer:disabled {
            background: #e4e4e7;
            color: #a1a1aa;
            cursor: not-allowed;
            transform: none;
        }

        .no-variants-box {
            background: #ffffff;
            border: 1px dashed var(--border-subtle);
            border-radius: 12px;
            padding: 24px 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8125rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .no-variants-box a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.75rem;
        }

        .no-variants-box a:hover {
            text-decoration: underline;
        }

        /* Right Column: Sticky Live Office & Transfer Card */
        .live-office-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        .live-card-header {
            padding: 22px 24px 18px 24px;
            background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
            border-bottom: 1px solid var(--border-subtle);
        }

        .live-card-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .live-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .live-card-title svg {
            width: 22px;
            height: 22px;
            color: #2563eb;
        }

        .live-badge-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 3px 8px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 9999px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .office-select-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .office-select-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .office-dropdown {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            background: #ffffff;
            font-family: var(--font-sans);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .office-dropdown:focus {
            outline: none;
            border-color: #09090b;
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        /* Office Info Banner */
        .selected-office-info {
            margin-top: 14px;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .office-badge-box {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .office-badge-name {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #0f172a;
        }

        .office-badge-code {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #64748b;
        }

        .office-stock-count {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
            flex-shrink: 0;
        }

        .office-stock-count span {
            font-size: 0.6875rem;
            color: #64748b;
        }

        .office-stock-count strong {
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
        }

        /* Current Office Inventory Accordion */
        .office-holdings-accordion {
            margin-top: 8px;
            border-top: 1px dashed var(--border-subtle);
            padding-top: 8px;
        }

        .accordion-toggle {
            background: transparent;
            border: none;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            cursor: pointer;
            padding: 4px 0;
        }

        .accordion-toggle:hover {
            text-decoration: underline;
        }

        .office-holdings-list {
            display: none;
            margin-top: 8px;
            max-height: 140px;
            overflow-y: auto;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
            background: #ffffff;
            padding: 6px;
        }

        .office-holdings-list.show {
            display: block;
        }

        .holding-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            font-size: 0.75rem;
            border-bottom: 1px solid #f8fafc;
        }

        .holding-item:last-child {
            border-bottom: none;
        }

        /* Staged Transfers Section */
        .live-card-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-height: calc(100vh - 360px);
            overflow-y: auto;
        }

        .staged-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .staged-section-title {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .staged-count-pill {
            padding: 2px 7px;
            background: #09090b;
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
        }

        .clear-staged-btn {
            background: transparent;
            border: none;
            font-size: 0.75rem;
            color: var(--text-muted);
            cursor: pointer;
            text-decoration: underline;
        }

        .clear-staged-btn:hover {
            color: #dc2626;
        }

        /* Staged Item Card */
        .staged-item {
            background: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            animation: slideDown 0.2s ease;
        }

        .staged-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .staged-item-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .staged-parent-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .staged-variant-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .staged-sku-pill {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #64748b;
        }

        .btn-remove-staged {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .btn-remove-staged:hover {
            color: #dc2626;
        }

        .staged-item-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 6px;
            border-top: 1px dashed #e2e8f0;
        }

        .staged-calc-indicator {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .staged-calc-indicator strong {
            color: #16a34a;
        }

        /* Empty Staged State */
        .empty-staged-box {
            text-align: center;
            padding: 36px 16px;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .empty-staged-box svg {
            width: 42px;
            height: 42px;
            color: #cbd5e1;
        }

        .empty-staged-box p {
            font-size: 0.8125rem;
            line-height: 1.45;
            max-width: 240px;
        }

        /* Footer & Submit */
        .live-card-footer {
            padding: 20px 24px;
            background: #ffffff;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .transfer-summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .summary-row.total-row {
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
            font-weight: 800;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .btn-confirm-transfer {
            width: 100%;
            padding: 13px 20px;
            background: #09090b;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-weight: 700;
            font-family: var(--font-sans);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-confirm-transfer:hover:not(:disabled) {
            background: #18181b;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-confirm-transfer:disabled {
            background: #e4e4e7;
            color: #a1a1aa;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Toast feedback */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #09090b;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        @media (max-width: 768px) {
            .transfer-content-area {
                padding: 16px;
            }
            .items-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <x-sidebar>
        <div class="transfer-content-area">
            @php
                $allVariantsList = isset($variants) && $variants->isNotEmpty()
                    ? $variants
                    : (isset($items) ? $items->flatMap->variants : collect());

                $variantDropdownOptions = [];
                $variantsCatalogData = [];

                foreach ($allVariantsList as $v) {
                    $parent = $v->inventoryItem;
                    $vName = $v->variant_name;
                    $iName = $parent?->name;
                    $displayName = ($iName && $iName !== $vName) ? "{$iName} — {$vName}" : $vName;
                    $avail = (int) ($v->available_for_transfer ?? 0);
                    $unit = $v->effective_unit;
                    $code = $v->item_code ?: ($v->sku ?: ($v->barcode ?: 'VAR-'.$v->id));
                    $loc = $v->location ?: ($parent?->location ?: 'Main Warehouse');

                    $variantDropdownOptions[] = [
                        'id' => (string) $v->id,
                        'name' => $displayName,
                        'details' => "Code: {$code} • Avail: {$avail} {$unit} • {$loc}",
                    ];

                    $variantsCatalogData[$v->id] = [
                        'id' => (int) $v->id,
                        'variantName' => $vName,
                        'itemName' => $iName ?: $vName,
                        'unit' => $unit,
                        'available' => $avail,
                        'barcode' => (string) ($v->barcode ?: ''),
                        'sku' => (string) ($v->sku ?: ''),
                    ];
                }

                $totalTransferAvailable = $allVariantsList->sum('available_for_transfer');
            @endphp

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Transfer Stocks to Offices</h1>
                    <p>Allocate and dispatch inventory items from central warehouse • Real-time deduction from available transfer inventory</p>
                </div>

                <div class="header-stats">
                    <div class="stat-pill">
                        <span class="stat-pill-dot"></span>
                        <span>Offices: <strong>{{ $offices->count() }}</strong></span>
                    </div>
                    <div class="stat-pill">
                        <svg style="width: 14px; height: 14px; color: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span>Warehouse Items: <strong>{{ $items->count() }}</strong></span>
                    </div>
                    <div class="stat-pill">
                        <svg style="width: 14px; height: 14px; color: #16a34a;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Total Available to Transfer: <strong>{{ number_format($totalTransferAvailable) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Session Alert Banner -->
            @if(session('status'))
                <div class="alert-banner alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-banner alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Main Split Layout -->
            <div class="transfer-layout">

                <!-- Left Column: Search, Filters & Item Cards Grid -->
                <div class="catalog-section">

                    <!-- Search & Filter Controls -->
                    <div class="catalog-controls">
                        <div class="search-and-toggles">
                            <div class="search-input-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="catalogSearch" class="search-input" placeholder="Search item name, code, variant, barcode, SKU..." autofocus>
                            </div>

                            <button type="button" class="filter-toggle-btn active" id="filterStockOnlyBtn">
                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Has Available Stock</span>
                            </button>
                        </div>

                        <!-- Category Filters -->
                        <div class="category-pills">
                            <button type="button" class="category-pill-btn active" data-category="ALL">All Categories</button>
                            @foreach($categories as $category)
                                <button type="button" class="category-pill-btn" data-category="{{ $category }}">{{ $category }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div class="items-grid" id="itemsGrid">
                        @forelse($items as $item)
                            @php
                                $itemVariants = $item->variants;
                                $itemAvailableSum = $itemVariants->sum('available_for_transfer');
                                $searchTerms = strtolower($item->name . ' ' . $item->item_code . ' ' . $item->category . ' ' . $item->description . ' ' . $itemVariants->pluck('variant_name')->implode(' ') . ' ' . $itemVariants->pluck('barcode')->implode(' ') . ' ' . $itemVariants->pluck('sku')->implode(' '));
                            @endphp

                            <div class="item-card" 
                                 data-name="{{ strtolower($item->name) }}"
                                 data-code="{{ strtolower($item->item_code) }}"
                                 data-category="{{ $item->category }}"
                                 data-search="{{ $searchTerms }}"
                                 data-available="{{ $itemAvailableSum }}"
                                 data-variants-count="{{ $itemVariants->count() }}">
                                
                                <div class="item-card-header">
                                    <div class="item-card-topbar">
                                        <span class="category-tag">{{ $item->category }}</span>
                                        <span class="code-tag">{{ $item->item_code }}</span>
                                    </div>
                                    <h2 class="item-card-title">{{ $item->name }}</h2>
                                    @if($item->description)
                                        <p class="item-card-desc">{{ $item->description }}</p>
                                    @endif
                                    <div class="item-card-meta">
                                        <span class="meta-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            {{ $item->location ?: 'Main Warehouse' }}
                                        </span>
                                        <span class="meta-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            {{ $itemVariants->count() }} Variant(s)
                                        </span>
                                    </div>
                                </div>

                                <!-- Variants List -->
                                <div class="variants-list">
                                    <div class="variants-list-header">
                                        <span>Select Variants to Transfer</span>
                                        <span>Available</span>
                                    </div>

                                    @forelse($itemVariants as $variant)
                                        @php
                                            $lowerName = strtolower($variant->variant_name);
                                            $swatchColor = match(true) {
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

                                        <div class="variant-row" id="variantRow-{{ $variant->id }}" data-variant-id="{{ $variant->id }}">
                                            <div class="variant-row-top">
                                                <div class="variant-info">
                                                    <span class="color-dot" style="background-color: {{ $swatchColor }};"></span>
                                                    <div class="variant-name-code">
                                                        <span class="variant-name">{{ $variant->variant_name }}</span>
                                                        <span class="variant-subtext">Barcode: {{ $variant->barcode }} @if($variant->sku)• SKU: {{ $variant->sku }}@endif</span>
                                                    </div>
                                                </div>

                                                @if($variant->available_for_transfer > 0)
                                                    <span class="available-badge has-stock" id="availableBadge-{{ $variant->id }}">
                                                        {{ $variant->available_for_transfer }} {{ $variant->effective_unit }}
                                                    </span>
                                                @else
                                                    <span class="available-badge out-stock">0 {{ $variant->effective_unit }}</span>
                                                @endif
                                            </div>

                                            <div class="variant-action-bar">
                                                @if($variant->available_for_transfer > 0)
                                                    <div class="qty-stepper">
                                                        <button type="button" class="qty-step-btn" onclick="stepVariantQty({{ $variant->id }}, -1)">-</button>
                                                        <input type="number" 
                                                               id="inputQty-{{ $variant->id }}" 
                                                               class="qty-input" 
                                                               value="1" 
                                                               min="1" 
                                                               max="{{ $variant->available_for_transfer }}"
                                                               onchange="validateVariantInput({{ $variant->id }}, {{ $variant->available_for_transfer }})">
                                                        <button type="button" class="qty-step-btn" onclick="stepVariantQty({{ $variant->id }}, 1, {{ $variant->available_for_transfer }})">+</button>
                                                    </div>

                                                    <button type="button" 
                                                            class="btn-add-transfer" 
                                                            id="addBtn-{{ $variant->id }}"
                                                            data-variant-id="{{ $variant->id }}"
                                                            data-variant-name="{{ $variant->variant_name }}"
                                                            data-item-name="{{ $item->name }}"
                                                            data-unit="{{ $variant->effective_unit }}"
                                                            data-available="{{ $variant->available_for_transfer }}"
                                                            data-barcode="{{ $variant->barcode }}"
                                                            onclick="stageVariant(Number(this.dataset.variantId), this.dataset.variantName, this.dataset.itemName, this.dataset.unit, Number(this.dataset.available), this.dataset.barcode)">
                                                        <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                        <span>Add to Transfer</span>
                                                    </button>
                                                @else
                                                    <span style="font-size: 0.75rem; color: #a1a1aa; font-style: italic;">No stock available to transfer</span>
                                                    <button type="button" class="btn-add-transfer" disabled>Unavailable</button>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="no-variants-box">
                                            <p>No variants registered for this item.</p>
                                            <a href="{{ route('admin.inventory.show', $item) }}">Manage Variants in Inventory →</a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div style="grid-column: 1 / -1; background: #ffffff; border: 1px solid var(--border-subtle); border-radius: 16px; padding: 48px; text-align: center; color: var(--text-muted);">
                                <p>No inventory items registered in the warehouse yet.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Empty Filter Message -->
                    <div id="noMatchMessage" style="display: none; background: #ffffff; border: 1px dashed var(--border-subtle); border-radius: 16px; padding: 48px; text-align: center; color: var(--text-muted); margin-top: 16px;">
                        <svg style="width: 36px; height: 36px; color: #a1a1aa; margin-bottom: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">No matching items found</h3>
                        <p style="font-size: 0.8125rem;">Try adjusting your search query or reset the category filters.</p>
                        <button type="button" class="btn-add-transfer" style="margin-top: 14px;" onclick="resetFilters()">Reset Filters</button>
                    </div>

                </div>

                <!-- Right Column: Sticky Live Office & Transfer Card -->
                <aside class="live-card-column">
                    <div class="live-office-card">

                        <!-- Header & Office Selector -->
                        <div class="live-card-header">
                            <div class="live-card-title-row">
                                <div class="live-card-title">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 21h18"/>
                                        <path d="M9 8h1"/>
                                        <path d="M9 12h1"/>
                                        <path d="M9 16h1"/>
                                        <path d="M14 8h1"/>
                                        <path d="M14 12h1"/>
                                        <path d="M14 16h1"/>
                                        <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                                    </svg>
                                    <span>Stock Transfer</span>
                                </div>
                                <span class="live-badge-indicator">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #2563eb;"></span>
                                    Live Sync
                                </span>
                            </div>

                            <!-- Office Selection -->
                            <div class="office-select-field">
                                <label class="office-select-label" for="officeSelect">Target Destination Office *</label>
                                <x-dropdown-search 
                                    name="office_id_select"
                                    id="officeSelect"
                                    :options="$offices"
                                    valueKey="id"
                                    labelKey="office_name"
                                    subtextKey="office_code"
                                    :selected="$selectedOfficeId"
                                    placeholder="-- Select Receiving Office --"
                                    searchPlaceholder="Search office name or code..."
                                    :allowClear="true"
                                />
                            </div>

                            <!-- Live Selected Office Info Box -->
                            <div class="selected-office-info" id="selectedOfficeBanner" style="{{ empty($selectedOfficeId) ? 'display: none;' : '' }}">
                                <div class="office-badge-box">
                                    <span class="office-badge-name" id="selectedOfficeNameText">-</span>
                                    <span class="office-badge-code" id="selectedOfficeCodeText">-</span>
                                </div>
                                <div class="office-stock-count">
                                    <span>Current Holdings</span>
                                    <strong id="selectedOfficeHoldingsCount">0 Variants</strong>
                                </div>
                            </div>

                            <!-- Expandable Current Holdings Preview -->
                            <div class="office-holdings-accordion" id="officeHoldingsSection" style="{{ empty($selectedOfficeId) ? 'display: none;' : '' }}">
                                <button type="button" class="accordion-toggle" onclick="toggleOfficeHoldings()">
                                    <span id="accordionToggleText">View Office Inventory Items</span>
                                    <span id="accordionArrow">▼</span>
                                </button>
                                <div class="office-holdings-list" id="officeHoldingsList">
                                    <!-- Populated dynamically via JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Staged Items Section -->
                        <div class="live-card-body">
                            <!-- Variant Selection Dropdown Search Component -->
                            {{-- <div class="office-select-field" style="margin-bottom: 14px;">
                                <label class="office-select-label" for="variantPickerSelect" style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>Select Variant to Transfer</span>
                                    <span style="font-size: 0.6875rem; color: #2563eb; font-weight: 600; text-transform: none; letter-spacing: normal;">Quick Add by Name, Code, or SKU</span>
                                </label>
                                <x-dropdown-search 
                                    name="stage_variant_picker"
                                    id="variantPickerSelect"
                                    :options="$variantDropdownOptions"
                                    valueKey="id"
                                    labelKey="name"
                                    subtextKey="details"
                                    placeholder="-- Search & select variant to transfer --"
                                    searchPlaceholder="Type variant name, barcode, or SKU..."
                                    :allowClear="true"
                                />
                            </div> --}}

                            <div class="staged-section-header">
                                <span class="staged-section-title">
                                    <span>Staged for Transfer</span>
                                    <span class="staged-count-pill" id="stagedCountPill">0</span>
                                </span>
                                <button type="button" class="clear-staged-btn" id="clearStagedBtn" onclick="clearAllStaged()" style="display: none;">Clear All</button>
                            </div>

                            <!-- Staged Items Container -->
                            <div id="stagedItemsContainer" style="display: flex; flex-direction: column; gap: 10px;">
                                <!-- Dynamically populated by JS -->
                            </div>

                            <!-- Empty Staged State -->
                            <div class="empty-staged-box" id="emptyStagedBox">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v1"/>
                                    <path d="M18 8h4a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-4"/>
                                    <circle cx="5.5" cy="18.5" r="2.5"/>
                                    <circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                                <p>Select an office above, then choose item variants from the left to stage a transfer.</p>
                            </div>
                        </div>

                        <!-- Footer & Submit Form -->
                        <div class="live-card-footer">
                            <div class="transfer-summary-box">
                                <div class="summary-row">
                                    <span>Target Office</span>
                                    <strong id="summaryOfficeName">None Selected</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Staged Variants</span>
                                    <span id="summaryVariantsCount">0 items</span>
                                </div>
                                <div class="summary-row total-row">
                                    <span>Total Units to Dispatch</span>
                                    <span id="summaryTotalUnits" style="color: #2563eb;">0 units</span>
                                </div>
                            </div>

                            <!-- Hidden Form for POST submission -->
                            <form id="transferForm" method="POST" action="{{ route('admin.transfers.store') }}" onsubmit="validateBeforeSubmit(event); return false;">
                                @csrf
                                <input type="hidden" name="office_id" id="formOfficeId" value="{{ $selectedOfficeId ?? '' }}">
                                <div id="formTransfersInputs">
                                    <!-- Inputs will be injected on submit -->
                                </div>

                                <button type="submit" class="btn-confirm-transfer" id="submitTransferBtn" disabled>
                                    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    <span>Confirm & Transfer Stocks</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </aside>

            </div>

        </div>
    </x-sidebar>

    <!-- Embedded Offices Holdings Data for Live JS sync -->
    <script>
        const officesData = {
            @foreach($offices as $office)
                "{{ $office->id }}": {
                    id: {{ $office->id }},
                    name: @json($office->office_name),
                    code: @json($office->office_code),
                    holdings: [
                        @foreach($office->officeInventories as $oi)
                            @if($oi->variant)
                                {
                                    variantName: @json($oi->variant->variant_name),
                                    itemName: @json($oi->variant->inventoryItem?->name ?? $oi->variant->variant_name),
                                    quantity: {{ (int) ($oi->quantity ?? 1) }},
                                    unit: @json($oi->variant->effective_unit)
                                },
                            @endif
                        @endforeach
                    ]
                },
            @endforeach
        };

        const variantsCatalogData = @json($variantsCatalogData);

        // Reactive Application State
        let selectedOfficeId = @json($selectedOfficeId ?? '');
        let stagedItems = []; // [{ variantId, variantName, itemName, unit, available, quantity, barcode }]
        let activeCategory = 'ALL';
        let stockOnlyFilter = true;

        // Initialize on DOM load
        document.addEventListener('DOMContentLoaded', () => {
            const officeInput = document.getElementById('officeSelect');
            if (officeInput) {
                officeInput.addEventListener('change', () => {
                    selectedOfficeId = officeInput.value;
                    document.getElementById('formOfficeId').value = selectedOfficeId;
                    updateOfficeUI();
                    updateSummaryAndSubmitState();
                });
            }

            const variantPicker = document.getElementById('variantPickerSelect');
            if (variantPicker) {
                variantPicker.addEventListener('change', () => {
                    const vId = variantPicker.value;
                    if (!vId) return;
                    const vData = variantsCatalogData[vId];
                    if (vData) {
                        stageVariant(vData.id, vData.variantName, vData.itemName, vData.unit, vData.available, vData.barcode);
                        // Reset dropdown selection immediately so user can easily search and add the next variant
                        const wrapper = variantPicker.closest('.pgso-dropdown-search-wrapper');
                        if (wrapper) {
                            const clearBtn = wrapper.querySelector('.pgso-ds-clear-btn');
                            if (clearBtn) clearBtn.click();
                        }
                    }
                });
            }

            if (selectedOfficeId) {
                updateOfficeUI();
            }

            setupSearchAndFilters();
            applyCardFilters();
        });

        function updateOfficeUI() {
            const banner = document.getElementById('selectedOfficeBanner');
            const holdingsSection = document.getElementById('officeHoldingsSection');
            const nameEl = document.getElementById('selectedOfficeNameText');
            const codeEl = document.getElementById('selectedOfficeCodeText');
            const countEl = document.getElementById('selectedOfficeHoldingsCount');
            const summaryNameEl = document.getElementById('summaryOfficeName');
            const holdingsList = document.getElementById('officeHoldingsList');

            if (!selectedOfficeId || !officesData[selectedOfficeId]) {
                banner.style.display = 'none';
                holdingsSection.style.display = 'none';
                summaryNameEl.textContent = 'None Selected';
                return;
            }

            const data = officesData[selectedOfficeId];
            banner.style.display = 'flex';
            holdingsSection.style.display = 'block';
            nameEl.textContent = data.name;
            codeEl.textContent = data.code;
            countEl.textContent = `${data.holdings.length} Variant(s)`;
            summaryNameEl.textContent = data.code;

            // Render holdings preview
            if (data.holdings.length === 0) {
                holdingsList.innerHTML = `<div style="text-align: center; padding: 10px; font-size: 0.75rem; color: #a1a1aa;">No items currently in this office.</div>`;
            } else {
                holdingsList.innerHTML = data.holdings.map(h => `
                    <div class="holding-item">
                        <span style="font-weight: 600; color: #1e293b;">${h.itemName} (${h.variantName})</span>
                        <span style="font-weight: 700; color: #2563eb;">${h.quantity} ${h.unit}</span>
                    </div>
                `).join('');
            }
        }

        function toggleOfficeHoldings() {
            const list = document.getElementById('officeHoldingsList');
            const arrow = document.getElementById('accordionArrow');
            const isShown = list.classList.toggle('show');
            arrow.textContent = isShown ? '▲' : '▼';
        }

        // Stepper Quantity in Catalog Card
        function stepVariantQty(variantId, delta, maxAvailable = 999999) {
            const input = document.getElementById(`inputQty-${variantId}`);
            if (!input) return;

            let current = parseInt(input.value) || 1;
            let next = current + delta;
            if (next < 1) next = 1;
            if (next > maxAvailable) next = maxAvailable;

            input.value = next;
        }

        function validateVariantInput(variantId, maxAvailable) {
            const input = document.getElementById(`inputQty-${variantId}`);
            if (!input) return;

            let val = parseInt(input.value) || 1;
            if (val < 1) val = 1;
            if (val > maxAvailable) val = maxAvailable;
            input.value = val;
        }

        // Stage Variant to Transfer Cart
        function stageVariant(variantId, variantName, itemName, unit, available, barcode) {
            if (!selectedOfficeId) {
                showToast('⚠️ Please select a destination office first!');
                const triggerBtn = document.getElementById('ds_container_officeSelect_trigger');
                if (triggerBtn) {
                    triggerBtn.focus();
                    triggerBtn.click();
                }
                return;
            }

            if (available <= 0) {
                showToast(`⚠️ '${variantName}' has no available stock to transfer.`);
                return;
            }

            const input = document.getElementById(`inputQty-${variantId}`);
            const qtyToAdd = input ? parseInt(input.value) || 1 : 1;

            const existingIndex = stagedItems.findIndex(i => i.variantId === variantId);

            if (existingIndex > -1) {
                // Update quantity in stage
                let newQty = stagedItems[existingIndex].quantity + qtyToAdd;
                if (newQty > available) newQty = available;
                stagedItems[existingIndex].quantity = newQty;
                showToast(`Updated '${variantName}' to ${newQty} ${unit}`);
            } else {
                stagedItems.push({
                    variantId,
                    variantName,
                    itemName,
                    unit,
                    available,
                    barcode,
                    quantity: qtyToAdd
                });
                showToast(`Staged ${qtyToAdd}x '${variantName}' for transfer`);
            }

            renderStagedUI();
            updateCatalogRowHighlight(variantId);
        }

        function updateCatalogRowHighlight(variantId) {
            const row = document.getElementById(`variantRow-${variantId}`);
            const btn = document.getElementById(`addBtn-${variantId}`);
            if (!row || !btn) return;

            const staged = stagedItems.find(i => i.variantId === variantId);
            if (staged) {
                row.classList.add('staged');
                btn.classList.add('staged-btn');
                btn.innerHTML = `<span>✓ Staged (${staged.quantity})</span>`;
            } else {
                row.classList.remove('staged');
                btn.classList.remove('staged-btn');
                btn.innerHTML = `
                    <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Add to Transfer</span>
                `;
            }
        }

        // Render Live Staged Items in Right Card
        function renderStagedUI() {
            const container = document.getElementById('stagedItemsContainer');
            const emptyBox = document.getElementById('emptyStagedBox');
            const clearBtn = document.getElementById('clearStagedBtn');
            const countPill = document.getElementById('stagedCountPill');

            countPill.textContent = stagedItems.length;

            if (stagedItems.length === 0) {
                container.innerHTML = '';
                emptyBox.style.display = 'flex';
                clearBtn.style.display = 'none';
                updateSummaryAndSubmitState();
                return;
            }

            emptyBox.style.display = 'none';
            clearBtn.style.display = 'inline-block';

            container.innerHTML = stagedItems.map((item, idx) => {
                const remaining = item.available - item.quantity;
                return `
                    <div class="staged-item">
                        <div class="staged-item-top">
                            <div class="staged-item-info">
                                <span class="staged-parent-title">${escapeHtml(item.itemName)}</span>
                                <span class="staged-variant-title">${escapeHtml(item.variantName)}</span>
                                <span class="staged-sku-pill">Barcode: ${escapeHtml(item.barcode)}</span>
                            </div>
                            <button type="button" class="btn-remove-staged" onclick="removeStagedItem(${item.variantId})" title="Remove item">
                                <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="staged-item-controls">
                            <div class="staged-calc-indicator">
                                <span>Avail: <strong>${item.available}</strong></span>
                                <span>→ Rem: <strong>${remaining}</strong></span>
                            </div>

                            <div class="qty-stepper">
                                <button type="button" class="qty-step-btn" onclick="adjustStagedQty(${item.variantId}, -1)">-</button>
                                <input type="number" 
                                       class="qty-input" 
                                       value="${item.quantity}" 
                                       min="1" 
                                       max="${item.available}" 
                                       onchange="setStagedQtyDirect(${item.variantId}, this.value)">
                                <button type="button" class="qty-step-btn" onclick="adjustStagedQty(${item.variantId}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            updateSummaryAndSubmitState();
        }

        function adjustStagedQty(variantId, delta) {
            const item = stagedItems.find(i => i.variantId === variantId);
            if (!item) return;

            let next = item.quantity + delta;
            if (next < 1) next = 1;
            if (next > item.available) next = item.available;

            item.quantity = next;
            renderStagedUI();
            updateCatalogRowHighlight(variantId);
        }

        function setStagedQtyDirect(variantId, rawValue) {
            const item = stagedItems.find(i => i.variantId === variantId);
            if (!item) return;

            let val = parseInt(rawValue) || 1;
            if (val < 1) val = 1;
            if (val > item.available) val = item.available;

            item.quantity = val;
            renderStagedUI();
            updateCatalogRowHighlight(variantId);
        }

        function removeStagedItem(variantId) {
            stagedItems = stagedItems.filter(i => i.variantId !== variantId);
            renderStagedUI();
            updateCatalogRowHighlight(variantId);
            showToast('Item removed from transfer stage');
        }

        function clearAllStaged() {
            const ids = stagedItems.map(i => i.variantId);
            stagedItems = [];
            renderStagedUI();
            ids.forEach(id => updateCatalogRowHighlight(id));
            showToast('Cleared all staged items');
        }

        function updateSummaryAndSubmitState() {
            const totalVariants = stagedItems.length;
            const totalUnits = stagedItems.reduce((acc, curr) => acc + curr.quantity, 0);

            document.getElementById('summaryVariantsCount').textContent = `${totalVariants} variant(s)`;
            document.getElementById('summaryTotalUnits').textContent = `${totalUnits} units`;

            const submitBtn = document.getElementById('submitTransferBtn');
            const canSubmit = selectedOfficeId && totalVariants > 0;
            submitBtn.disabled = !canSubmit;
        }

        // Validate & Inject hidden inputs before submit
        async function validateBeforeSubmit(e) {
            if (e && e.preventDefault) {
                e.preventDefault();
            }

            if (!selectedOfficeId) {
                if (window.showAlert) {
                    showAlert({
                        type: 'warning',
                        title: 'Target Office Required',
                        message: 'Please select a destination office from the dropdown before confirming the transfer.',
                        confirmText: 'Understood'
                    });
                } else {
                    alert('Please select a target office.');
                }
                return false;
            }

            if (stagedItems.length === 0) {
                if (window.showAlert) {
                    showAlert({
                        type: 'warning',
                        title: 'No Items Staged',
                        message: 'Please stage at least one item variant for transfer before submitting.',
                        confirmText: 'Understood'
                    });
                } else {
                    alert('Please stage at least one item variant for transfer.');
                }
                return false;
            }

            const officeName = officesData[selectedOfficeId]?.name || 'the selected office';
            const totalUnits = stagedItems.reduce((acc, curr) => acc + curr.quantity, 0);

            let confirmed = false;
            if (window.showConfirm) {
                confirmed = await showConfirm({
                    type: 'warning',
                    title: 'Confirm Office Stock Transfer',
                    message: `Are you sure you want to transfer ${totalUnits} units (${stagedItems.length} variants) to ${officeName}? This will deduct them from Available for Transfer and log the transaction.`,
                    confirmText: 'Yes, Transfer Stock',
                    cancelText: 'Cancel'
                });
            } else {
                confirmed = confirm(`Are you sure you want to transfer ${totalUnits} units (${stagedItems.length} variants) to ${officeName}? This will deduct them from Available for Transfer.`);
            }

            if (!confirmed) {
                return false;
            }

            // Injects hidden form fields
            document.getElementById('formOfficeId').value = selectedOfficeId;
            const container = document.getElementById('formTransfersInputs');
            container.innerHTML = '';

            stagedItems.forEach((item, index) => {
                const varInput = document.createElement('input');
                varInput.type = 'hidden';
                varInput.name = `transfers[${index}][variant_id]`;
                varInput.value = item.variantId;
                container.appendChild(varInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `transfers[${index}][quantity]`;
                qtyInput.value = item.quantity;
                container.appendChild(qtyInput);
            });

            document.getElementById('transferForm').submit();
            return true;
        }

        // Live Catalog Search & Filters
        function setupSearchAndFilters() {
            const searchInput = document.getElementById('catalogSearch');
            searchInput.addEventListener('input', applyCardFilters);

            const toggleStockBtn = document.getElementById('filterStockOnlyBtn');
            toggleStockBtn.addEventListener('click', () => {
                stockOnlyFilter = !stockOnlyFilter;
                toggleStockBtn.classList.toggle('active', stockOnlyFilter);
                applyCardFilters();
            });

            const categoryBtns = document.querySelectorAll('.category-pill-btn');
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeCategory = btn.getAttribute('data-category');
                    applyCardFilters();
                });
            });
        }

        function applyCardFilters() {
            const query = (document.getElementById('catalogSearch').value || '').trim().toLowerCase();
            const cards = document.querySelectorAll('.item-card');
            let matchedCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const category = card.getAttribute('data-category');
                const available = parseInt(card.getAttribute('data-available')) || 0;

                const matchesQuery = query === '' || searchData.includes(query);
                const matchesCategory = activeCategory === 'ALL' || category === activeCategory;
                const matchesStock = !stockOnlyFilter || available > 0;

                if (matchesQuery && matchesCategory && matchesStock) {
                    card.style.display = 'flex';
                    matchedCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noMatch = document.getElementById('noMatchMessage');
            noMatch.style.display = matchedCount === 0 ? 'block' : 'none';
        }

        function resetFilters() {
            document.getElementById('catalogSearch').value = '';
            activeCategory = 'ALL';
            stockOnlyFilter = false;
            document.getElementById('filterStockOnlyBtn').classList.remove('active');
            document.querySelectorAll('.category-pill-btn').forEach(b => {
                b.classList.toggle('active', b.getAttribute('data-category') === 'ALL');
            });
            applyCardFilters();
        }

        // Toast Helper
        function showToast(msg) {
            const existing = document.querySelector('.toast-notification');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <svg style="width: 16px; height: 16px; color: #10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>${escapeHtml(msg)}</span>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
