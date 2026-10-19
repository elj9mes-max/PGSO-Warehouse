<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Monitor requisition requests, approval statuses, and warehouse activity logs">
    <title>Request Status & Activity Logs - Provincial Government of Aurora</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|jetbrains-mono:400,500,600,700" rel="stylesheet" />

    <style>
        :root {
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --border-subtle: #e2e8f0;
            --border-hover: #cbd5e1;
            --brand-primary: #059669;
            --brand-primary-hover: #047857;
            --brand-primary-light: #ecfdf5;
            --font-main: 'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Topbar Header */
        .topbar-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .topbar-branding {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .logo-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);
        }

        .brand-text-stack {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 0.9375rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: #0f172a;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.6875rem;
            color: #64748b;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-new-req {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #059669;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.2);
        }

        .btn-new-req:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            padding: 4px 12px 4px 5px;
            border-radius: 9999px;
            border: 1px solid #e2e8f0;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #0f172a;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-name-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Layout Container */
        .stat-container {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 28px 120px;
            flex: 1;
        }

        /* Header Row */
        .stat-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 20px;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7813rem;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .crumb-link {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .crumb-link:hover {
            color: var(--brand-primary);
        }

        .crumb-active {
            color: var(--text-primary);
            font-weight: 700;
        }

        .stat-title-area h1 {
            font-size: 1.625rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .stat-subtitle {
            font-size: 0.875rem;
            color: #64748b;
        }

        .office-station-pill {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            flex-shrink: 0;
        }

        .station-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #f1f5f9;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .station-text strong {
            display: block;
            font-size: 0.8438rem;
            color: #0f172a;
            font-weight: 700;
        }

        .station-text span {
            font-size: 0.7188rem;
            color: #64748b;
            font-family: var(--font-mono);
            font-weight: 600;
        }

        /* Metrics Summary Cards Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
            border-color: #cbd5e1;
        }

        .metric-info .label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .metric-info .value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            font-family: var(--font-mono);
            line-height: 1.1;
        }

        .metric-info .subtext {
            font-size: 0.6875rem;
            color: #94a3b8;
            margin-top: 3px;
        }

        .metric-icon-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-amber { background: #fffbeb; color: #d97706; }
        .icon-emerald { background: #ecfdf5; color: #059669; }
        .icon-purple { background: #faf5ff; color: #9333ea; }

        /* Tabs Navigation */
        .tabs-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-subtle);
            margin-bottom: 20px;
            gap: 16px;
        }

        .tabs-list {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 12px 18px;
            font-size: 0.875rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: #0f172a;
        }

        .tab-btn.active {
            color: #059669;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: #059669;
            border-radius: 2px 2px 0 0;
        }

        .tab-count-pill {
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 800;
            font-family: var(--font-mono);
            background: #f1f5f9;
            color: #475569;
        }

        .tab-btn.active .tab-count-pill {
            background: #d1fae5;
            color: #065f46;
        }

        /* Filter Controls */
        .filter-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-box-wrap {
            flex: 1;
            min-width: 280px;
            position: relative;
        }

        .search-box-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 17px;
            height: 17px;
        }

        .search-box-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            background: #f8fafc;
            border: 1.5px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.8438rem;
            color: #0f172a;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .search-box-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .dropdowns-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.7813rem;
            color: #64748b;
            padding-top: 6px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-reset-filters {
            background: transparent;
            border: none;
            color: #059669;
            font-size: 0.7813rem;
            font-weight: 700;
            cursor: pointer;
            display: none;
        }

        .btn-reset-filters:hover {
            text-decoration: underline;
        }

        /* Requests Cards Feed */
        .requests-feed {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .request-card {
            background: #ffffff;
            border: 1.5px solid var(--border-subtle);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .request-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .req-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .req-id-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .req-type-pill {
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 0.6875rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-family: var(--font-mono);
        }

        .type-ris {
            background: #e0e7ff;
            color: #3730a3;
        }

        .type-pr {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .req-number {
            font-size: 1.0625rem;
            font-weight: 800;
            color: #0f172a;
            font-family: var(--font-mono);
            letter-spacing: -0.01em;
        }

        .btn-copy-id {
            background: #f1f5f9;
            border: none;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-copy-id:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .req-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .status-pending {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .status-pending .status-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
        }

        .status-approved {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .status-approved .status-dot {
            background: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .status-issued {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .status-issued .status-dot {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }

        .status-rejected {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .status-rejected .status-dot {
            background: #ef4444;
        }

        .status-cancelled {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .status-cancelled .status-dot {
            background: #94a3b8;
        }

        /* Purpose & Notes Area */
        .req-purpose-box {
            background: #f8fafc;
            border-left: 3px solid #059669;
            padding: 10px 14px;
            border-radius: 0 8px 8px 0;
            font-size: 0.8438rem;
            color: #334155;
            line-height: 1.45;
        }

        .req-purpose-box strong {
            color: #0f172a;
            font-weight: 700;
        }

        /* Items Chips Preview Strip */
        .req-items-strip {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .items-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .item-chip {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 0.7813rem;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #1e293b;
        }

        .item-chip-qty {
            font-family: var(--font-mono);
            font-weight: 800;
            color: #059669;
            background: #ecfdf5;
            padding: 1px 5px;
            border-radius: 4px;
        }

        /* Stepper Workflow Progress */
        .workflow-stepper {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            background: #f8fafc;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .step-node {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6875rem;
            font-weight: 800;
            flex-shrink: 0;
            border: 2px solid #cbd5e1;
            background: #ffffff;
            color: #94a3b8;
        }

        .step-node.completed .step-circle {
            background: #059669;
            border-color: #059669;
            color: #ffffff;
        }

        .step-node.active .step-circle {
            background: #f59e0b;
            border-color: #f59e0b;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }

        .step-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .step-info .title {
            font-size: 0.7813rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .step-info .sub {
            font-size: 0.6875rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Card Footer & Action Buttons */
        .req-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            gap: 14px;
            flex-wrap: wrap;
        }

        .footer-meta-left {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.7813rem;
            color: #64748b;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .footer-actions-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action-view {
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            color: #1e293b;
            padding: 8px 14px;
            border-radius: 9px;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-action-view:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-action-print {
            background: #0f172a;
            border: 1px solid #0f172a;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 9px;
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-action-print:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-feed-card {
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 60px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        .empty-icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Warehouse Issuance Audit Table */
        .audit-table-wrap {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .audit-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .audit-table th {
            background: #f8fafc;
            padding: 12px 18px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid var(--border-subtle);
        }

        .audit-table td {
            padding: 14px 18px;
            font-size: 0.8438rem;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            vertical-align: middle;
        }

        .audit-table tr:hover td {
            background: #fcfdfe;
        }

        /* Modal Backdrop & Dialog */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 500;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal-dialog {
            background: #ffffff;
            border-radius: 18px;
            max-width: 780px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: modalPopIn 0.22s ease-out;
        }

        @keyframes modalPopIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .modal-header h3 {
            font-size: 1.125rem;
            font-weight: 800;
            color: #0f172a;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            background: #f8fafc;
        }

        /* Responsive */
        @media (max-width: 960px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .workflow-stepper {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 640px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            .stat-container {
                padding: 16px 16px 120px;
            }
            .workflow-stepper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    @php
        $userInitials = strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) ?: 'ST';

        $typeFilterOptions = [
            ['id' => '', 'name' => 'All Request Types'],
            ['id' => 'RIS', 'name' => 'RIS (Requisition Slip)'],
            ['id' => 'PR', 'name' => 'PR (Purchase Request)'],
        ];

        $statusFilterOptions = [
            ['id' => '', 'name' => 'All Statuses'],
            ['id' => 'pending', 'name' => 'Pending Review'],
            ['id' => 'approved', 'name' => 'Approved'],
            ['id' => 'issued', 'name' => 'Issued & Completed'],
            ['id' => 'rejected', 'name' => 'Rejected'],
        ];

        $sortFilterOptions = [
            ['id' => 'date-desc', 'name' => 'Newest First'],
            ['id' => 'date-asc', 'name' => 'Oldest First'],
            ['id' => 'units-desc', 'name' => 'Most Units Requested'],
        ];
    @endphp

    <!-- Topbar Header -->
    <header class="topbar-header">
        <div class="topbar-branding">
            <a href="{{ route('staff.inventory') }}" class="logo-wrap" title="Aurora Warehouse Management">
                <div class="logo-icon-box">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <div class="brand-text-stack">
                    <span class="brand-title">Warehouse Aurora</span>
                    <span class="brand-subtitle">Requisition Monitoring</span>
                </div>
            </a>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('staff.requisition.index') }}" class="btn-new-req" title="Create a new RIS or PR requisition">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>New Requisition</span>
            </a>

            <div class="user-pill">
                <div class="user-avatar">{{ $userInitials }}</div>
                <span class="user-name-label">{{ $user->full_name }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="stat-container">
        
        <!-- Header & Breadcrumbs -->
        <div class="stat-header-row">
            <div class="stat-title-area">
                <div class="breadcrumb-wrap">
                    <a href="{{ route('staff.inventory') }}" class="crumb-link">Inventory</a>
                    <span>/</span>
                    <a href="{{ route('staff.requisition.index') }}" class="crumb-link">Requisitions</a>
                    <span>/</span>
                    <span class="crumb-active">Request Status & Logs</span>
                </div>
                <h1>
                    <span>Request Status & Activity Logs</span>
                </h1>
                <p class="stat-subtitle">Monitor approval status, warehouse issuance, and audit history of your office's RIS and PR requests.</p>
            </div>

            <!-- Station Card -->
            <div class="office-station-pill">
                <div class="station-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="station-text">
                    <strong>{{ $activeOffice ? $activeOffice->office_name : 'General Staff Pool' }}</strong>
                    <span>{{ $activeOffice ? 'CODE: ' . $activeOffice->office_code : 'NO OFFICE' }}</span>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-info">
                    <div class="label">Total Requisitions</div>
                    <div class="value">{{ $totalCount }}</div>
                    <div class="subtext">RIS and PR requests logged</div>
                </div>
                <div class="metric-icon-box icon-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M13 6h3a2 2 0 0 1 2 2v7"/><line x1="6" x2="6" y1="9" y2="21"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <div class="label">Pending Review</div>
                    <div class="value">{{ $pendingCount }}</div>
                    <div class="subtext">Awaiting PGSO authorization</div>
                </div>
                <div class="metric-icon-box icon-amber">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <div class="label">Approved & Ready</div>
                    <div class="value">{{ $approvedCount }}</div>
                    <div class="subtext">Ready for warehouse release</div>
                </div>
                <div class="metric-icon-box icon-blue">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <div class="label">Completed / Issued</div>
                    <div class="value">{{ $issuedCount }}</div>
                    <div class="subtext">{{ $totalUnitsRequested }} total units requested</div>
                </div>
                <div class="metric-icon-box icon-emerald">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
            </div>
        </div>

        <!-- View Switcher Tabs -->
        <div class="tabs-row">
            <div class="tabs-list">
                <button type="button" class="tab-btn active" id="tabRequestsBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>Requisition Slips</span>
                    <span class="tab-count-pill">{{ $totalCount }}</span>
                </button>
                <button type="button" class="tab-btn" id="tabAuditLogsBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    <span>Warehouse Issuance Log</span>
                    <span class="tab-count-pill">{{ $inventoryLogs->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Tab Panel 1: Requisitions Feed -->
        <section id="panelRequests">
            
            <!-- Filters Toolbar -->
            <div class="filter-card">
                <div class="filter-card-top">
                    <!-- Search Input -->
                    <div class="search-box-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input 
                            type="text" 
                            id="statSearchInput" 
                            class="search-box-input" 
                            placeholder="Search by Request Number, Purpose, Item Name, or Stock No..." 
                            autocomplete="off"
                        >
                    </div>

                    <!-- Dropdowns -->
                    <div class="dropdowns-wrap">
                        <div style="min-width: 190px;">
                            <x-dropdown-search 
                                name="type_filter"
                                id="statTypeSelect"
                                :options="$typeFilterOptions"
                                valueKey="id"
                                labelKey="name"
                                placeholder="All Request Types"
                                searchPlaceholder="Filter type..."
                                :allowClear="true"
                            />
                        </div>

                        <div style="min-width: 180px;">
                            <x-dropdown-search 
                                name="status_filter"
                                id="statStatusSelect"
                                :options="$statusFilterOptions"
                                valueKey="id"
                                labelKey="name"
                                placeholder="All Statuses"
                                searchPlaceholder="Filter status..."
                                :allowClear="true"
                            />
                        </div>

                        <div style="min-width: 180px;">
                            <x-dropdown-search 
                                name="sort_filter"
                                id="statSortSelect"
                                :options="$sortFilterOptions"
                                valueKey="id"
                                labelKey="name"
                                selected="date-desc"
                                placeholder="Sort By..."
                                searchPlaceholder="Sort order..."
                                :allowClear="false"
                            />
                        </div>
                    </div>
                </div>

                <div class="filter-meta-row">
                    <span id="requestsCountText">Showing {{ $requests->count() }} of {{ $requests->count() }} logged requisitions</span>
                    <button type="button" class="btn-reset-filters" id="resetStatFiltersBtn">Reset Filters</button>
                </div>
            </div>

            <!-- Requests List -->
            <div class="requests-feed" id="requestsFeedList">
                @forelse($requests as $req)
                    @php
                        $itemsCount = $req->items->count();
                        $totalUnits = (int) $req->items->sum('quantity_requested');
                        $issuedUnits = (int) $req->items->sum('quantity_issued');
                        $statusClass = match(strtolower($req->status)) {
                            'approved' => 'status-approved',
                            'issued' => 'status-issued',
                            'rejected' => 'status-rejected',
                            'cancelled' => 'status-cancelled',
                            default => 'status-pending',
                        };

                        $statusLabel = match(strtolower($req->status)) {
                            'approved' => 'Approved (Ready to Issue)',
                            'issued' => 'Completed & Issued',
                            'rejected' => 'Rejected',
                            'cancelled' => 'Cancelled',
                            default => 'Pending PGSO Review',
                        };
                    @endphp

                    <div 
                        class="request-card"
                        data-id="{{ $req->id }}"
                        data-number="{{ strtolower($req->request_number) }}"
                        data-type="{{ strtoupper($req->request_type) }}"
                        data-status="{{ strtolower($req->status) }}"
                        data-purpose="{{ strtolower($req->purpose ?? '') }}"
                        data-items="{{ strtolower($req->items->pluck('item_name')->join(' ') . ' ' . $req->items->pluck('stock_no')->join(' ')) }}"
                        data-units="{{ $totalUnits }}"
                        data-date="{{ $req->created_at->timestamp }}"
                    >
                        <!-- Top Header -->
                        <div class="req-card-header">
                            <div class="req-id-wrap">
                                <span class="req-type-pill {{ $req->request_type === 'PR' ? 'type-pr' : 'type-ris' }}">
                                    {{ $req->request_type }}
                                </span>
                                <span class="req-number">{{ $req->request_number }}</span>
                                <button type="button" class="btn-copy-id" onclick="navigator.clipboard.writeText('{{ $req->request_number }}'); alert('Copied {{ $req->request_number }} to clipboard!')" title="Copy Request ID">
                                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                </button>
                            </div>

                            <div class="req-status-pill {{ $statusClass }}">
                                <span class="status-dot"></span>
                                <span>{{ $statusLabel }}</span>
                            </div>
                        </div>

                        <!-- Purpose Note -->
                        @if($req->purpose)
                            <div class="req-purpose-box">
                                <strong>Purpose:</strong> {{ $req->purpose }}
                            </div>
                        @endif

                        <!-- Items Strip -->
                        <div class="req-items-strip">
                            <span class="items-label">{{ $itemsCount }} Item{{ $itemsCount === 1 ? '' : 's' }} ({{ $totalUnits }} units):</span>
                            @foreach($req->items->take(4) as $item)
                                <div class="item-chip">
                                    <span>{{ $item->item_name }}</span>
                                    <span class="item-chip-qty">{{ $item->quantity_requested }} {{ $item->unit }}</span>
                                </div>
                            @endforeach
                            @if($itemsCount > 4)
                                <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">+{{ $itemsCount - 4 }} more</span>
                            @endif
                        </div>

                        <!-- Workflow Stepper -->
                        <div class="workflow-stepper">
                            <!-- Step 1: Submitted -->
                            <div class="step-node completed">
                                <div class="step-circle">✓</div>
                                <div class="step-info">
                                    <span class="title">Submitted</span>
                                    <span class="sub">{{ $req->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Step 2: Approved -->
                            <div class="step-node {{ in_array(strtolower($req->status), ['approved', 'issued']) ? 'completed' : (strtolower($req->status) === 'pending' ? 'active' : '') }}">
                                <div class="step-circle">{{ in_array(strtolower($req->status), ['approved', 'issued']) ? '✓' : '2' }}</div>
                                <div class="step-info">
                                    <span class="title">PGSO Review</span>
                                    <span class="sub">{{ $req->approved_by_name ? 'By ' . $req->approved_by_name : (strtolower($req->status) === 'pending' ? 'In Queue' : 'Pending') }}</span>
                                </div>
                            </div>

                            <!-- Step 3: Warehouse Issuance -->
                            <div class="step-node {{ strtolower($req->status) === 'issued' ? 'completed' : (strtolower($req->status) === 'approved' ? 'active' : '') }}">
                                <div class="step-circle">{{ strtolower($req->status) === 'issued' ? '✓' : '3' }}</div>
                                <div class="step-info">
                                    <span class="title">Stock Issuance</span>
                                    <span class="sub">{{ strtolower($req->status) === 'issued' ? 'Completed' : (strtolower($req->status) === 'approved' ? 'Awaiting Dispatch' : 'Pending') }}</span>
                                </div>
                            </div>

                            <!-- Step 4: Acknowledged -->
                            <div class="step-node {{ strtolower($req->status) === 'issued' ? 'completed' : '' }}">
                                <div class="step-circle">{{ strtolower($req->status) === 'issued' ? '✓' : '4' }}</div>
                                <div class="step-info">
                                    <span class="title">Custody Transfer</span>
                                    <span class="sub">{{ $req->received_by_name ? 'Signed by ' . $req->received_by_name : 'Pending Receipt' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="req-card-footer">
                            <div class="footer-meta-left">
                                <div class="meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>Logged {{ $req->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Requester: {{ $req->requested_by_name ?? ($req->user?->full_name ?? 'Staff') }}</span>
                                </div>
                            </div>

                            <div class="footer-actions-right">
                                <button type="button" class="btn-action-view" onclick="openDetailsModal({{ $req->toJson() }})">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>View Items ({{ $itemsCount }})</span>
                                </button>

                                <a href="{{ route('staff.requisition.ris-form', ['requisition' => $req->id]) }}" class="btn-action-print" title="Open Printable RIS Slip">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                    <span>Print Form</span>
                                </a>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="empty-feed-card">
                        <div class="empty-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M13 6h3a2 2 0 0 1 2 2v7"/><line x1="6" x2="6" y1="9" y2="21"/></svg>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 800; color: #0f172a;">No Requisition Requests Logged Yet</h3>
                        <p style="font-size: 0.875rem; color: #64748b; max-width: 400px; line-height: 1.5;">You haven't submitted any Requisition & Issue Slips (RIS) or Purchase Requests (PR) for your office station yet.</p>
                        <a href="{{ route('staff.requisition.index') }}" class="btn-new-req" style="margin-top: 6px;">
                            <span>Create Your First Requisition</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Tab Panel 2: Warehouse Issuance Audit Logs -->
        <section id="panelAuditLogs" style="display: none;">
            <div class="audit-table-wrap">
                <table class="audit-table">
                    <thead>
                        <tr>
                            <th>Reference / Batch</th>
                            <th>Item & Specification</th>
                            <th>Stock Quantity</th>
                            <th>Purpose / Destination</th>
                            <th>Released By</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryLogs as $log)
                            <tr>
                                <td>
                                    <strong style="font-family: var(--font-mono); color: #0f172a;">
                                        {{ $log->reference_number ?? ('REL-' . str_pad((string)$log->id, 5, '0', STR_PAD_LEFT)) }}
                                    </strong>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #0f172a;">{{ $log->item_name ?? 'Standard Item' }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $log->variant_name ?? ($log->variant?->variant_name ?? 'Standard Variant') }}</div>
                                </td>
                                <td>
                                    <span style="font-weight: 800; font-family: var(--font-mono); color: #059669; background: #ecfdf5; padding: 2px 7px; border-radius: 5px;">
                                        {{ $log->quantity ?? 1 }} {{ $log->variant?->effective_unit ?? 'pcs' }}
                                    </span>
                                </td>
                                <td>
                                    <div>{{ $log->purpose ?: 'Official Office Stock Issuance' }}</div>
                                    @if($log->recipient)
                                        <div style="font-size: 0.7188rem; color: #64748b;">Recipient: {{ $log->recipient }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight: 600;">{{ $log->user?->full_name ?? ($log->user?->name ?? 'Warehouse Officer') }}</span>
                                </td>
                                <td style="font-family: var(--font-mono); font-size: 0.7813rem; color: #64748b;">
                                    {{ $log->created_at->format('M d, Y • h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                                    No warehouse issuance audit logs recorded for this office custody yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- Request Details Breakdown Modal -->
    <div class="modal-backdrop" id="detailsModalBackdrop">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h3 id="modalReqNumber">Requisition Details</h3>
                    <span id="modalReqStatus" style="font-size: 0.75rem; font-weight: 700;"></span>
                </div>
                <button type="button" onclick="closeDetailsModal()" style="background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer;">✕</button>
            </div>

            <div class="modal-body">
                <!-- Metadata Box -->
                <div style="background: #f8fafc; border: 1px solid var(--border-subtle); border-radius: 12px; padding: 14px 16px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; font-size: 0.8125rem;">
                    <div><span style="color: #64748b;">Request Type:</span> <strong id="modalReqType">RIS</strong></div>
                    <div><span style="color: #64748b;">Date Created:</span> <strong id="modalReqDate"></strong></div>
                    <div><span style="color: #64748b;">Requester:</span> <strong id="modalReqStaff"></strong></div>
                    <div><span style="color: #64748b;">Office Station:</span> <strong id="modalReqOffice"></strong></div>
                </div>

                <!-- Purpose Box -->
                <div>
                    <span style="font-size: 0.7188rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Purpose / Justification</span>
                    <div id="modalReqPurpose" style="font-size: 0.8438rem; color: #0f172a; margin-top: 4px; padding: 10px 14px; background: #ffffff; border: 1px solid var(--border-subtle); border-radius: 9px;"></div>
                </div>

                <!-- Items Table -->
                <div>
                    <span style="font-size: 0.7188rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Requested Line Items</span>
                    <div style="margin-top: 6px; border: 1px solid var(--border-subtle); border-radius: 10px; overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.8125rem;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-subtle); text-align: left;">
                                    <th style="padding: 9px 12px; color: #475569;">Item Name</th>
                                    <th style="padding: 9px 12px; color: #475569;">SKU / Stock No</th>
                                    <th style="padding: 9px 12px; text-align: center; color: #475569;">Qty Req</th>
                                    <th style="padding: 9px 12px; text-align: center; color: #475569;">Qty Issued</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsTableBody">
                                <!-- Dynamic Items -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Signatures Review -->
                <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 16px; font-size: 0.7813rem; display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    <div><span style="color: #64748b;">Approved By:</span> <strong id="modalApprovedBy">RICARDO Q. BAUTISTA (PGDH-PGSO)</strong></div>
                    <div><span style="color: #64748b;">Issued By:</span> <strong id="modalIssuedBy">RAYMOND G. AMAT (AA-III)</strong></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeDetailsModal()" style="padding: 9px 16px; background: #ffffff; border: 1px solid var(--border-subtle); border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Close
                </button>
                <a href="{{ route('staff.requisition.ris-form') }}" id="modalPrintLink" class="btn-action-print">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    <span>Print Official RIS Slip</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Bottom Navigation -->
    <x-staffNav />

    <!-- Script for Tab Switching, Filtering, and Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Tab Switching
            const tabRequestsBtn = document.getElementById('tabRequestsBtn');
            const tabAuditLogsBtn = document.getElementById('tabAuditLogsBtn');
            const panelRequests = document.getElementById('panelRequests');
            const panelAuditLogs = document.getElementById('panelAuditLogs');

            if (tabRequestsBtn && tabAuditLogsBtn) {
                tabRequestsBtn.addEventListener('click', () => {
                    tabRequestsBtn.classList.add('active');
                    tabAuditLogsBtn.classList.remove('active');
                    panelRequests.style.display = 'block';
                    panelAuditLogs.style.display = 'none';
                });

                tabAuditLogsBtn.addEventListener('click', () => {
                    tabAuditLogsBtn.classList.add('active');
                    tabRequestsBtn.classList.remove('active');
                    panelAuditLogs.style.display = 'block';
                    panelRequests.style.display = 'none';
                });
            }

            // Client-side Filtering
            const searchInput = document.getElementById('statSearchInput');
            const typeSelect = document.getElementById('statTypeSelect');
            const statusSelect = document.getElementById('statStatusSelect');
            const sortSelect = document.getElementById('statSortSelect');
            const countText = document.getElementById('requestsCountText');
            const resetBtn = document.getElementById('resetStatFiltersBtn');
            const feedContainer = document.getElementById('requestsFeedList');
            const cards = Array.from(feedContainer?.querySelectorAll('.request-card') || []);
            const totalCount = cards.length;

            function applyFilters() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const selectedType = (typeSelect?.value || '').trim().toUpperCase();
                const selectedStatus = (statusSelect?.value || '').trim().toLowerCase();
                const selectedSort = sortSelect?.value || 'date-desc';

                let visibleCards = [];

                cards.forEach(card => {
                    const number = card.dataset.number || '';
                    const type = card.dataset.type || '';
                    const status = card.dataset.status || '';
                    const purpose = card.dataset.purpose || '';
                    const items = card.dataset.items || '';

                    const matchesQuery = !query || 
                        number.includes(query) || 
                        purpose.includes(query) || 
                        items.includes(query);

                    const matchesType = !selectedType || type === selectedType;
                    const matchesStatus = !selectedStatus || status === selectedStatus;

                    if (matchesQuery && matchesType && matchesStatus) {
                        card.style.display = 'flex';
                        visibleCards.push(card);
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Sort Visible Cards
                visibleCards.sort((a, b) => {
                    const dateA = parseInt(a.dataset.date) || 0;
                    const dateB = parseInt(b.dataset.date) || 0;
                    const unitsA = parseInt(a.dataset.units) || 0;
                    const unitsB = parseInt(b.dataset.units) || 0;

                    switch (selectedSort) {
                        case 'date-desc': return dateB - dateA;
                        case 'date-asc': return dateA - dateB;
                        case 'units-desc': return unitsB - unitsA;
                        default: return 0;
                    }
                });

                visibleCards.forEach(card => feedContainer.appendChild(card));

                if (countText) {
                    countText.textContent = `Showing ${visibleCards.length} of ${totalCount} logged requisitions`;
                }

                const hasActiveFilters = Boolean(query || selectedType || selectedStatus || (selectedSort !== 'date-desc'));
                if (resetBtn) {
                    resetBtn.style.display = hasActiveFilters ? 'inline-flex' : 'none';
                }
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (typeSelect) typeSelect.addEventListener('change', applyFilters);
            if (statusSelect) statusSelect.addEventListener('change', applyFilters);
            if (sortSelect) sortSelect.addEventListener('change', applyFilters);

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    if (typeSelect) { typeSelect.value = ''; typeSelect.dispatchEvent(new Event('change')); }
                    if (statusSelect) { statusSelect.value = ''; statusSelect.dispatchEvent(new Event('change')); }
                    if (sortSelect) { sortSelect.value = 'date-desc'; sortSelect.dispatchEvent(new Event('change')); }
                    applyFilters();
                });
            }
        });

        // Details Modal Logic
        const modalBackdrop = document.getElementById('detailsModalBackdrop');
        const modalReqNumber = document.getElementById('modalReqNumber');
        const modalReqStatus = document.getElementById('modalReqStatus');
        const modalReqType = document.getElementById('modalReqType');
        const modalReqDate = document.getElementById('modalReqDate');
        const modalReqStaff = document.getElementById('modalReqStaff');
        const modalReqOffice = document.getElementById('modalReqOffice');
        const modalReqPurpose = document.getElementById('modalReqPurpose');
        const modalItemsTableBody = document.getElementById('modalItemsTableBody');
        const modalApprovedBy = document.getElementById('modalApprovedBy');
        const modalIssuedBy = document.getElementById('modalIssuedBy');

        function openDetailsModal(request) {
            if (!request) return;

            modalReqNumber.textContent = request.request_number;
            modalReqStatus.textContent = (request.status || 'Pending').toUpperCase();
            modalReqType.textContent = request.request_type || 'RIS';
            modalReqDate.textContent = new Date(request.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            modalReqStaff.textContent = request.requested_by_name || (request.user ? (request.user.first_name + ' ' + request.user.last_name) : 'Staff');
            modalReqOffice.textContent = request.office ? request.office.office_name : 'Assigned Office';
            modalReqPurpose.textContent = request.purpose || 'No purpose remarks specified';
            modalApprovedBy.textContent = request.approved_by_name ? `${request.approved_by_name} (${request.approved_by_designation || 'PGDH-PGSO'})` : 'Awaiting Review';
            modalIssuedBy.textContent = request.issued_by_name ? `${request.issued_by_name} (${request.issued_by_designation || 'AA-III'})` : 'Pending Issuance';

            modalItemsTableBody.innerHTML = '';
            const items = request.items || [];

            if (items.length === 0) {
                modalItemsTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 18px; color: #94a3b8;">No line items attached</td></tr>';
            } else {
                items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid #f1f5f9';
                    tr.innerHTML = `
                        <td style="padding: 8px 12px;">
                            <strong>${item.item_name}</strong>
                            <div style="font-size: 0.72rem; color: #64748b;">${item.variant_name || ''}</div>
                        </td>
                        <td style="padding: 8px 12px; font-family: var(--font-mono); font-size: 0.75rem;">${item.stock_no || 'N/A'}</td>
                        <td style="padding: 8px 12px; text-align: center; font-weight: 700; font-family: var(--font-mono); color: #059669;">${item.quantity_requested} ${item.unit}</td>
                        <td style="padding: 8px 12px; text-align: center; font-weight: 700; font-family: var(--font-mono);">${item.quantity_issued || 0}</td>
                    `;
                    modalItemsTableBody.appendChild(tr);
                });
            }

            const modalPrintLink = document.getElementById('modalPrintLink');
            if (modalPrintLink) {
                modalPrintLink.href = "{{ route('staff.requisition.ris-form') }}?requisition=" + request.id;
            }

            modalBackdrop.classList.add('open');
        }

        function closeDetailsModal() {
            modalBackdrop.classList.remove('open');
        }

        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeDetailsModal();
        });
    </script>

</body>
</html>
