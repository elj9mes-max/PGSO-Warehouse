<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Receive PO Supplies</title>
    
    <!-- Modern Sans Font for SaaS Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
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
            --btn-hover: #27272a;
            --btn-text: #ffffff;
            --status-green: #10b981;
            --status-amber: #f59e0b;
            --status-blue: #2563eb;
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

        .po-content-area {
            padding: 32px 36px;
            max-width: 1400px;
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

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .header-title-group p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 4px;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-outline:hover {
            background-color: #f4f4f5;
            color: var(--text-primary);
            border-color: var(--border-hover);
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

        /* Banner / Alerts */
        .alert-status {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Delivery Confirmation Banner with Print Action */
        .delivery-received-banner {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #86efac;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.12);
        }

        .delivery-received-body {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            flex: 1;
        }

        .delivery-received-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #16a34a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.25);
        }

        .delivery-received-icon svg {
            width: 20px;
            height: 20px;
        }

        .delivery-received-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .delivery-received-heading {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #14532d;
            margin: 0;
        }

        .delivery-received-text {
            font-size: 0.8125rem;
            color: #166534;
            line-height: 1.4;
            margin: 0;
        }

        .delivery-received-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 5px;
        }

        .delivery-received-meta .meta-tag {
            font-size: 0.75rem;
            background: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #bbf7d0;
        }

        .delivery-received-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-print-iar-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            background: #059669;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.8125rem;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.35);
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-print-iar-primary:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.45);
            color: #ffffff;
        }

        .btn-print-iar-secondary {
            display: inline-flex;
            align-items: center;
            padding: 9px 14px;
            background: #ffffff;
            color: #166534;
            font-weight: 600;
            font-size: 0.8125rem;
            border-radius: 8px;
            border: 1px solid #86efac;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-print-iar-secondary:hover {
            background: #dcfce7;
            color: #14532d;
        }

        /* Action Column in Recent Batches Table */
        .btn-action-iar {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            background: #f0fdf4;
            color: #059669;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.12s ease;
            white-space: nowrap;
        }

        .btn-action-iar:hover {
            background: #059669;
            color: #ffffff;
            border-color: #059669;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }

        /* Cards */
        .form-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .card-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f4f4f5;
        }

        .card-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: #f4f4f5;
            color: #09090b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .card-title {
            font-size: 1.0625rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .card-subtitle {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Grid Form Layout */
        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-field label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .form-field .field-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            height: 40px;
            padding: 0 14px;
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.15s ease;
        }

        .form-textarea {
            height: auto;
            min-height: 80px;
            padding: 10px 14px;
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            background-color: #ffffff;
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        /* Destination Callout Box */
        .destination-callout {
            border-radius: 10px;
            padding: 14px 18px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 8px;
        }

        .destination-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-central {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .badge-office {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* Quick Add Component Box */
        .variant-quick-add-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 22px;
        }

        .quick-add-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .quick-add-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-add-stage-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 16px;
            margin-top: 14px;
            animation: fadeIn 0.15s ease-out;
        }

        /* Price Update Badge */
        .price-update-badge {
            display: none;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            color: #b45309;
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            padding: 2px 8px;
            border-radius: 6px;
            margin-top: 5px;
            width: fit-content;
            font-weight: 600;
            animation: fadeIn 0.15s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-3px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Line Items Table */
        .items-table-container {
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8438rem;
            text-align: left;
        }

        .items-table thead {
            background-color: #f4f4f5;
            border-bottom: 1px solid var(--border-subtle);
        }

        .items-table th {
            padding: 12px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .items-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border-subtle);
            vertical-align: middle;
            background-color: #ffffff;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:hover td {
            background-color: #fafafa;
        }

        .btn-del-row {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #fee2e2;
            background-color: #fff5f5;
            color: #dc2626;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .btn-del-row:hover {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Summary Bar */
        .summary-bar {
            background-color: #09090b;
            color: #ffffff;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 8px;
        }

        .summary-metrics {
            display: flex;
            align-items: center;
            gap: 28px;
            flex-wrap: wrap;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #a1a1aa;
        }

        .summary-val {
            font-size: 1.375rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            font-family: var(--font-mono);
            margin-top: 2px;
        }

        .btn-submit-delivery {
            height: 44px;
            padding: 0 24px;
            background-color: #ffffff;
            color: #09090b;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.12);
        }

        .btn-submit-delivery:hover {
            background-color: #f4f4f5;
            transform: translateY(-1px);
        }

        /* Recent Batches Section */
        .history-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .batch-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #f4f4f5;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid #e4e4e7;
            display: inline-block;
        }

        .po-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            background-color: #eff6ff;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
            display: inline-block;
        }

        .status-pill-batch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        .status-active {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .status-depleted {
            background-color: #f4f4f5;
            color: #71717a;
            border: 1px solid #e4e4e7;
        }

        .po-pill-link {
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .po-pill-link:hover {
            background-color: #dbeafe;
            border-color: #93c5fd;
            transform: translateY(-1px);
        }

        /* Modal Overlay & Base */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.6);
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
            max-width: 640px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
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
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .modal-badge-green {
            display: inline-block;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #047857;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 2px 8px;
            border-radius: 9999px;
            margin-bottom: 4px;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
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
            padding: 20px 24px;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-subtle);
            background-color: #fafafa;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
        }

        .modal-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .modal-info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .modal-info-card .info-label {
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .modal-info-card .info-val {
            font-size: 0.875rem;
            color: var(--text-primary);
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .modal-table-container {
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            background: #ffffff;
        }

        .modal-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }

        .modal-items-table th {
            background: #f4f4f5;
            padding: 8px 12px;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .modal-items-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f4f4f5;
            color: var(--text-primary);
        }

        .modal-items-table tr:last-child td {
            border-bottom: none;
        }

        .modal-totals-box {
            margin-top: 14px;
            background: #09090b;
            color: #ffffff;
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-totals-box .total-label {
            font-size: 0.6875rem;
            color: #a1a1aa;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
        }

        .modal-totals-box .total-number {
            font-size: 1.25rem;
            font-weight: 800;
            font-family: var(--font-mono);
            color: #ffffff;
        }

        .modal-totals-box .total-amount {
            font-size: 1.25rem;
            font-weight: 800;
            font-family: var(--font-mono);
            color: #34d399;
        }

        .modal-iar-card {
            margin-top: 14px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #86efac;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .iar-checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .iar-checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #059669;
        }

        .btn-confirm-receive {
            background-color: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(5, 150, 105, 0.3);
        }

        .btn-confirm-receive:hover {
            background-color: #047857 !important;
            border-color: #047857 !important;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    @php
        // Prepare options for Destination Office dropdown-search component
        $destinationOptions = collect([
            [
                'id' => '',
                'name' => '🏢 PGSO Central Warehouse (Central Stock Custody)',
                'code' => 'CENTRAL-MAIN',
            ]
        ])->concat($offices->map(function ($o) {
            return [
                'id' => (string) $o->id,
                'name' => '🏛️ ' . $o->office_name,
                'code' => $o->office_code ?? 'OFFICE',
            ];
        }))->toArray();

        // Prepare options for Supply Variants dropdown-search component
        $variantDropdownOptions = [];
        $variantsList = [];

        $allVariants = isset($variants) && $variants->isNotEmpty()
            ? $variants
            : (isset($items) ? $items->flatMap->variants : collect());

        foreach ($allVariants as $variant) {
            $item = $variant->inventoryItem;
            $unit = $variant->effective_unit;
            $code = $variant->item_code ?: ($variant->sku ?: ($variant->barcode ?: ($item?->item_code ?? 'VAR-'.$variant->id)));
            $cost = (float) ($variant->unit_cost ?: ($item?->unit_cost ?? 0.00));
            $qty = (int) ($variant->quantity ?? 0);
            
            $displayName = $item && $item->name && $item->name !== $variant->variant_name
                ? $item->name . ' — ' . $variant->variant_name
                : $variant->variant_name;

            $variantDropdownOptions[] = [
                'id' => (string) $variant->id,
                'name' => $displayName,
                'details' => $code . ' • ' . $qty . ' ' . $unit . ' in stock @ ₱' . number_format($cost, 2),
            ];

            $variantsList[] = [
                'id' => (string) $variant->id,
                'itemName' => $item?->name ?? $variant->variant_name,
                'itemCode' => $item?->item_code ?? $variant->item_code ?? $code,
                'category' => $variant->category ?: ($item?->category ?? 'General'),
                'variantName' => $variant->variant_name,
                'variantDescription' => $variant->variant_description ?: ($item?->description ?? ''),
                'sku' => $variant->sku,
                'barcode' => $variant->barcode,
                'unit' => $unit,
                'currentQty' => $qty,
                'unitCost' => $cost,
            ];
        }
    @endphp

    <x-sidebar>
        <div class="po-content-area">

            <!-- Breadcrumbs -->
            <nav class="breadcrumb-nav">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                    <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Dashboard</span>
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('admin.inventory') }}" class="breadcrumb-link">
                    <span>Inventory</span>
                </a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">Receive Purchase Order Supplies</span>
            </nav>

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Receive Purchase Order Supplies</h1>
                    <p>Record delivered inventory supplies (variants) • Set office destination custody • Separate pricing into distinct batches</p>
                </div>

                <div class="header-actions">
                    <a href="{{ session('received_po') ? route('admin.inventory.iar-form', ['po' => session('received_po')]) : route('admin.inventory.iar-form') }}" class="btn-outline" target="_blank" style="color: #059669; border-color: #a7f3d0; background: #f0fdf4;">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>Print IAR Form (A4)</span>
                    </a>
                    <a href="{{ route('admin.inventory') }}" class="btn-outline">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        <span>Back to Inventory</span>
                    </a>
                </div>
            </div>

            <!-- Flash Status Alert with Print IAR Action -->
            @if(session('status'))
                <div class="delivery-received-banner" id="deliveryReceivedBanner">
                    <div class="delivery-received-body">
                        <div class="delivery-received-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="delivery-received-details">
                            <h3 class="delivery-received-heading">Delivery Received Successfully</h3>
                            <p class="delivery-received-text">{{ session('status') }}</p>
                            @if(session('received_po'))
                                <div class="delivery-received-meta">
                                    <span class="meta-tag"><strong>PO Number:</strong> {{ session('received_po') }}</span>
                                    @if(session('received_destination'))
                                        <span class="meta-tag"><strong>Destination:</strong> {{ session('received_destination') }}</span>
                                    @endif
                                    @if(session('received_supplier'))
                                        <span class="meta-tag"><strong>Supplier:</strong> {{ session('received_supplier') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(session('received_po'))
                        <div class="delivery-received-actions">
                            <a href="{{ route('admin.inventory.iar-form', ['po' => session('received_po'), 'print' => 1]) }}" target="_blank" class="btn-print-iar-primary" id="printIarActionBtn" title="Print Acceptance & Inspection Report immediately">
                                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                <span>Print IAR Form</span>
                            </a>
                            <a href="{{ route('admin.inventory.iar-form', ['po' => session('received_po')]) }}" target="_blank" class="btn-print-iar-secondary" title="Preview A4 document before printing">
                                <span>Preview IAR</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Errors Alert -->
            @if($errors->any())
                <div class="alert-error">
                    <strong style="display: block; margin-bottom: 6px;">Please correct the following errors:</strong>
                    <ul style="padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Main Form -->
            <form action="{{ route('admin.inventory.purchase-request.store') }}" method="POST" id="poReceiveForm">
                @csrf

                <!-- Section 1: PO & Destination Details -->
                <div class="form-card">
                    <div class="card-header-bar">
                        <div class="card-title-group">
                            <div class="card-icon-badge">
                                <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path></svg>
                            </div>
                            <div>
                                <h2 class="card-title">1. Delivery Custody & Destination</h2>
                                <p class="card-subtitle">Choose where the incoming supplies will be stored or assigned</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <!-- Destination Office Selector using Dropdown Search Component -->
                        <div class="form-field" style="grid-column: span 2;">
                            <label for="officeDestinationSelect">Stock Destination Custody <span style="color: #ef4444;">*</span></label>
                            
                            <x-dropdown-search 
                                name="office_id"
                                id="officeDestinationSelect"
                                :options="$destinationOptions"
                                valueKey="id"
                                labelKey="name"
                                subtextKey="code"
                                :selected="old('office_id', '')"
                                placeholder="🏢 PGSO Central Warehouse (Central Storage Custody)"
                                searchPlaceholder="Type office name or code to search destination..."
                                :allowClear="true"
                            />

                            <!-- Destination Live Context Box -->
                            {{-- <div class="destination-callout" id="destCallout">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span class="destination-badge badge-central" id="destBadge">Central Warehouse</span>
                                    <span style="font-size: 0.8125rem; color: var(--text-secondary);" id="destDesc">
                                        Items will be catalogued into PGSO Central Warehouse custody and made available for departmental issuance and transfers.
                                    </span>
                                </div>
                            </div> --}}
                        </div>

                        <!-- Delivery Date -->
                        <div class="form-field">
                            <label for="delivery_date">Delivery Date <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="delivery_date" id="delivery_date" class="form-input" value="{{ old('delivery_date', date('Y-m-d')) }}" required>
                            {{-- <span class="field-hint">Date items were received at warehouse</span> --}}
                        </div>
                    </div>

                    <!-- Hidden Flags for IAR Generation -->
                    <input type="hidden" name="generate_iar" id="hiddenGenerateIar" value="1">
                    <input type="hidden" name="auto_print" id="hiddenAutoPrint" value="0">

                    <div class="form-grid-3">
                        <!-- PO Number -->
                        <div class="form-field">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <label for="po_number">Purchase Order (PO) No.</label>
                                <button type="button" onclick="generatePoNumber()" style="background: none; border: none; color: #2563eb; font-size: 0.6875rem; font-weight: 600; cursor: pointer;">⚡ Auto-Generate</button>
                            </div>
                            <input type="text" name="po_number" id="po_number" class="form-input" placeholder="e.g. PO-2026-0482" value="{{ old('po_number') }}">
                        </div>

                        <!-- Supplier / Vendor -->
                        <div class="form-field">
                            <label for="supplier">Supplier / Merchant</label>
                            <input type="text" name="supplier" id="supplier" class="form-input" placeholder="e.g. Advance Paper Corp / Crown Trading" value="{{ old('supplier') }}">
                        </div>

                        <!-- Sales Invoice / DR Number -->
                        <div class="form-field">
                            <label for="invoice_number">Invoice / DR No. (Optional)</label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-input" placeholder="e.g. SI-77291 / DR-0482" value="{{ old('invoice_number') }}">
                        </div>
                    </div>

                    <div class="form-grid-2" style="margin-top: 14px;">
                        <!-- PR Number (Optional) -->
                        <div class="form-field">
                            <label for="pr_number">Purchase Request (PR) No. (Optional)</label>
                            <input type="text" name="pr_number" id="pr_number" class="form-input" placeholder="e.g. PR-2026-0112" value="{{ old('pr_number') }}">
                        </div>

                        <!-- Delivery Remarks -->
                        <div class="form-field">
                            <label for="remarks">Delivery Remarks & Reference Notes</label>
                            <input type="text" name="remarks" id="remarks" class="form-input" placeholder="e.g. Delivered per Sales Invoice #77291, inspected in good condition" value="{{ old('remarks') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Line Items (Variants) with Dropdown Search Component -->
                <div class="form-card">
                    <div class="card-header-bar">
                        <div class="card-title-group">
                            <div class="card-icon-badge">
                                <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path></svg>
                            </div>
                            <div>
                                <h2 class="card-title">2. Delivered Supply Items (Variants)</h2>
                                <p class="card-subtitle">Use the searchable dropdown to find catalogued supply variants, then add them to the PO delivery</p>
                            </div>
                        </div>
                    </div>

                    <!-- Supply Variant Search & Add Box using Dropdown Search Component -->
                    <div class="variant-quick-add-box">
                        <div class="quick-add-header">
                            <span class="quick-add-title">
                                <svg style="width: 16px; height: 16px; color: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <span>Search & Add Supply Variant to PO Receipt</span>
                            </span>
                            <span style="font-size: 0.75rem; color: #16a34a; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Selecting any variant immediately adds it to the receipt table below</span>
                            </span>
                        </div>

                        <!-- Dropdown Search for Supply Variant Selection -->
                        <x-dropdown-search 
                            name="variant_picker"
                            id="variantSearchPicker"
                            :options="$variantDropdownOptions"
                            valueKey="id"
                            labelKey="name"
                            subtextKey="details"
                            placeholder="-- Click here to search & add a supply variant to the PO receipt --"
                            searchPlaceholder="Type item name, variant, barcode, or SKU to add..."
                            :allowClear="true"
                        />
                    </div>

                    <!-- Delivery Items Table -->
                    <div class="items-table-container">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 32%;">Supply Item & Variant</th>
                                    <th style="width: 14%;">Qty Delivered</th>
                                    <th style="width: 15%;">Arrival Price (₱)</th>
                                    <th style="width: 18%;">Batch Notes / Expiry</th>
                                    <th style="width: 15%; text-align: right;">Line Total (₱)</th>
                                    <th style="width: 6%; text-align: center;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <tr id="emptyRowPlaceholder">
                                    <td colspan="6" style="text-align: center; padding: 44px 20px; color: var(--text-muted);">
                                        <svg style="width: 34px; height: 34px; color: #d4d4d8; margin: 0 auto 10px; display: block;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="16" height="13" x="1" y="3" rx="2"></rect><path d="M16 8h4l3 3v5h-7V8z"></path><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                        <strong style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 4px;">No items added yet</strong>
                                        <span>Click the dropdown search above to add delivered supply variants to this PO receipt.</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Bottom Bar -->
                    <div class="summary-bar">
                        <div class="summary-metrics">
                            <div class="summary-item">
                                <span class="summary-label">Items Count</span>
                                <span class="summary-val" id="summaryItemsCount">0</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Total Units Received</span>
                                <span class="summary-val" id="summaryUnitsCount">0</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Total PO Amount</span>
                                <span class="summary-val" id="summaryTotalAmount">₱0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Target Custody</span>
                                <span class="summary-val" id="summaryTargetCustody" style="font-size: 1rem; color: #e4e4e7;">Central Warehouse</span>
                            </div>
                        </div>

                        <button type="button" class="btn-submit-delivery" id="submitDeliveryBtn" onclick="openConfirmDeliveryModal()">
                            <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Confirm & Receive Delivery</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Confirm and Receive Delivery Modal with IAR Option -->
            <div class="modal-overlay" id="confirmDeliveryModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-header">
                        <div>
                            <span class="modal-badge-green">Inspection & Acceptance Verification</span>
                            <h2>Confirm PO Delivery & Receive Stock</h2>
                            <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 4px;">
                                Review the delivered items and custody destination before recording stock into inventory.
                            </p>
                        </div>
                        <button type="button" class="modal-close-btn" onclick="closeConfirmDeliveryModal()" aria-label="Close modal">
                            <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Key Metadata Grid -->
                        <div class="modal-info-grid">
                            <div class="modal-info-card">
                                <span class="info-label">Purchase Order (PO)</span>
                                <strong class="info-val" id="modalPoDisplay">—</strong>
                            </div>
                            <div class="modal-info-card">
                                <span class="info-label">Supplier / Merchant</span>
                                <strong class="info-val" id="modalSupplierDisplay">—</strong>
                            </div>
                            <div class="modal-info-card">
                                <span class="info-label">Target Stock Custody</span>
                                <strong class="info-val" id="modalCustodyDisplay">PGSO Central Warehouse</strong>
                            </div>
                            <div class="modal-info-card">
                                <span class="info-label">Delivery Date</span>
                                <strong class="info-val" id="modalDateDisplay">—</strong>
                            </div>
                        </div>

                        <!-- Delivered Items Table -->
                        <div style="margin-top: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.04em;">Delivered Variant Items</span>
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;" id="modalItemsCount">0 variant(s)</span>
                            </div>
                            <div class="modal-table-container">
                                <table class="modal-items-table">
                                    <thead>
                                        <tr>
                                            <th>Item & Variant Description</th>
                                            <th style="text-align: right;">Quantity</th>
                                            <th style="text-align: right;">Unit Cost</th>
                                            <th style="text-align: right;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalItemsTableBody">
                                        <!-- Dynamically generated rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Grand Totals Callout -->
                        <div class="modal-totals-box">
                            <div>
                                <span class="total-label">Total Units to Receive</span>
                                <span class="total-number" id="modalTotalUnits">0 units</span>
                            </div>
                            <div style="text-align: right;">
                                <span class="total-label">Total Delivery Value</span>
                                <span class="total-amount" id="modalTotalAmount">₱0.00</span>
                            </div>
                        </div>

                        <!-- IAR Form Generation Option -->
                        <div class="modal-iar-card">
                            <label class="iar-checkbox-label">
                                <input type="checkbox" id="modalGenerateIarCheckbox" checked onchange="toggleIarOptions()">
                                <div style="display: flex; flex-direction: column;">
                                    <strong style="font-size: 0.875rem; color: #065f46; display: flex; align-items: center; gap: 6px;">
                                        <span>Generate IAR Form (Inspection & Acceptance Report)</span>
                                        <span style="font-size: 0.6875rem; background: #059669; color: #ffffff; padding: 1px 6px; border-radius: 4px; font-weight: 700;">RECOMMENDED</span>
                                    </strong>
                                    <span style="font-size: 0.7813rem; color: #047857; margin-top: 3px; line-height: 1.4;">
                                        Immediately generates the official COA Appendix 62 A4 report pre-filled with all received items, quantities, PO reference, and inspection signatories.
                                    </span>
                                </div>
                            </label>

                            <div id="modalAutoPrintWrapper" style="margin-top: 10px; margin-left: 28px; padding-top: 8px; border-top: 1px dashed #a7f3d0;">
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 0.7813rem; color: #047857; cursor: pointer;">
                                    <input type="checkbox" id="modalAutoPrintCheckbox">
                                    <span>Automatically trigger print dialog when IAR form opens</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-outline" onclick="closeConfirmDeliveryModal()">Back to Edit</button>
                        <button type="button" class="btn-primary btn-confirm-receive" id="modalSubmitBtn" onclick="submitConfirmedDelivery()">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span id="modalSubmitBtnText">Confirm & Generate IAR</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section 3: Recent Batches History -->
            <div class="history-card">
                <div class="card-header-bar">
                    <div class="card-title-group">
                        <div class="card-icon-badge">
                            <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <h2 class="card-title">Recent PO Deliveries & Active Stock Batches</h2>
                            <p class="card-subtitle">Live audit of recorded batches, price tiers, and remaining balances</p>
                        </div>
                    </div>
                </div>

                <div class="items-table-container">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Batch No.</th>
                                <th>PO Number</th>
                                <th>Supply Item & Variant</th>
                                <th>Unit Price</th>
                                <th>Received / Remaining</th>
                                <th>Custody Destination</th>
                                <th>Delivery Date</th>
                                <th style="text-align: right;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBatches as $batch)
                                <tr>
                                    <td>
                                        <span class="batch-pill">{{ $batch->batch_number }}</span>
                                    </td>
                                    <td>
                                        @if($batch->po_number)
                                            <a href="{{ route('admin.inventory.iar-form', ['po' => $batch->po_number]) }}" target="_blank" class="po-pill po-pill-link" title="Generate & View Official IAR Form for {{ $batch->po_number }}">
                                                <span>{{ $batch->po_number }}</span>
                                                <svg style="width: 10px; height: 10px; color: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                            </a>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.75rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display: flex; flex-direction: column;">
                                            <strong style="color: var(--text-primary);">{{ $batch->inventoryItem?->name ?? 'Standard Item' }}</strong>
                                            <span style="font-size: 0.75rem; color: var(--text-secondary);">
                                                Variant: <strong>{{ $batch->variant?->variant_name ?? 'Default' }}</strong> 
                                                @if($batch->variant?->sku) • SKU: {{ $batch->variant->sku }} @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <strong style="font-family: var(--font-mono); color: var(--text-primary);">₱{{ number_format($batch->unit_cost, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: baseline; gap: 4px;">
                                            <span style="font-weight: 700; font-family: var(--font-mono);">{{ $batch->quantity_remaining }}</span>
                                            <span style="color: var(--text-muted); font-size: 0.75rem;">/ {{ $batch->quantity_received }} {{ $batch->variant?->effective_unit ?? 'pcs' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($batch->office)
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #15803d;">🏛️ {{ $batch->office->office_name }}</span>
                                        @else
                                            <span style="font-size: 0.8125rem; font-weight: 600; color: #1d4ed8;">🏢 Central Warehouse</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-size: 0.8125rem; color: var(--text-secondary);">
                                            {{ $batch->delivery_date ? $batch->delivery_date->format('M d, Y') : $batch->created_at->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        <span class="status-pill-batch {{ $batch->quantity_remaining > 0 ? 'status-active' : 'status-depleted' }}">
                                            {{ $batch->quantity_remaining > 0 ? 'Active Batch' : 'Depleted' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 36px 20px; color: var(--text-muted);">
                                        No deliveries or batches recorded yet. Submit your first Purchase Order delivery above!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </x-sidebar>

    <script>
        const rawVariantsList = @json($variantsList);
        window.variantsMap = {};
        if (Array.isArray(rawVariantsList)) {
            rawVariantsList.forEach(v => {
                if (v && v.id) {
                    window.variantsMap[String(v.id)] = v;
                }
            });
        } else if (typeof rawVariantsList === 'object' && rawVariantsList !== null) {
            Object.values(rawVariantsList).forEach(v => {
                if (v && v.id) {
                    window.variantsMap[String(v.id)] = v;
                }
            });
        }

        let rowCounter = 0;

        function generatePoNumber() {
            const year = new Date().getFullYear();
            const random = Math.floor(1000 + Math.random() * 9000);
            const poInput = document.getElementById('po_number');
            if (poInput) poInput.value = `PO-${year}-${random}`;
        }

        // Live updates for Destination Office
        function updateDestinationPreview() {
            const officeInput = document.getElementById('officeDestinationSelect');
            const calloutBadge = document.getElementById('destBadge');
            const calloutDesc = document.getElementById('destDesc');
            const summaryTarget = document.getElementById('summaryTargetCustody');

            const val = officeInput ? officeInput.value : '';
            const labelEl = document.getElementById('ds_container_officeDestinationSelect_label');
            const officeName = labelEl ? labelEl.textContent.trim().replace('🏛️ ', '') : '';

            if (calloutBadge) {
                if (!val) {
                    calloutBadge.textContent = 'Central Warehouse';
                    calloutBadge.className = 'destination-badge badge-central';
                } else {
                    calloutBadge.textContent = 'Office Custody';
                    calloutBadge.className = 'destination-badge badge-office';
                }
            }

            if (calloutDesc) {
                if (!val) {
                    calloutDesc.textContent = 'Items will be catalogued into PGSO Central Warehouse custody and made available for departmental issuance and transfers.';
                } else {
                    calloutDesc.textContent = `Items will be immediately placed under direct custody of ${officeName || 'the selected office'}.`;
                }
            }

            if (summaryTarget) {
                if (!val) {
                    summaryTarget.textContent = 'Central Warehouse';
                    summaryTarget.style.color = '#bfdbfe';
                } else {
                    summaryTarget.textContent = (officeName ? officeName.split('(')[0] : 'Office Custody').trim();
                    summaryTarget.style.color = '#bbf7d0';
                }
            }
        }

        // Direct addition of selected variant into the PO Receipt table
        function handleVariantDirectAdd(variantId) {
            const key = String(variantId || '').trim();
            if (!key || !window.variantsMap || !window.variantsMap[key]) {
                console.warn('Selected variant ID not found in catalog map:', variantId);
                return;
            }

            const variant = window.variantsMap[key];

            // Check if variant is already present in the table
            const existingRow = document.querySelector(`tr[data-variant-id="${variant.id}"]`);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('.row-qty-input');
                if (qtyInput) {
                    qtyInput.value = parseInt(qtyInput.value || 0, 10) + 1;
                    recalculateTableRow(existingRow);
                    existingRow.style.transition = 'background-color 0.25s ease';
                    existingRow.style.backgroundColor = '#eff6ff';
                    setTimeout(() => { existingRow.style.backgroundColor = ''; }, 600);
                    qtyInput.focus();
                    qtyInput.select();
                }
            } else {
                appendTableRow(variant, 1, parseFloat(variant.unitCost || 0), '');
            }

            // Clear the dropdown search immediately so the user can search and add another variant
            setTimeout(() => {
                const clearBtn = document.getElementById('ds_container_variantSearchPicker_clear');
                if (clearBtn) {
                    clearBtn.click();
                } else {
                    const variantInput = document.getElementById('variantSearchPicker');
                    if (variantInput) variantInput.value = '';
                    const labelEl = document.getElementById('ds_container_variantSearchPicker_label');
                    if (labelEl) labelEl.classList.add('pgso-ds-hidden');
                    const placeholderEl = document.getElementById('ds_container_variantSearchPicker_placeholder');
                    if (placeholderEl) placeholderEl.classList.remove('pgso-ds-hidden');
                }
            }, 50);

            recalculateGrandTotals();
        }

        function appendTableRow(variant, qty, cost, remarks) {
            rowCounter++;
            const tbody = document.getElementById('itemsTableBody');
            const placeholder = document.getElementById('emptyRowPlaceholder');
            if (placeholder) placeholder.style.display = 'none';

            const tr = document.createElement('tr');
            tr.id = `row-${rowCounter}`;
            tr.dataset.rowIndex = rowCounter;
            tr.dataset.variantId = variant.id;

            const isPriceDiff = Math.abs(cost - parseFloat(variant.unitCost || 0)) > 0.009;

            tr.innerHTML = `
                <td>
                    <input type="hidden" name="items[${rowCounter}][variant_id]" value="${variant.id}">
                    <div style="display: flex; flex-direction: column;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="color: var(--text-primary); font-size: 0.9375rem;">${escapeHtml(variant.itemName)}</strong>
                            <span style="font-size: 0.6875rem; background-color: #f4f4f5; border: 1px solid #e4e4e7; padding: 1px 6px; border-radius: 4px; color: #52525b;">${escapeHtml(variant.category || 'Supply')}</span>
                        </div>
                        <span style="font-size: 0.8125rem; color: var(--text-secondary); margin-top: 3px;">
                            ${variant.variantName && variant.variantName !== variant.itemName ? `Variant: <strong style="color: #09090b;">${escapeHtml(variant.variantName)}</strong> • ` : ''}
                            ${variant.sku ? `SKU: <span style="font-family: var(--font-mono);">${escapeHtml(variant.sku)}</span>` : ''}
                            ${variant.barcode && variant.barcode !== variant.sku ? ` • Barcode: <span style="font-family: var(--font-mono);">${escapeHtml(variant.barcode)}</span>` : ''}
                        </span>
                        <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                            Current Catalog: ${variant.currentQty} ${variant.unit} in stock @ ₱${parseFloat(variant.unitCost).toFixed(2)}
                        </span>
                        <div class="price-update-badge" id="row-price-badge-${rowCounter}" style="${isPriceDiff ? 'display: inline-flex;' : ''}">
                            ⚡ Price will update to ₱${cost.toFixed(2)} (prior: ₱${parseFloat(variant.unitCost).toFixed(2)})
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="number" name="items[${rowCounter}][quantity]" class="form-input row-qty-input" min="1" value="${qty}" required style="width: 100px; font-weight: 700; font-family: var(--font-mono);" oninput="recalculateTableRow(this.closest('tr'))">
                        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">${escapeHtml(variant.unit)}</span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="font-weight: 600; color: var(--text-muted);">₱</span>
                        <input type="number" step="0.01" min="0" name="items[${rowCounter}][unit_cost]" class="form-input row-cost-input" value="${cost.toFixed(2)}" data-original-cost="${variant.unitCost}" required style="width: 110px; font-weight: 700; font-family: var(--font-mono);" oninput="recalculateTableRow(this.closest('tr'))">
                    </div>
                </td>
                <td>
                    <input type="text" name="items[${rowCounter}][remarks]" class="form-input" placeholder="e.g. Lot #1, Expiry 2028" value="${escapeHtml(remarks)}" style="height: 38px; font-size: 0.8125rem;">
                </td>
                <td style="text-align: right;">
                    <div class="row-total-val" style="font-family: var(--font-mono); font-weight: 800; font-size: 1rem; color: var(--text-primary);">
                        ₱${(qty * cost).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </div>
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn-del-row" onclick="removeTableRow(${rowCounter})" title="Remove item">
                        <svg style="width: 15px; height: 15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);

            // Subtle highlight on add
            tr.style.transition = 'background-color 0.4s ease';
            tr.style.backgroundColor = '#f0fdf4';
            setTimeout(() => { tr.style.backgroundColor = ''; }, 700);

            // Focus and select the quantity input of the newly added row
            const newQtyInput = tr.querySelector('.row-qty-input');
            if (newQtyInput) {
                newQtyInput.focus();
                newQtyInput.select();
            }

            recalculateGrandTotals();
        }

        function removeTableRow(rowId) {
            const row = document.getElementById(`row-${rowId}`);
            if (row) {
                row.remove();
                const remainingRows = document.querySelectorAll('#itemsTableBody tr:not(#emptyRowPlaceholder)');
                if (remainingRows.length === 0) {
                    const placeholder = document.getElementById('emptyRowPlaceholder');
                    if (placeholder) placeholder.style.display = '';
                }
                recalculateGrandTotals();
            }
        }

        function recalculateTableRow(row) {
            if (!row) return;
            const qtyInput = row.querySelector('.row-qty-input');
            const costInput = row.querySelector('.row-cost-input');
            const totalDisplay = row.querySelector('.row-total-val');
            const rowId = row.dataset.rowIndex;

            const qty = parseInt(qtyInput?.value || 0, 10);
            const cost = parseFloat(costInput?.value || 0);
            const originalCost = parseFloat(costInput?.dataset.originalCost || 0);

            if (totalDisplay) {
                totalDisplay.textContent = `₱${(qty * cost).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }

            const priceBadge = document.getElementById(`row-price-badge-${rowId}`);
            if (priceBadge) {
                if (Math.abs(cost - originalCost) > 0.009) {
                    priceBadge.style.display = 'inline-flex';
                    priceBadge.textContent = `⚡ Price will update to ₱${cost.toFixed(2)} (prior: ₱${originalCost.toFixed(2)})`;
                } else {
                    priceBadge.style.display = 'none';
                }
            }

            recalculateGrandTotals();
        }

        function recalculateGrandTotals() {
            const rows = document.querySelectorAll('#itemsTableBody tr:not(#emptyRowPlaceholder)');
            let totalItems = 0;
            let totalUnits = 0;
            let grandTotal = 0;

            rows.forEach(row => {
                const qtyInput = row.querySelector('.row-qty-input');
                const costInput = row.querySelector('.row-cost-input');

                const qty = parseInt(qtyInput?.value || 0, 10);
                const cost = parseFloat(costInput?.value || 0);

                if (qty > 0) {
                    totalItems++;
                    totalUnits += qty;
                    grandTotal += (qty * cost);
                }
            });

            const summaryItems = document.getElementById('summaryItemsCount');
            const summaryUnits = document.getElementById('summaryUnitsCount');
            const summaryTotal = document.getElementById('summaryTotalAmount');

            if (summaryItems) summaryItems.textContent = totalItems;
            if (summaryUnits) summaryUnits.textContent = totalUnits.toLocaleString();
            if (summaryTotal) summaryTotal.textContent = `₱${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function initPurchaseRequestPage() {
            // Setup listener for Destination Office dropdown
            const destInput = document.getElementById('officeDestinationSelect');
            if (destInput) {
                destInput.addEventListener('change', updateDestinationPreview);
                destInput.addEventListener('dropdown-selected', updateDestinationPreview);
            }
            updateDestinationPreview();

            // Setup listener for Supply Variant Picker dropdown
            const variantInput = document.getElementById('variantSearchPicker');
            const variantContainer = document.getElementById('ds_container_variantSearchPicker');

            let lastProcessed = null;
            let lastProcessedTime = 0;

            function processVariantSelection(val) {
                if (!val) return;
                const now = Date.now();
                if (val === lastProcessed && (now - lastProcessedTime) < 300) {
                    return; // Prevent duplicate trigger from multiple bubble events
                }
                lastProcessed = val;
                lastProcessedTime = now;
                handleVariantDirectAdd(val);
            }

            if (variantInput) {
                variantInput.addEventListener('dropdown-selected', (e) => {
                    const val = e.detail && e.detail.value !== undefined ? e.detail.value : variantInput.value;
                    processVariantSelection(val);
                });
                variantInput.addEventListener('change', () => {
                    processVariantSelection(variantInput.value);
                });
            }

            // Direct click fallback listener on dropdown options
            if (variantContainer) {
                variantContainer.addEventListener('click', (e) => {
                    const opt = e.target.closest('.pgso-ds-option');
                    if (opt && opt.dataset.value) {
                        processVariantSelection(opt.dataset.value);
                    }
                });
            }

            // Modal Confirmation & IAR Generation Logic
            let isConfirmedSubmission = false;

            window.openConfirmDeliveryModal = function() {
                const rows = document.querySelectorAll('#itemsTableBody tr:not(#emptyRowPlaceholder)');
                if (rows.length === 0) {
                    alert('Please add at least one supply variant item to the delivery receipt before confirming.');
                    return;
                }

                const deliveryDateInput = document.getElementById('delivery_date');
                if (deliveryDateInput && !deliveryDateInput.value) {
                    alert('Please select a delivery date.');
                    deliveryDateInput.focus();
                    return;
                }

                // Extract Header Information
                const poInput = document.getElementById('po_number');
                let poVal = poInput && poInput.value.trim() ? poInput.value.trim() : '';
                if (!poVal) {
                    const today = new Date();
                    poVal = `PO-${today.getFullYear()}-${Math.floor(1000 + Math.random() * 9000)} (Auto)`;
                }

                const supplierInput = document.getElementById('supplier');
                const supplierVal = supplierInput && supplierInput.value.trim() ? supplierInput.value.trim() : 'Standard Supplier / Merchant';

                const custodyVal = document.getElementById('summaryTargetCustody') 
                    ? document.getElementById('summaryTargetCustody').textContent 
                    : 'PGSO Central Warehouse';

                const dateVal = deliveryDateInput && deliveryDateInput.value ? deliveryDateInput.value : new Date().toISOString().split('T')[0];

                // Populate Info Cards
                const poDisplay = document.getElementById('modalPoDisplay');
                const supplierDisplay = document.getElementById('modalSupplierDisplay');
                const custodyDisplay = document.getElementById('modalCustodyDisplay');
                const dateDisplay = document.getElementById('modalDateDisplay');

                if (poDisplay) poDisplay.textContent = poVal;
                if (supplierDisplay) supplierDisplay.textContent = supplierVal;
                if (custodyDisplay) custodyDisplay.textContent = custodyVal;
                if (dateDisplay) dateDisplay.textContent = dateVal;

                // Populate Modal Table
                const modalTableBody = document.getElementById('modalItemsTableBody');
                if (modalTableBody) {
                    modalTableBody.innerHTML = '';

                    let totalUnits = 0;
                    let totalValue = 0;
                    let itemCount = 0;

                    rows.forEach(row => {
                        itemCount++;
                        const nameEl = row.querySelector('.item-cell strong');
                        const variantEl = row.querySelector('.variant-badge');
                        const unitEl = row.querySelector('.unit-badge');
                        const qtyInput = row.querySelector('.item-qty-input');
                        const costInput = row.querySelector('.item-cost-input');

                        const itemName = nameEl ? nameEl.textContent.trim() : 'Item';
                        const variantName = variantEl ? variantEl.textContent.trim() : '';
                        const unitName = unitEl ? unitEl.textContent.trim() : 'pcs';
                        const qty = qtyInput ? parseInt(qtyInput.value, 10) || 0 : 0;
                        const cost = costInput ? parseFloat(costInput.value) || 0 : 0;
                        const subtotal = qty * cost;

                        totalUnits += qty;
                        totalValue += subtotal;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <div style="font-weight: 700; color: var(--text-primary); font-size: 0.8125rem;">${itemName}</div>
                                <div style="font-size: 0.7188rem; color: var(--text-muted); margin-top: 2px;">
                                    ${variantName ? `<span style="font-weight: 600; color: #2563eb;">${variantName}</span> • ` : ''}${unitName}
                                </div>
                            </td>
                            <td style="text-align: right; font-weight: 700; font-family: var(--font-mono); font-size: 0.8125rem;">${qty.toLocaleString()} <span style="font-size: 0.6875rem; color: var(--text-muted);">${unitName}</span></td>
                            <td style="text-align: right; font-family: var(--font-mono); color: var(--text-secondary); font-size: 0.8125rem;">₱${cost.toFixed(2)}</td>
                            <td style="text-align: right; font-weight: 700; font-family: var(--font-mono); color: #047857; font-size: 0.8438rem;">₱${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                        `;
                        modalTableBody.appendChild(tr);
                    });

                    const modalItemsCount = document.getElementById('modalItemsCount');
                    const modalTotalUnits = document.getElementById('modalTotalUnits');
                    const modalTotalAmount = document.getElementById('modalTotalAmount');

                    if (modalItemsCount) modalItemsCount.textContent = `${itemCount} variant(s)`;
                    if (modalTotalUnits) modalTotalUnits.textContent = `${totalUnits.toLocaleString()} units`;
                    if (modalTotalAmount) modalTotalAmount.textContent = `₱${totalValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }

                // Reset IAR toggle state
                toggleIarOptions();

                // Open modal
                const modal = document.getElementById('confirmDeliveryModal');
                if (modal) {
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                }
            };

            window.closeConfirmDeliveryModal = function() {
                const modal = document.getElementById('confirmDeliveryModal');
                if (modal) {
                    modal.classList.remove('open');
                    modal.setAttribute('aria-hidden', 'true');
                }
            };

            window.toggleIarOptions = function() {
                const iarCheckbox = document.getElementById('modalGenerateIarCheckbox');
                const autoPrintWrapper = document.getElementById('modalAutoPrintWrapper');
                const submitBtnText = document.getElementById('modalSubmitBtnText');
                const hiddenGenerateIar = document.getElementById('hiddenGenerateIar');

                const isChecked = iarCheckbox && iarCheckbox.checked;
                if (hiddenGenerateIar) {
                    hiddenGenerateIar.value = isChecked ? '1' : '0';
                }

                if (autoPrintWrapper) {
                    autoPrintWrapper.style.display = isChecked ? 'block' : 'none';
                }

                if (submitBtnText) {
                    submitBtnText.textContent = isChecked ? 'Confirm & Generate IAR' : 'Confirm & Receive Only';
                }
            };

            window.submitConfirmedDelivery = function() {
                const iarCheckbox = document.getElementById('modalGenerateIarCheckbox');
                const autoPrintCheckbox = document.getElementById('modalAutoPrintCheckbox');
                const hiddenGenerateIar = document.getElementById('hiddenGenerateIar');
                const hiddenAutoPrint = document.getElementById('hiddenAutoPrint');
                const submitBtn = document.getElementById('modalSubmitBtn');
                const form = document.getElementById('poReceiveForm');

                if (hiddenGenerateIar) {
                    hiddenGenerateIar.value = iarCheckbox && iarCheckbox.checked ? '1' : '0';
                }
                if (hiddenAutoPrint) {
                    hiddenAutoPrint.value = autoPrintCheckbox && autoPrintCheckbox.checked ? '1' : '0';
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.75';
                    submitBtn.innerHTML = `
                        <svg style="width: 16px; height: 16px; animation: spin 0.8s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path></svg>
                        <span>Receiving & Generating...</span>
                    `;
                }

                isConfirmedSubmission = true;
                if (form) {
                    form.submit();
                }
            };

            // Form Submit Interception to ensure confirmation modal is shown
            const form = document.getElementById('poReceiveForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (isConfirmedSubmission) {
                        return true;
                    }
                    e.preventDefault();
                    openConfirmDeliveryModal();
                });
            }

            // Close modal on Escape or backdrop click
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeConfirmDeliveryModal();
                }
            });

            const confirmModal = document.getElementById('confirmDeliveryModal');
            if (confirmModal) {
                confirmModal.addEventListener('click', function(e) {
                    if (e.target === confirmModal) {
                        closeConfirmDeliveryModal();
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPurchaseRequestPage);
        } else {
            initPurchaseRequestPage();
        }
    </script>
</body>
</html>
