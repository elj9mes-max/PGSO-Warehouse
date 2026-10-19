<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Staff Accounts & Credentials</title>

    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        /* Base Reset & Variables matching PGSO Design Language */
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
            --btn-text: #ffffff;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --primary: #18181b;
            --accent-blue: #2563eb;
            --accent-emerald: #059669;
            --accent-amber: #d97706;
            --accent-rose: #e11d48;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .accounts-content-area {
            padding: 32px 36px;
            max-width: 1440px;
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

        .btn-primary,
        .btn-outline {
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

        /* Metrics Bar */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease, transform 0.15s ease;
        }

        .metric-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-1px);
        }

        .metric-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .metric-icon-bubble {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f5;
            color: #52525b;
        }

        .metric-icon-bubble svg {
            width: 16px;
            height: 16px;
        }

        .metric-number {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin: 8px 0 2px 0;
            line-height: 1.1;
        }

        .metric-desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Alerts */
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
            justify-content: space-between;
            gap: 12px;
            animation: fadeIn 0.2s ease;
        }

        .alert-banner.error {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-banner svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 2px;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Filter & Search Bar */
        .filter-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 14px 18px;
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
            height: 40px;
            padding: 0 36px 0 38px;
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

        .search-clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a1a1aa;
            cursor: pointer;
            padding: 4px;
            display: none;
        }

        .search-clear-btn.visible {
            display: block;
        }

        .filters-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* Style adjustments for dropdownSearch inside filter bar & modals */
        .filter-panel .pgso-dropdown-search-wrapper {
            position: relative;
        }

        .filter-panel .pgso-ds-trigger {
            height: 40px;
            min-height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 0.8438rem;
            border: 1px solid var(--border-subtle);
            background-color: #ffffff;
        }

        .form-group .pgso-ds-trigger {
            height: 40px;
            min-height: 40px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 0.8438rem;
            border: 1px solid var(--border-subtle);
        }

        .counter-badge {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 4px 10px;
            background-color: #f4f4f5;
            border-radius: 20px;
            white-space: nowrap;
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

        .accounts-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8438rem;
        }

        .accounts-table th {
            padding: 14px 18px;
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            background-color: #fafafa;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .accounts-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .accounts-table tbody tr {
            transition: background-color 0.12s ease;
        }

        .accounts-table tbody tr:hover {
            background-color: #fbfbfa;
        }

        .accounts-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Staff Profile Cell */
        .staff-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .staff-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #18181b 0%, #3f3f46 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
            letter-spacing: -0.02em;
        }

        .staff-avatar.admin-avatar {
            background: linear-gradient(135deg, #09090b 0%, #1e1b4b 100%);
            border: 1px solid #312e81;
        }

        .staff-avatar.staff-blue {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }

        .staff-avatar.staff-emerald {
            background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
        }

        .staff-avatar.staff-amber {
            background: linear-gradient(135deg, #78350f 0%, #d97706 100%);
        }

        .staff-avatar.staff-rose {
            background: linear-gradient(135deg, #881337 0%, #e11d48 100%);
        }

        .staff-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .staff-name {
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .staff-subline {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .username-badge {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            color: #3f3f46;
            background-color: #f4f4f5;
            padding: 1px 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: all;
        }

        .username-badge:hover {
            background-color: #e4e4e7;
            color: #09090b;
        }

        .username-badge svg {
            width: 11px;
            height: 11px;
            opacity: 0.6;
        }

        .current-user-tag {
            font-size: 0.6875rem;
            font-weight: 700;
            background-color: #eff6ff;
            color: #1d4ed8;
            padding: 2px 7px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Role Badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .role-badge svg {
            width: 13px;
            height: 13px;
        }

        .role-badge.admin {
            background-color: #09090b;
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .role-badge.staff {
            background-color: #f4f4f5;
            color: #27272a;
            border: 1px solid #e4e4e7;
        }

        /* Office Cell */
        .office-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .office-title {
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .office-title svg {
            width: 14px;
            height: 14px;
            color: #71717a;
            flex-shrink: 0;
        }

        .office-code-badge {
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            font-weight: 600;
            color: #1e40af;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 1px 6px;
            border-radius: 4px;
            display: inline-block;
            width: fit-content;
        }

        .unassigned-office-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #b45309;
            background-color: #fef3c7;
            border: 1px solid #fde68a;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .unassigned-office-badge svg {
            width: 12px;
            height: 12px;
        }

        /* Action Buttons */
        .actions-cell {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .btn-action {
            height: 32px;
            padding: 0 10px;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.7813rem;
            font-weight: 600;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            color: var(--text-primary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-action:hover {
            border-color: var(--border-hover);
            background-color: #f4f4f5;
        }

        .btn-action svg {
            width: 13px;
            height: 13px;
            color: #52525b;
        }

        .btn-action.btn-office-action {
            color: #1e40af;
            border-color: #bfdbfe;
            background-color: #f8fafc;
        }

        .btn-action.btn-office-action:hover {
            background-color: #eff6ff;
            border-color: #93c5fd;
        }

        .btn-action.btn-office-action svg {
            color: #2563eb;
        }

        .btn-action.btn-cred-action {
            color: #047857;
            border-color: #a7f3d0;
            background-color: #f0fdf4;
        }

        .btn-action.btn-cred-action:hover {
            background-color: #dcfce7;
            border-color: #6ee7b7;
        }

        .btn-action.btn-cred-action svg {
            color: #059669;
        }

        .btn-action.btn-delete-action {
            padding: 0 8px;
            color: #dc2626;
            border-color: #fecaca;
            background-color: #fffaf0;
        }

        .btn-action.btn-delete-action:hover {
            background-color: #fee2e2;
            border-color: #fca5a5;
        }

        .btn-action.btn-delete-action svg {
            color: #ef4444;
        }

        .btn-action:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
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

        .empty-icon svg {
            width: 24px;
            height: 24px;
        }

        .empty-state h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 0.8125rem;
            color: var(--text-muted);
            max-width: 380px;
            margin: 0 auto;
        }

        /* Modal Overlays & Styling */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.5);
            backdrop-filter: blur(4px);
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
            max-width: 520px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: visible;
            /* Prevents dropdownSearch floating menu from clipping */
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .modal-dialog.modal-sm {
            max-width: 460px;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(6px);
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
            background-color: #ffffff;
            border-radius: 16px 16px 0 0;
        }

        .modal-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f5;
            color: var(--text-primary);
        }

        .modal-header-icon svg {
            width: 18px;
            height: 18px;
        }

        .modal-header-icon.icon-blue {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .modal-header-icon.icon-emerald {
            background-color: #ecfdf5;
            color: #059669;
        }

        .modal-header-icon.icon-rose {
            background-color: #fff1f2;
            color: #e11d48;
        }

        .modal-header-text h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .modal-header-text p {
            font-size: 0.7813rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .modal-close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            transition: all 0.15s ease;
        }

        .modal-close-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .modal-body {
            padding: 22px 24px;
            overflow-y: visible;
            /* Prevents dropdownSearch floating menu from clipping */
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 2fr 1fr 2fr;
            gap: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-label-hint {
            font-size: 0.7188rem;
            font-weight: 500;
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
            width: 100%;
            background-color: #ffffff;
        }

        .modal-input:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        .input-with-prefix {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-prefix {
            position: absolute;
            left: 12px;
            font-family: var(--font-mono);
            font-weight: 600;
            color: #71717a;
            font-size: 0.8438rem;
            pointer-events: none;
        }

        .input-with-prefix .modal-input {
            padding-left: 28px;
        }

        .input-with-action {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-action-btn {
            position: absolute;
            right: 8px;
            background: none;
            border: none;
            color: #71717a;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s ease;
        }

        .input-action-btn:hover {
            color: var(--text-primary);
        }

        .modal-footer {
            padding: 16px 24px;
            background-color: #fafafa;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-radius: 0 0 16px 16px;
        }

        /* User Card Callout inside modal */
        .modal-user-preview {
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 6px;
        }

        .preview-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background-color: #09090b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8125rem;
        }

        .preview-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .preview-name {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.8438rem;
        }

        .preview-subline {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Info box */
        .info-callout {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.75rem;
            color: #475569;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.4;
        }

        .info-callout svg {
            width: 14px;
            height: 14px;
            color: #0284c7;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-callout.warning {
            background-color: #fffbeb;
            border-color: #fef3c7;
            color: #92400e;
        }

        .info-callout.warning svg {
            color: #d97706;
        }

        /* Password Generator Toolbar */
        .pwd-generator-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f4f4f5;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.75rem;
        }

        .btn-gen-pwd {
            background: none;
            border: none;
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 2px 4px;
        }

        .btn-gen-pwd:hover {
            text-decoration: underline;
        }

        /* Toast notification */
        .pgso-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #09090b;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateY(30px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pgso-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        @media (max-width: 768px) {
            .accounts-content-area {
                padding: 20px 16px;
            }

            .form-row,
            .form-row-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        // Normalized options for DropdownSearch components
        $officeFilterOptions = collect([
            ['id' => '', 'name' => "All Offices ({$totalAccounts})", 'code' => 'ALL'],
            ['id' => '__unassigned__', 'name' => "No Office Assigned ({$unassignedCount})", 'code' => 'FLOAT'],
        ])->concat($offices->map(fn($o) => [
                        'id' => (string) $o->id,
                        'name' => $o->office_name,
                        'code' => $o->office_code,
                    ]))->toArray();

        $officeSelectOptions = collect([
            ['id' => '', 'name' => '-- No Office (Floating Staff / Pool) --', 'code' => 'FLOAT'],
        ])->concat($offices->map(fn($o) => [
                        'id' => (string) $o->id,
                        'name' => $o->office_name,
                        'code' => $o->office_code,
                    ]))->toArray();

        $roleOptions = [
            ['id' => 'Staff', 'name' => 'Warehouse Staff', 'code' => 'STAFF'],
            ['id' => 'Admin', 'name' => 'Administrator', 'code' => 'ADMIN'],
        ];

        $roleFilterOptions = [
            ['id' => '', 'name' => 'All Account Roles', 'code' => 'ALL'],
            ['id' => 'Staff', 'name' => 'Warehouse Staff', 'code' => 'STAFF'],
            ['id' => 'Admin', 'name' => 'Administrator', 'code' => 'ADMIN'],
        ];
    @endphp

    <x-sidebar>
        <div class="accounts-content-area">

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Accounts & Staff Directory</h1>
                    <p>PGSO Property & Supply Master List • User Credentials, Office Allocations & Access Roles</p>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn-outline" onclick="window.print()">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        <span>Print Roster</span>
                    </button>

                    <button type="button" class="btn-primary" id="openAddAccountModalBtn">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="22" y1="11" x2="16" y2="11" />
                        </svg>
                        <span>Add New Staff Account</span>
                    </button>
                </div>
            </div>

            <!-- Flash Alert Messages -->
            @if(session('status'))
                <div class="alert-banner" role="alert" id="statusBanner">
                    <div class="alert-content">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="document.getElementById('statusBanner').remove()">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-banner error" role="alert" id="errorBanner">
                    <div class="alert-content">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="document.getElementById('errorBanner').remove()">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-banner error" role="alert">
                    <div class="alert-content">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            <strong>Please review form errors:</strong>
                            <ul style="margin-left: 18px; margin-top: 4px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Metrics Overview Bar -->
            <div class="metrics-grid">
                <!-- Total Accounts -->
                <div class="metric-card">
                    <div class="metric-top">
                        <span class="metric-label">Total Accounts</span>
                        <div class="metric-icon-bubble">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                    </div>
                    <div class="metric-number">{{ $totalAccounts }}</div>
                    <div class="metric-desc">
                        <span>{{ $adminCount }} Admin{{ $adminCount === 1 ? '' : 's' }}</span> • <span>{{ $staffCount }}
                            Staff Member{{ $staffCount === 1 ? '' : 's' }}</span>
                    </div>
                </div>

                <!-- Active Staff Accounts -->
                <div class="metric-card">
                    <div class="metric-top">
                        <span class="metric-label">Staff Personnel</span>
                        <div class="metric-icon-bubble" style="background-color: #ecfdf5; color: #059669;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <polyline points="16 11 18 13 22 9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="metric-number">{{ $staffCount }}</div>
                    <div class="metric-desc">
                        <span>Warehouse operators & field custodians</span>
                    </div>
                </div>

                <!-- Office Assigned Staff -->
                <div class="metric-card">
                    <div class="metric-top">
                        <span class="metric-label">Office Assigned</span>
                        <div class="metric-icon-bubble" style="background-color: #eff6ff; color: #2563eb;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18" />
                                <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                <path d="M9 9h1" />
                                <path d="M9 13h1" />
                                <path d="M9 17h1" />
                                <path d="M14 9h1" />
                                <path d="M14 13h1" />
                                <path d="M14 17h1" />
                            </svg>
                        </div>
                    </div>
                    <div class="metric-number">{{ $assignedCount }}</div>
                    <div class="metric-desc">
                        <span>Stationed across provincial offices</span>
                    </div>
                </div>

                <!-- Floating / Unassigned Staff -->
                <div class="metric-card">
                    <div class="metric-top">
                        <span class="metric-label">Floating / Unassigned</span>
                        <div class="metric-icon-bubble"
                            style="background-color: {{ $unassignedCount > 0 ? '#fef3c7' : '#f4f4f5' }}; color: {{ $unassignedCount > 0 ? '#d97706' : '#71717a' }};">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="metric-number">{{ $unassignedCount }}</div>
                    <div class="metric-desc">
                        <span>{{ $unassignedCount > 0 ? 'General warehouse pool / unstationed' : 'All personnel assigned' }}</span>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Toolbar with DropdownSearch Components -->
            <div class="filter-panel">
                <div class="search-box">
                    <span class="search-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" id="accountsSearchInput" class="search-input"
                        placeholder="Search by staff name, @username, or office name/code..." autocomplete="off">
                    <button type="button" class="search-clear-btn" id="searchClearBtn" title="Clear search">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="filters-group">
                    <!-- DropdownSearch: Office Filter -->
                    <div style="min-width: 250px;">
                        <x-dropdown-search name="office_filter" id="officeFilterSelect" :options="$officeFilterOptions"
                            valueKey="id" labelKey="name" subtextKey="code" placeholder="All Offices"
                            searchPlaceholder="Search office..." :allowClear="true" />
                    </div>

                    <!-- DropdownSearch: Role Filter -->
                    <div style="min-width: 190px;">
                        <x-dropdown-search name="role_filter" id="roleFilterSelect" :options="$roleFilterOptions"
                            valueKey="id" labelKey="name" subtextKey="code" placeholder="All Account Roles"
                            searchPlaceholder="Search role..." :allowClear="true" />
                    </div>

                    <!-- Reset Filters Button -->
                    <button type="button" class="btn-outline" id="resetFiltersBtn"
                        style="height: 40px; padding: 0 12px; display: none;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                            <path d="M3 3v5h5" />
                        </svg>
                        <span>Reset</span>
                    </button>

                    <div class="counter-badge" id="accountsCounterBadge">
                        Showing {{ $users->count() }} of {{ $totalAccounts }}
                    </div>
                </div>
            </div>

            <!-- Accounts Roster Table -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="accounts-table" id="accountsTable">
                        <thead>
                            <tr>
                                <th>Staff Member</th>
                                <th>Account Role</th>
                                <th>Assigned Office</th>
                                <th>Registered</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $initials = strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1));
                                    $isCurrent = auth()->check() && auth()->id() === $user->id;
                                    $paletteIndex = ($user->id % 4);
                                    $avatarClass = $user->isAdmin()
                                        ? 'admin-avatar'
                                        : ($paletteIndex === 0 ? 'staff-blue' : ($paletteIndex === 1 ? 'staff-emerald' : ($paletteIndex === 2 ? 'staff-amber' : 'staff-rose')));
                                @endphp
                                <tr class="account-row" data-user-id="{{ $user->id }}"
                                    data-name="{{ strtolower($user->full_name) }}"
                                    data-first-name="{{ e($user->first_name) }}"
                                    data-middle-initial="{{ e($user->middle_initial) }}"
                                    data-last-name="{{ e($user->last_name) }}"
                                    data-username="{{ strtolower($user->username) }}"
                                    data-raw-username="{{ e($user->username) }}"
                                    data-email="{{ strtolower($user->email ?? '') }}"
                                    data-role="{{ $user->account_level?->value ?? 'Staff' }}"
                                    data-office-id="{{ $user->office_id ?? '__unassigned__' }}"
                                    data-office-name="{{ strtolower($user->office?->office_name ?? '') }}"
                                    data-office-code="{{ strtolower($user->office?->office_code ?? '') }}">
                                    <!-- Staff Member Cell -->
                                    <td>
                                        <div class="staff-cell">
                                            <div class="staff-avatar {{ $avatarClass }}">
                                                {{ $initials ?: 'U' }}
                                            </div>
                                            <div class="staff-details">
                                                <div class="staff-name">
                                                    <span>{{ $user->full_name }}</span>
                                                    @if($isCurrent)
                                                        <span class="current-user-tag">You</span>
                                                    @endif
                                                </div>
                                                <div class="staff-subline">
                                                    <span class="username-badge"
                                                        title="Click to copy username: @{{ $user->username }}"
                                                        onclick="copyToClipboard('{{ $user->username }}', 'Username @{{ $user->username }} copied!')">
                                                        <span>@<span>{{ $user->username }}</span></span>
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                                            <path
                                                                d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                                        </svg>
                                                    </span>
                                                    @if($user->email)
                                                        <span>• {{ $user->email }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Role Cell -->
                                    <td>
                                        @if($user->isAdmin())
                                            <span class="role-badge admin">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                </svg>
                                                <span>Administrator</span>
                                            </span>
                                        @else
                                            <span class="role-badge staff">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <span>Warehouse Staff</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Office Cell -->
                                    <td>
                                        <div class="office-cell">
                                            @if($user->office)
                                                <div class="office-title">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 21h18" />
                                                        <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                                        <path d="M9 9h1" />
                                                        <path d="M9 13h1" />
                                                        <path d="M9 17h1" />
                                                        <path d="M14 9h1" />
                                                        <path d="M14 13h1" />
                                                        <path d="M14 17h1" />
                                                    </svg>
                                                    <span>{{ $user->office->office_name }}</span>
                                                </div>
                                                <div>
                                                    <span class="office-code-badge">{{ $user->office->office_code }}</span>
                                                </div>
                                            @else
                                                <div class="unassigned-office-badge">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="12" y1="8" x2="12" y2="12" />
                                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                                    </svg>
                                                    <span>Unassigned / Floater</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Registered Date -->
                                    <td>
                                        <div style="color: var(--text-primary); font-weight: 500;">
                                            {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Pre-existing' }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                                            {{ $user->created_at ? $user->created_at->diffForHumans() : 'System default' }}
                                        </div>
                                    </td>

                                    <!-- Action Buttons -->
                                    <td>
                                        <div class="actions-cell">
                                            <!-- Change Office Button -->
                                            <button type="button" class="btn-action btn-office-action open-office-modal-btn"
                                                title="Change assigned office" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ e($user->full_name) }}"
                                                data-username="{{ e($user->username) }}"
                                                data-office-id="{{ $user->office_id ?? '' }}"
                                                data-office-name="{{ e($user->office?->office_name ?? 'Unassigned') }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 21h18" />
                                                    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                                                    <path d="M9 9h1" />
                                                    <path d="M9 13h1" />
                                                </svg>
                                                <span>Office</span>
                                            </button>

                                            <!-- Reset Credentials (Username & Password) Button -->
                                            <button type="button" class="btn-action btn-cred-action open-cred-modal-btn"
                                                title="Reset username & password" data-user-id="{{ $user->id }}"
                                                data-user-name="{{ e($user->full_name) }}"
                                                data-username="{{ e($user->username) }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                </svg>
                                                <span>Credentials</span>
                                            </button>

                                            <!-- Edit Profile Details Button -->
                                            <button type="button" class="btn-action open-edit-modal-btn"
                                                title="Edit user details" data-user-id="{{ $user->id }}"
                                                data-first-name="{{ e($user->first_name) }}"
                                                data-middle-initial="{{ e($user->middle_initial) }}"
                                                data-last-name="{{ e($user->last_name) }}"
                                                data-username="{{ e($user->username) }}"
                                                data-role="{{ $user->account_level?->value ?? 'Staff' }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            <!-- Delete Button -->
                                            @if(!$isCurrent)
                                                <button type="button" class="btn-action btn-delete-action open-delete-modal-btn"
                                                    title="Delete account" data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ e($user->full_name) }}"
                                                    data-username="{{ e($user->username) }}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18" />
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button" class="btn-action btn-delete-action" disabled
                                                    title="You cannot delete your own logged-in account">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                    <circle cx="9" cy="7" r="4" />
                                                </svg>
                                            </div>
                                            <h3>No Staff Accounts Found</h3>
                                            <p>There are no staff accounts configured in the warehouse roster yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Empty Filter Search Result Row -->
                            <tr id="emptySearchRow" style="display: none;">
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="8" />
                                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                            </svg>
                                        </div>
                                        <h3>No Matching Personnel</h3>
                                        <p>No user accounts match your current search terms or filter criteria. Try
                                            adjusting or resetting your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </x-sidebar>

    <!-- ============================================================== -->
    <!-- MODAL 1: ADD NEW STAFF ACCOUNT -->
    <!-- ============================================================== -->
    <div class="modal-overlay" id="addAccountModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-header-info">
                    <div class="modal-header-icon icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <line x1="19" y1="8" x2="19" y2="14" />
                            <line x1="22" y1="11" x2="16" y2="11" />
                        </svg>
                    </div>
                    <div class="modal-header-text">
                        <h2>Add Staff Account</h2>
                        <p>Create credentials and assign office responsibilities</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-close-modal="addAccountModal">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.accounts.store') }}" method="POST" id="addAccountForm">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <!-- Name row (First, MI, Last) -->
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="modal-label" for="addFirstName">First Name *</label>
                                <input type="text" name="first_name" id="addFirstName" class="modal-input"
                                    placeholder="e.g. Maria" required value="{{ old('first_name') }}">
                            </div>
                            <div class="form-group">
                                <label class="modal-label" for="addMiddleInitial">M.I.</label>
                                <input type="text" name="middle_initial" id="addMiddleInitial" class="modal-input"
                                    placeholder="C." maxlength="5" value="{{ old('middle_initial') }}">
                            </div>
                            <div class="form-group">
                                <label class="modal-label" for="addLastName">Last Name *</label>
                                <input type="text" name="last_name" id="addLastName" class="modal-input"
                                    placeholder="e.g. Santos" required value="{{ old('last_name') }}">
                            </div>
                        </div>

                        <!-- Username with prefix -->
                        <div class="form-group">
                            <label class="modal-label" for="addUsername">
                                <span>Username *</span>
                                <span class="modal-label-hint">Must be unique for login</span>
                            </label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">@</span>
                                <input type="text" name="username" id="addUsername" class="modal-input"
                                    placeholder="msantos" required value="{{ old('username') }}">
                            </div>
                        </div>

                        <!-- Email (Optional) -->
                        <div class="form-group">
                            <label class="modal-label" for="addEmail">
                                <span>Email Address</span>
                                <span class="modal-label-hint">Optional</span>
                            </label>
                            <input type="email" name="email" id="addEmail" class="modal-input"
                                placeholder="msantos@pgso.gov.ph" value="{{ old('email') }}">
                        </div>

                        <!-- Role & Office Assignment using DropdownSearch Components -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="modal-label" for="addAccountLevel">Account Role *</label>
                                <x-dropdown-search name="account_level" id="addAccountLevel" :options="$roleOptions"
                                    valueKey="id" labelKey="name" subtextKey="code" selected="Staff"
                                    placeholder="Select Account Role" searchPlaceholder="Search role..."
                                    :allowClear="false" required />
                            </div>

                            <div class="form-group">
                                <label class="modal-label" for="addOfficeId">Assign Office</label>
                                <x-dropdown-search name="office_id" id="addOfficeId" :options="$officeSelectOptions"
                                    valueKey="id" labelKey="name" subtextKey="code"
                                    placeholder="-- No Office (Floating Staff) --"
                                    searchPlaceholder="Search office name or code..." :allowClear="true" />
                            </div>
                        </div>

                        <!-- Password Generator Toolbar -->
                        <div class="pwd-generator-bar">
                            <span style="color: var(--text-muted);">Set initial secure password:</span>
                            <button type="button" class="btn-gen-pwd"
                                onclick="generateAndFillPassword('addPassword', 'addPasswordConfirmation')">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 2l-2 2m-2-2l2 2M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                                    <path d="M21 3v5h-5" />
                                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16" />
                                    <path d="M16 16h5v5" />
                                </svg>
                                <span>Generate Strong Password</span>
                            </button>
                        </div>

                        <!-- Password & Confirmation -->
                        <div class="form-row">
                            <div class="form-group">
                                <label class="modal-label" for="addPassword">Password *</label>
                                <div class="input-with-action">
                                    <input type="password" name="password" id="addPassword" class="modal-input"
                                        placeholder="Min. 8 characters" required minlength="8">
                                    <button type="button" class="input-action-btn"
                                        onclick="togglePasswordVisibility('addPassword', this)">Show</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="modal-label" for="addPasswordConfirmation">Confirm Password *</label>
                                <div class="input-with-action">
                                    <input type="password" name="password_confirmation" id="addPasswordConfirmation"
                                        class="modal-input" placeholder="Repeat password" required minlength="8">
                                    <button type="button" class="input-action-btn"
                                        onclick="togglePasswordVisibility('addPasswordConfirmation', this)">Show</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-close-modal="addAccountModal">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: RESET CREDENTIALS (USERNAME & PASSWORD) -->
    <!-- ============================================================== -->
    <div class="modal-overlay" id="credentialsModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-header">
                <div class="modal-header-info">
                    <div class="modal-header-icon icon-emerald">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <div class="modal-header-text">
                        <h2>Reset Credentials</h2>
                        <p>Change username and/or generate a new password</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-close-modal="credentialsModal">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="" method="POST" id="credentialsForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <!-- User Card Preview -->
                        <div class="modal-user-preview">
                            <div class="preview-avatar" id="credUserAvatar">JD</div>
                            <div class="preview-text">
                                <span class="preview-name" id="credUserName">Staff Member</span>
                                <span class="preview-subline">Current Username: <strong
                                        style="color: var(--text-primary); font-family: var(--font-mono);"
                                        id="credUserOldUsername">@staff</strong></span>
                            </div>
                        </div>

                        <!-- Username Input -->
                        <div class="form-group">
                            <label class="modal-label" for="credUsername">
                                <span>Username</span>
                                <span class="modal-label-hint">Login handle</span>
                            </label>
                            <div class="input-with-prefix">
                                <span class="input-prefix">@</span>
                                <input type="text" name="username" id="credUsername" class="modal-input" required>
                            </div>
                        </div>

                        <div class="info-callout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <span>Leave password fields blank if you only want to change the username.</span>
                        </div>

                        <!-- Password Generator Toolbar -->
                        <div class="pwd-generator-bar">
                            <span style="color: var(--text-muted);">Reset password:</span>
                            <button type="button" class="btn-gen-pwd"
                                onclick="generateAndFillPassword('credPassword', 'credPasswordConfirmation')">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M21 2l-2 2m-2-2l2 2M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                                    <path d="M21 3v5h-5" />
                                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16" />
                                    <path d="M16 16h5v5" />
                                </svg>
                                <span>Generate Strong Password</span>
                            </button>
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label class="modal-label" for="credPassword">New Password</label>
                            <div class="input-with-action">
                                <input type="password" name="password" id="credPassword" class="modal-input"
                                    placeholder="Leave empty to keep unchanged" minlength="8">
                                <button type="button" class="input-action-btn"
                                    onclick="togglePasswordVisibility('credPassword', this)">Show</button>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="form-group">
                            <label class="modal-label" for="credPasswordConfirmation">Confirm New Password</label>
                            <div class="input-with-action">
                                <input type="password" name="password_confirmation" id="credPasswordConfirmation"
                                    class="modal-input" placeholder="Repeat new password" minlength="8">
                                <button type="button" class="input-action-btn"
                                    onclick="togglePasswordVisibility('credPasswordConfirmation', this)">Show</button>
                            </div>
                        </div>

                        <!-- Generated password helper display -->
                        <div id="genPwdCopyNotice"
                            style="display: none; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 12px; font-size: 0.75rem; color: #065f46; justify-content: space-between; align-items: center;">
                            <span>Password generated!</span>
                            <button type="button" class="btn-action"
                                style="height: 24px; padding: 0 8px; font-size: 0.6875rem;"
                                onclick="copyGeneratedPassword()">Copy to Clipboard</button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-close-modal="credentialsModal">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span>Update Credentials</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: CHANGE OFFICE ASSIGNMENT -->
    <!-- ============================================================== -->
    <div class="modal-overlay" id="officeModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-header">
                <div class="modal-header-info">
                    <div class="modal-header-icon icon-blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18" />
                            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
                            <path d="M9 9h1" />
                            <path d="M9 13h1" />
                            <path d="M9 17h1" />
                        </svg>
                    </div>
                    <div class="modal-header-text">
                        <h2>Change Office</h2>
                        <p>Reassign staff personnel to a designated department</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-close-modal="officeModal">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="" method="POST" id="officeForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <!-- User Card Preview -->
                        <div class="modal-user-preview">
                            <div class="preview-avatar" id="officeUserAvatar">JD</div>
                            <div class="preview-text">
                                <span class="preview-name" id="officeUserName">Staff Member</span>
                                <span class="preview-subline">Current Office: <strong
                                        style="color: var(--text-primary);" id="officeUserCurrentOffice">Provincial
                                        Governor's Office</strong></span>
                            </div>
                        </div>

                        <!-- DropdownSearch: Select Designated Office -->
                        <div class="form-group">
                            <label class="modal-label" for="changeOfficeSelect">Select Designated Office</label>
                            <x-dropdown-search name="office_id" id="changeOfficeSelect" :options="$officeSelectOptions"
                                valueKey="id" labelKey="name" subtextKey="code"
                                placeholder="-- No Office (Floating Staff / Pool) --"
                                searchPlaceholder="Type office name or code to search..." :allowClear="true" />
                        </div>

                        <div class="info-callout">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <span>Changing an office updates where this personnel is logged for supply issuances, stock
                                distribution, and requisition receipts.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-close-modal="officeModal">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span>Save Office Assignment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 4: EDIT PROFILE DETAILS -->
    <!-- ============================================================== -->
    <div class="modal-overlay" id="editProfileModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-header">
                <div class="modal-header-info">
                    <div class="modal-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                        </svg>
                    </div>
                    <div class="modal-header-text">
                        <h2>Edit Staff Details</h2>
                        <p>Update personnel name & role level</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-close-modal="editProfileModal">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="" method="POST" id="editProfileForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="modal-label" for="editFirstName">First Name *</label>
                                <input type="text" name="first_name" id="editFirstName" class="modal-input" required>
                            </div>
                            <div class="form-group">
                                <label class="modal-label" for="editMiddleInitial">M.I.</label>
                                <input type="text" name="middle_initial" id="editMiddleInitial" class="modal-input"
                                    maxlength="5">
                            </div>
                            <div class="form-group">
                                <label class="modal-label" for="editLastName">Last Name *</label>
                                <input type="text" name="last_name" id="editLastName" class="modal-input" required>
                            </div>
                        </div>

                        <!-- DropdownSearch: Edit Role -->
                        <div class="form-group">
                            <label class="modal-label" for="editAccountLevel">Account Role *</label>
                            <x-dropdown-search name="account_level" id="editAccountLevel" :options="$roleOptions"
                                valueKey="id" labelKey="name" subtextKey="code" selected="Staff"
                                placeholder="Select Account Role" searchPlaceholder="Search role..." :allowClear="false"
                                required />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-close-modal="editProfileModal">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 5: DELETE ACCOUNT CONFIRMATION -->
    <!-- ============================================================== -->
    <div class="modal-overlay" id="deleteAccountModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-header">
                <div class="modal-header-info">
                    <div class="modal-header-icon icon-rose">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                    </div>
                    <div class="modal-header-text">
                        <h2>Delete Account</h2>
                        <p>Permanently remove user credentials</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-close-modal="deleteAccountModal">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form action="" method="POST" id="deleteAccountForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="form-grid">
                        <p style="font-size: 0.875rem; color: var(--text-primary); line-height: 1.5;">
                            Are you sure you want to delete the account for <strong id="deleteUserName">Staff
                                Member</strong> (<span style="font-family: var(--font-mono);"
                                id="deleteUserUsername">@user</span>)?
                        </p>

                        <div class="info-callout warning">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                <line x1="12" y1="9" x2="12" y2="13" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                            <span>This action cannot be undone. Historical audit and transaction logs associated with
                                this user will remain preserved.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" data-close-modal="deleteAccountModal">Cancel</button>
                    <button type="submit" class="btn-primary" style="background-color: #dc2626; border-color: #dc2626;">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                        </svg>
                        <span>Delete Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast Element -->
    <div class="pgso-toast" id="pgsoToast">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span id="toastMessage">Copied to clipboard</span>
    </div>

    <!-- Interactive Client Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Base API url builder
            const baseAccountsUrl = "{{ url('/admin/accounts') }}";

            // Modal Trigger Elements
            const addAccountModal = document.getElementById('addAccountModal');
            const credentialsModal = document.getElementById('credentialsModal');
            const officeModal = document.getElementById('officeModal');
            const editProfileModal = document.getElementById('editProfileModal');
            const deleteAccountModal = document.getElementById('deleteAccountModal');

            // Open Add Modal
            const openAddBtn = document.getElementById('openAddAccountModalBtn');
            if (openAddBtn) {
                openAddBtn.addEventListener('click', () => openModal(addAccountModal));
            }

            // Close Modal Buttons
            document.querySelectorAll('[data-close-modal]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-close-modal');
                    const targetModal = document.getElementById(targetId);
                    if (targetModal) closeModal(targetModal);
                });
            });

            // Close on overlay backdrop click
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) {
                        closeModal(overlay);
                    }
                });
            });

            // ESC Key closes top modal
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay.active').forEach(modal => closeModal(modal));
                }
            });

            function openModal(modal) {
                if (!modal) return;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }

            // ==============================================================
            // Setup Credentials Modal (Reset Username & Password)
            // ==============================================================
            document.querySelectorAll('.open-cred-modal-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const userId = btn.getAttribute('data-user-id');
                    const userName = btn.getAttribute('data-user-name');
                    const username = btn.getAttribute('data-username');

                    document.getElementById('credentialsForm').action = `${baseAccountsUrl}/${userId}/credentials`;
                    document.getElementById('credUserName').textContent = userName;
                    document.getElementById('credUserOldUsername').textContent = `@${username}`;
                    document.getElementById('credUsername').value = username;
                    document.getElementById('credPassword').value = '';
                    document.getElementById('credPasswordConfirmation').value = '';
                    document.getElementById('genPwdCopyNotice').style.display = 'none';

                    // Avatar initials
                    const initials = userName.split(' ').map(n => n[0]).filter(Boolean).slice(0, 2).join('').toUpperCase() || 'U';
                    document.getElementById('credUserAvatar').textContent = initials;

                    openModal(credentialsModal);
                });
            });

            // ==============================================================
            // Setup Office Modal (Change Assigned Office via DropdownSearch)
            // ==============================================================
            document.querySelectorAll('.open-office-modal-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const userId = btn.getAttribute('data-user-id');
                    const userName = btn.getAttribute('data-user-name');
                    const officeId = btn.getAttribute('data-office-id');
                    const officeName = btn.getAttribute('data-office-name');

                    document.getElementById('officeForm').action = `${baseAccountsUrl}/${userId}/office`;
                    document.getElementById('officeUserName').textContent = userName;
                    document.getElementById('officeUserCurrentOffice').textContent = officeName || 'No Office (Floating Staff)';

                    // Avatar initials
                    const initials = userName.split(' ').map(n => n[0]).filter(Boolean).slice(0, 2).join('').toUpperCase() || 'U';
                    document.getElementById('officeUserAvatar').textContent = initials;

                    openModal(officeModal);

                    // Set value in DropdownSearch component
                    setDropdownSearchValue('changeOfficeSelect', officeId || '');
                });
            });

            // ==============================================================
            // Setup Edit Profile Modal (via DropdownSearch)
            // ==============================================================
            document.querySelectorAll('.open-edit-modal-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const userId = btn.getAttribute('data-user-id');
                    const firstName = btn.getAttribute('data-first-name');
                    const middleInitial = btn.getAttribute('data-middle-initial');
                    const lastName = btn.getAttribute('data-last-name');
                    const role = btn.getAttribute('data-role');

                    document.getElementById('editProfileForm').action = `${baseAccountsUrl}/${userId}`;
                    document.getElementById('editFirstName').value = firstName;
                    document.getElementById('editMiddleInitial').value = middleInitial;
                    document.getElementById('editLastName').value = lastName;

                    // Set role in DropdownSearch component
                    setDropdownSearchValue('editAccountLevel', role || 'Staff');

                    openModal(editProfileModal);
                });
            });

            // ==============================================================
            // Setup Delete Modal
            // ==============================================================
            document.querySelectorAll('.open-delete-modal-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const userId = btn.getAttribute('data-user-id');
                    const userName = btn.getAttribute('data-user-name');
                    const username = btn.getAttribute('data-username');

                    document.getElementById('deleteAccountForm').action = `${baseAccountsUrl}/${userId}`;
                    document.getElementById('deleteUserName').textContent = userName;
                    document.getElementById('deleteUserUsername').textContent = `@${username}`;

                    openModal(deleteAccountModal);
                });
            });

            // ==============================================================
            // Live Search & Multi-Filter Logic with DropdownSearch Components
            // ==============================================================
            const searchInput = document.getElementById('accountsSearchInput');
            const searchClearBtn = document.getElementById('searchClearBtn');
            const officeFilterInput = document.getElementById('officeFilterSelect');
            const roleFilterInput = document.getElementById('roleFilterSelect');
            const resetFiltersBtn = document.getElementById('resetFiltersBtn');
            const counterBadge = document.getElementById('accountsCounterBadge');
            const rows = document.querySelectorAll('.account-row');
            const emptySearchRow = document.getElementById('emptySearchRow');
            const totalCount = rows.length;

            function filterRows() {
                const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
                const selectedOffice = officeFilterInput ? officeFilterInput.value : '';
                const selectedRole = roleFilterInput ? roleFilterInput.value : '';

                if (searchClearBtn) {
                    searchClearBtn.classList.toggle('visible', query.length > 0);
                }
                const hasActiveFilters = query.length > 0 || selectedOffice !== '' || selectedRole !== '';
                if (resetFiltersBtn) {
                    resetFiltersBtn.style.display = hasActiveFilters ? 'inline-flex' : 'none';
                }

                let visibleCount = 0;

                rows.forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const username = row.getAttribute('data-username') || '';
                    const email = row.getAttribute('data-email') || '';
                    const officeName = row.getAttribute('data-office-name') || '';
                    const officeCode = row.getAttribute('data-office-code') || '';
                    const rowOfficeId = row.getAttribute('data-office-id') || '';
                    const rowRole = row.getAttribute('data-role') || '';

                    // Check search query (matches name, username, email, office)
                    const matchesSearch = query === ''
                        || name.includes(query)
                        || username.includes(query)
                        || email.includes(query)
                        || officeName.includes(query)
                        || officeCode.includes(query);

                    // Check office filter
                    const matchesOffice = selectedOffice === '' || rowOfficeId === selectedOffice;

                    // Check role filter
                    const matchesRole = selectedRole === '' || rowRole === selectedRole;

                    if (matchesSearch && matchesOffice && matchesRole) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptySearchRow) {
                    emptySearchRow.style.display = (visibleCount === 0 && totalCount > 0) ? '' : 'none';
                }

                if (counterBadge) {
                    counterBadge.textContent = `Showing ${visibleCount} of ${totalCount}`;
                }
            }

            if (searchInput) searchInput.addEventListener('input', filterRows);
            if (officeFilterInput) officeFilterInput.addEventListener('change', filterRows);
            if (roleFilterInput) roleFilterInput.addEventListener('change', filterRows);

            if (searchClearBtn) {
                searchClearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    filterRows();
                    searchInput.focus();
                });
            }

            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    setDropdownSearchValue('officeFilterSelect', '');
                    setDropdownSearchValue('roleFilterSelect', '');
                    filterRows();
                });
            }
        });

        // ==============================================================
        // DropdownSearch Helper: Programmatically select or clear an option
        // ==============================================================
        function setDropdownSearchValue(inputId, value) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const wrapper = input.closest('.pgso-dropdown-search-wrapper');
            if (!wrapper) return;

            if (!wrapper.dataset.initialized && typeof window.setupPgsoDropdownSearch === 'function') {
                window.setupPgsoDropdownSearch(wrapper.id);
            }

            const strVal = String(value ?? '');

            if (strVal === '') {
                const clearBtn = wrapper.querySelector('.pgso-ds-clear-btn');
                if (clearBtn && !clearBtn.classList.contains('pgso-ds-hidden')) {
                    clearBtn.click();
                    return;
                }
                // Force clear state if no clear button is visible
                input.value = '';
                const labelEl = wrapper.querySelector('.pgso-ds-selected-label');
                const placeholderEl = wrapper.querySelector('.pgso-ds-placeholder');
                const badgeEl = wrapper.querySelector('.pgso-ds-selected-badge');
                if (labelEl) { labelEl.textContent = ''; labelEl.classList.add('pgso-ds-hidden'); }
                if (placeholderEl) { placeholderEl.classList.remove('pgso-ds-hidden'); }
                if (badgeEl) { badgeEl.classList.add('pgso-ds-hidden'); }
                wrapper.querySelectorAll('.pgso-ds-option').forEach(opt => {
                    opt.classList.remove('selected');
                    opt.setAttribute('aria-selected', 'false');
                    const check = opt.querySelector('.pgso-ds-check-icon');
                    if (check) check.classList.add('pgso-ds-hidden');
                });
                input.dispatchEvent(new Event('change', { bubbles: true }));
                return;
            }

            const targetOpt = wrapper.querySelector(`.pgso-ds-option[data-value="${strVal}"]`);
            if (targetOpt) {
                targetOpt.click();
            }
        }

        // ==============================================================
        // Global Helpers: Password Generator, Show/Hide & Clipboard Toast
        // ==============================================================
        let lastGeneratedPassword = '';

        function generateStrongPassword() {
            const lettersUpper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            const lettersLower = 'abcdefghijkmnpqrstuvwxyz';
            const numbers = '23456789';
            const symbols = '!@#$%^&*';
            const all = lettersUpper + lettersLower + numbers + symbols;

            let pwd = '';
            // Ensure at least one of each required character set
            pwd += lettersUpper[Math.floor(Math.random() * lettersUpper.length)];
            pwd += lettersLower[Math.floor(Math.random() * lettersLower.length)];
            pwd += numbers[Math.floor(Math.random() * numbers.length)];
            pwd += symbols[Math.floor(Math.random() * symbols.length)];

            // Fill up to 12 characters
            for (let i = 4; i < 12; i++) {
                pwd += all[Math.floor(Math.random() * all.length)];
            }

            // Shuffle
            return pwd.split('').sort(() => 0.5 - Math.random()).join('');
        }

        function generateAndFillPassword(pwdInputId, confirmInputId) {
            const pwd = generateStrongPassword();
            lastGeneratedPassword = pwd;

            const pwdInput = document.getElementById(pwdInputId);
            const confirmInput = document.getElementById(confirmInputId);

            if (pwdInput) {
                pwdInput.value = pwd;
                pwdInput.type = 'text'; // Show newly generated password clearly
            }
            if (confirmInput) {
                confirmInput.value = pwd;
                confirmInput.type = 'text';
            }

            const notice = document.getElementById('genPwdCopyNotice');
            if (notice) notice.style.display = 'flex';

            showToast('Strong password generated! Password visible for copying.');
        }

        function copyGeneratedPassword() {
            if (!lastGeneratedPassword) return;
            copyToClipboard(lastGeneratedPassword, 'Generated password copied to clipboard!');
        }

        function togglePasswordVisibility(inputId, triggerBtn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                triggerBtn.textContent = 'Hide';
            } else {
                input.type = 'password';
                triggerBtn.textContent = 'Show';
            }
        }

        function copyToClipboard(text, successMsg) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast(successMsg || 'Copied to clipboard');
                }).catch(() => {
                    fallbackCopy(text, successMsg);
                });
            } else {
                fallbackCopy(text, successMsg);
            }
        }

        function fallbackCopy(text, successMsg) {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            showToast(successMsg || 'Copied to clipboard');
        }

        function showToast(message) {
            const toast = document.getElementById('pgsoToast');
            const msgEl = document.getElementById('toastMessage');
            if (!toast || !msgEl) return;

            msgEl.textContent = message;
            toast.classList.add('show');

            clearTimeout(window._toastTimeout);
            window._toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>

</html>