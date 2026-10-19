<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Staff Office Stock Inventory</title>
    
    <!-- Modern Sans & Mono Fonts -->
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
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #09090b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .brand-icon svg {
            width: 18px;
            height: 18px;
        }

        .brand-text strong {
            font-size: 0.9375rem;
            display: block;
            line-height: 1.2;
            color: var(--text-primary);
        }

        .brand-text span {
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #18181b 0%, #3f3f46 100%);
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

        .role-pill {
            font-size: 0.6875rem;
            background: #f4f4f5;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 700;
            border: 1px solid var(--border-subtle);
            color: #27272a;
        }

        /* Layout */
        .dashboard-content-area {
            padding: 32px 36px;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Header */
        .staff-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .role-badge-staff {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #f4f4f5;
            color: #18181b;
            border: 1px solid var(--border-subtle);
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.8438rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Office Location Banner */
        .office-location-banner {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            flex-wrap: wrap;
        }

        .office-banner-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .office-banner-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background-color: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .office-banner-icon svg {
            width: 20px;
            height: 20px;
        }

        .office-banner-title {
            font-size: 1.0625rem;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .office-code-tag {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .duty-station-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .office-banner-desc {
            font-size: 0.7813rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .office-switcher-group {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 280px;
        }

        .office-switcher-label {
            font-size: 0.7813rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease, transform 0.15s ease;
        }

        .stat-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-1px);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f5;
            color: #52525b;
        }

        .stat-icon svg {
            width: 16px;
            height: 16px;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 8px 0 2px 0;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .stat-sub {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Panels Grid */
        .panels-grid {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 20px;
        }

        .panel-box {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .panel-heading-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f4f4f5;
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-title {
            font-size: 1.0625rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        /* Filter Panel in Table */
        .filter-panel {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 240px;
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
            width: 15px;
            height: 15px;
        }

        .search-input {
            width: 100%;
            height: 40px;
            padding: 0 32px 0 36px;
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

        .table-filters {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .counter-pill {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 4px 10px;
            background-color: #f4f4f5;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* Clean SaaS Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .saas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8438rem;
        }

        .saas-table th {
            text-align: left;
            font-weight: 700;
            color: var(--text-muted);
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-subtle);
            background-color: #fafafa;
            font-size: 0.7188rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .saas-table td {
            padding: 14px;
            border-bottom: 1px solid #f4f4f5;
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .saas-table tbody tr {
            transition: background-color 0.12s ease;
        }

        .saas-table tbody tr:hover {
            background-color: #fbfbfa;
        }

        .item-main-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .item-name-text {
            font-weight: 700;
            color: var(--text-primary);
        }

        .variant-name-subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .sku-tag {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            color: #3f3f46;
            background-color: #f4f4f5;
            padding: 1px 6px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            width: fit-content;
        }

        .sku-tag:hover {
            background-color: #e4e4e7;
            color: #09090b;
        }

        .category-pill {
            display: inline-block;
            font-size: 0.7188rem;
            font-weight: 600;
            color: #4b5563;
            background-color: #f3f4f6;
            padding: 2px 8px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .quantity-cell {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .quantity-number {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .quantity-unit {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .badge-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7188rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .badge-status-pill.success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-status-pill.warning {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-status-pill.danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Empty State */
        .empty-state {
            padding: 48px 16px;
            text-align: center;
        }

        .empty-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 12px auto;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f5;
            border-radius: 12px;
        }

        .empty-icon svg {
            width: 22px;
            height: 22px;
        }

        .empty-state h3 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .empty-state p {
            font-size: 0.8125rem;
            color: var(--text-muted);
            max-width: 360px;
            margin: 0 auto;
        }

        /* Profile Side Cards */
        .profile-card-item {
            padding: 14px;
            background: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .profile-card-item strong {
            font-size: 0.7813rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
        }

        .profile-card-item span {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .btn-outline-action {
            height: 38px;
            padding: 0 14px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background-color: #ffffff;
            color: var(--text-primary);
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            width: 100%;
        }

        .btn-outline-action:hover {
            background-color: #fafafa;
            border-color: var(--border-hover);
        }

        /* Toast */
        .pgso-toast {
            position: fixed;
            bottom: 84px;
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
            transform: translateY(20px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pgso-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        @media (max-width: 960px) {
            .panels-grid {
                grid-template-columns: 1fr;
            }
            .dashboard-content-area {
                padding: 20px 16px;
            }
            .office-location-banner {
                flex-direction: column;
                align-items: stretch;
            }
            .office-switcher-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    @php
        $canSwitchOffice = $canSwitchOffice ?? ($user->isAdmin() || is_null($user->office_id));

        // Normalized options for DropdownSearch components
        $officeSelectOptions = $allOffices->map(fn($o) => [
            'id' => (string) $o->id,
            'name' => $o->office_name,
            'code' => $o->office_code,
        ])->toArray();

        $categoryFilterOptions = collect([
            ['id' => '', 'name' => 'All Categories', 'code' => 'ALL'],
        ])->concat($categories->map(fn($c) => [
            'id' => (string) $c,
            'name' => (string) $c,
            'code' => '',
        ]))->toArray();

        $statusFilterOptions = [
            ['id' => '', 'name' => 'All Stock Statuses', 'code' => 'ALL'],
            ['id' => 'In Stock', 'name' => 'In Stock', 'code' => 'NORMAL'],
            ['id' => 'Low Stock', 'name' => 'Low Stock', 'code' => 'ALERT'],
            ['id' => 'Out of Stock', 'name' => 'Out of Stock', 'code' => 'EMPTY'],
        ];

        $userInitials = strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) ?: 'ST';
    @endphp

    <!-- Staff Top Brand Header -->
    <header class="staff-topbar">
        <div class="brand-group">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                    <path d="m3.3 7 8.7 5 8.7-5"/>
                    <path d="M12 22V12"/>
                </svg>
            </div>
            <div class="brand-text">
                <strong>PGSO Inventory System</strong>
                <span>Provincial Warehouse Operations</span>
            </div>
        </div>

        <div class="user-pill">
            <div class="user-avatar">{{ $userInitials }}</div>
            <span class="user-name-label">{{ $user->full_name }}</span>
            <span class="role-pill">{{ $user->account_level?->label() ?? 'Staff' }}</span>
        </div>
    </header>

    <div class="dashboard-content-area">

        <!-- Flash Alert -->
        @if(session('status'))
            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.8438rem; display: flex; align-items: center; gap: 10px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.8438rem; display: flex; align-items: center; gap: 10px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header -->
        <div class="staff-header">
            <div>
                <span class="role-badge-staff">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Staff Custody Workspace</span>
                </span>
                <h1 class="page-title">Office Stock Inventory</h1>
                <p class="page-subtitle">Welcome back, {{ $user->full_name }}. Real-time balance of supply allocations and property custody.</p>
            </div>
        </div>

        <!-- Designated Office Banner & DropdownSearch Switcher -->
        <div class="office-location-banner">
            <div class="office-banner-info">
                <div class="office-banner-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"/>
                        <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                        <path d="M9 9h1"/><path d="M9 13h1"/><path d="M9 17h1"/>
                        <path d="M14 9h1"/><path d="M14 13h1"/><path d="M14 17h1"/>
                    </svg>
                </div>
                <div>
                    <div class="office-banner-title">
                        <span>{{ $activeOffice ? $activeOffice->office_name : 'No Office Assigned (Floating Staff)' }}</span>
                        @if($activeOffice)
                            <span class="office-code-tag">{{ $activeOffice->office_code }}</span>
                            @if($user->office_id === $activeOffice->id)
                                <span class="duty-station-badge">Your Designated Office</span>
                            @else
                                <span class="duty-station-badge" style="background-color: #eff6ff; color: #1e40af; border-color: #bfdbfe;">Viewing Office</span>
                            @endif
                        @else
                            <span class="office-code-tag" style="background-color: #fef3c7; color: #92400e; border-color: #fde68a;">GENERAL POOL</span>
                        @endif
                    </div>
                    <div class="office-banner-desc">
                        @if($activeOffice)
                            <span>Property & Supply custody account for {{ $activeOffice->office_name }}</span>
                        @else
                            <span>You are currently assigned to the warehouse floating pool. Select an office to inspect its allocated items.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Office Switcher with DropdownSearch -->
            <div class="office-switcher-group">
                <span class="office-switcher-label">{{ $canSwitchOffice ? 'Switch Office:' : 'Designated Office:' }}</span>
                <div style="flex: 1; min-width: 220px;">
                    <x-dropdown-search 
                        name="office_id"
                        id="staffOfficeSelect"
                        :options="$officeSelectOptions"
                        valueKey="id"
                        labelKey="name"
                        subtextKey="code"
                        :selected="$activeOffice?->id"
                        placeholder="Choose Office..."
                        searchPlaceholder="Type office name or code..."
                        :allowClear="false"
                        :disabled="! $canSwitchOffice"
                    />
                </div>
            </div>
        </div>

        <!-- Stats Overview Bar -->
        <div class="stats-grid">
            <!-- Total Custody Balance Units -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Stock in Custody</span>
                    <div class="stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ number_format($totalStockUnits) }}</div>
                <span class="stat-sub">Units held at this location</span>
            </div>

            <!-- Total Allocated Line Items -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Allocated Items</span>
                    <div class="stat-icon" style="background-color: #ecfdf5; color: #059669;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="8" y1="6" x2="21" y2="6"/>
                            <line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/>
                            <line x1="3" y1="12" x2="3.01" y2="12"/>
                            <line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ $allocatedItemsCount }}</div>
                <span class="stat-sub">Distinct variant catalog lines</span>
            </div>

            <!-- Low / Depleted Stock Warning -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Reorder / Low Stock</span>
                    <div class="stat-icon" style="background-color: {{ ($lowStockCount + $outOfStockCount) > 0 ? '#fef3c7' : '#f4f4f5' }}; color: {{ ($lowStockCount + $outOfStockCount) > 0 ? '#d97706' : '#71717a' }};">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-number">{{ $lowStockCount + $outOfStockCount }}</div>
                <span class="stat-sub">{{ $outOfStockCount }} depleted • {{ $lowStockCount }} below reorder</span>
            </div>

            <!-- Total Valuation -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Custody Value</span>
                    <div class="stat-icon">
                        <span style="font-weight: 800; font-size: 0.875rem;">₱</span>
                    </div>
                </div>
                <div class="stat-number" style="font-size: 1.45rem;">₱{{ number_format($totalStockValue, 2) }}</div>
                <span class="stat-sub">Estimated inventory worth</span>
            </div>
        </div>

        <!-- Main Panels Layout -->
        <div class="panels-grid">
            
            <!-- Left Panel: Office Stock Inventory Table -->
            <div class="panel-box">
                <div class="panel-heading-row">
                    <div>
                        <h2 class="panel-title">Allocated Supplies & Stock Inventory</h2>
                        <p style="font-size: 0.7813rem; color: var(--text-muted); margin-top: 2px;">
                            Detailed ledger of supplies physically stationed in {{ $activeOffice ? $activeOffice->office_name : 'this location' }}
                        </p>
                    </div>
                </div>

                <!-- Filters & Search Toolbar -->
                <div class="filter-panel">
                    <div class="search-box">
                        <span class="search-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            id="inventorySearchInput" 
                            class="search-input" 
                            placeholder="Search by item name, SKU, barcode, code, location..." 
                            autocomplete="off"
                        >
                        <button type="button" class="search-clear-btn" id="searchClearBtn" title="Clear search">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="table-filters">
                        <!-- DropdownSearch: Category Filter -->
                        <div style="min-width: 180px;">
                            <x-dropdown-search 
                                name="category_filter"
                                id="categoryFilterSelect"
                                :options="$categoryFilterOptions"
                                valueKey="id"
                                labelKey="name"
                                placeholder="All Categories"
                                searchPlaceholder="Filter category..."
                                :allowClear="true"
                            />
                        </div>

                        <!-- DropdownSearch: Status Filter -->
                        <div style="min-width: 170px;">
                            <x-dropdown-search 
                                name="status_filter"
                                id="statusFilterSelect"
                                :options="$statusFilterOptions"
                                valueKey="id"
                                labelKey="name"
                                placeholder="All Statuses"
                                searchPlaceholder="Filter status..."
                                :allowClear="true"
                            />
                        </div>

                        <div class="counter-pill" id="inventoryCounterPill">
                            Showing {{ $officeInventories->count() }} of {{ $officeInventories->count() }} items
                        </div>
                    </div>
                </div>

                <!-- Responsive Inventory Table -->
                <div class="table-responsive">
                    <table class="saas-table" id="officeInventoryTable">
                        <thead>
                            <tr>
                                <th>Item Code / Barcode</th>
                                <th>Item & Variant Details</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Office Balance</th>
                                <th>Status</th>
                                <th style="text-align: right;">Unit Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($officeInventories as $oi)
                                @php
                                    $variant = $oi->variant;
                                    $item = $variant?->inventoryItem;
                                    $qty = (int) $oi->quantity;
                                    $reorder = (int) ($variant?->reorder_level ?? 5);
                                    $unit = $variant?->effective_unit ?? ($item?->unit ?? 'units');
                                    $unitCost = (float) ($variant?->unit_cost ?? $item?->unit_cost ?? 0);
                                    
                                    if ($qty <= 0) {
                                        $statusClass = 'danger';
                                        $statusText = 'Out of Stock';
                                    } elseif ($qty <= $reorder) {
                                        $statusClass = 'warning';
                                        $statusText = 'Low Stock';
                                    } else {
                                        $statusClass = 'success';
                                        $statusText = 'In Stock';
                                    }

                                    $code = $variant?->sku ?: ($variant?->barcode ?: ($item?->item_code ?? 'ITM-'.str_pad($oi->id, 4, '0', STR_PAD_LEFT)));
                                    $parentName = $item?->name ?? 'Inventory Supply Item';
                                    $variantName = $variant?->variant_name ?? '';
                                    $categoryName = $item?->category ?? 'General Supplies';
                                    $locationText = $variant?->location ?: ($item?->location ?: 'Storage Bay');
                                @endphp
                                <tr 
                                    class="inventory-row"
                                    data-code="{{ strtolower($code) }}"
                                    data-name="{{ strtolower($parentName . ' ' . $variantName) }}"
                                    data-category="{{ strtolower($categoryName) }}"
                                    data-location="{{ strtolower($locationText) }}"
                                    data-status="{{ $statusText }}"
                                >
                                    <!-- Code / SKU -->
                                    <td>
                                        <span 
                                            class="sku-tag" 
                                            title="Click to copy code: {{ $code }}"
                                            onclick="copyToClipboard('{{ $code }}', 'Code {{ $code }} copied!')"
                                        >
                                            <strong>{{ $code }}</strong>
                                            <svg viewBox="0 0 24 24" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                        </span>
                                    </td>

                                    <!-- Item Name & Variant Details -->
                                    <td>
                                        <div class="item-main-cell">
                                            <span class="item-name-text">{{ $parentName }}</span>
                                            @if($variantName && $variantName !== $parentName)
                                                <span class="variant-name-subtext">{{ $variantName }}</span>
                                            @endif
                                            @if($variant?->variant_description)
                                                <span style="font-size: 0.7188rem; color: #a1a1aa;">{{ Str::limit($variant->variant_description, 50) }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <span class="category-pill">{{ $categoryName }}</span>
                                    </td>

                                    <!-- Location -->
                                    <td>
                                        <span style="font-size: 0.7813rem; color: var(--text-primary); font-weight: 500;">
                                            {{ $locationText }}
                                        </span>
                                    </td>

                                    <!-- Office Balance -->
                                    <td>
                                        <div class="quantity-cell">
                                            <span class="quantity-number">{{ number_format($qty) }}</span>
                                            <span class="quantity-unit">{{ $unit }}</span>
                                        </div>
                                    </td>

                                    <!-- Status Pill -->
                                    <td>
                                        <span class="badge-status-pill {{ $statusClass }}">
                                            @if($statusClass === 'success')
                                                <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                            @elseif($statusClass === 'warning')
                                                <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                                            @else
                                                <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            @endif
                                            <span>{{ $statusText }}</span>
                                        </span>
                                    </td>

                                    <!-- Unit Cost / Valuation -->
                                    <td style="text-align: right;">
                                        <div style="font-weight: 600; color: var(--text-primary); font-family: var(--font-mono); font-size: 0.8125rem;">
                                            ₱{{ number_format($unitCost, 2) }}
                                        </div>
                                        <div style="font-size: 0.7188rem; color: var(--text-muted); font-family: var(--font-mono);">
                                            Tot: ₱{{ number_format($qty * $unitCost, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                                </svg>
                                            </div>
                                            <h3>No Office Inventory Recorded</h3>
                                            <p>There are currently no supply items or stock balances allocated to {{ $activeOffice ? $activeOffice->office_name : 'this location' }}.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Empty Filter Match Row -->
                            <tr id="emptySearchRow" style="display: none;">
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        </div>
                                        <h3>No Matching Stock Items</h3>
                                        <p>No inventory items match your search term or active category/status filters.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Panel: Staff Profile & Office Custody Info -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <!-- Staff Profile Card -->
                <div class="panel-box">
                    <div class="panel-heading-row" style="margin-bottom: 14px; padding-bottom: 10px;">
                        <h2 class="panel-title">Staff Profile</h2>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <div class="profile-card-item">
                            <strong>Personnel Name</strong>
                            <span>{{ $user->full_name }}</span>
                        </div>

                        <div class="profile-card-item">
                            <strong>Username</strong>
                            <span style="font-family: var(--font-mono); font-size: 0.8125rem;">&#64;{{ $user->username }}</span>
                        </div>

                        <div class="profile-card-item">
                            <strong>Designated Station</strong>
                            @if($user->office)
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 6px;">
                                    <span>{{ $user->office->office_name }}</span>
                                    <span class="office-code-tag">{{ $user->office->office_code }}</span>
                                </div>
                            @else
                                <span style="color: #b45309;">Floating Staff (Warehouse Pool)</span>
                            @endif
                        </div>

                        <div class="profile-card-item">
                            <strong>System Role</strong>
                            <span>{{ $user->account_level?->label() ?? 'Warehouse Staff' }}</span>
                        </div>

                        @if($user->email)
                            <div class="profile-card-item">
                                <strong>Official Email</strong>
                                <span style="font-size: 0.7813rem; word-break: break-all;">{{ $user->email }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Office Quick Actions & Roster -->
                <div class="panel-box">
                    <div class="panel-heading-row" style="margin-bottom: 14px; padding-bottom: 10px;">
                        <h2 class="panel-title">Custody Tools</h2>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="button" class="btn-outline-action" onclick="window.print()">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Office Stock Ledger</span>
                        </button>

                        <button 
                            type="button" 
                            class="btn-outline-action"
                            onclick="copyToClipboard(window.location.href, 'Direct link to office stock copied!')"
                        >
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            <span>Share Office Roster Link</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Staff Bottom Navigation Bar -->
    <x-staffNav />

    <!-- Notification Toast Element -->
    <div class="pgso-toast" id="pgsoToast">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toastMessage">Copied to clipboard</span>
    </div>

    <!-- Client-side Search and Filtering Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // DropdownSearch Office Switcher Listener
            const officeSelectInput = document.getElementById('staffOfficeSelect');
            if (officeSelectInput && !officeSelectInput.disabled) {
                officeSelectInput.addEventListener('change', function () {
                    if (this.value) {
                        const targetUrl = new URL(window.location.href);
                        targetUrl.searchParams.set('office_id', this.value);
                        window.location.href = targetUrl.toString();
                    }
                });
            }

            // Real-time table filter logic
            const searchInput = document.getElementById('inventorySearchInput');
            const searchClearBtn = document.getElementById('searchClearBtn');
            const categoryFilterInput = document.getElementById('categoryFilterSelect');
            const statusFilterInput = document.getElementById('statusFilterSelect');
            const counterPill = document.getElementById('inventoryCounterPill');
            const rows = document.querySelectorAll('.inventory-row');
            const emptySearchRow = document.getElementById('emptySearchRow');
            const totalCount = rows.length;

            function filterTableRows() {
                const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
                const selectedCat = categoryFilterInput ? categoryFilterInput.value.trim().toLowerCase() : '';
                const selectedStatus = statusFilterInput ? statusFilterInput.value.trim() : '';

                if (searchClearBtn) {
                    searchClearBtn.classList.toggle('visible', query.length > 0);
                }

                let visibleCount = 0;

                rows.forEach(row => {
                    const code = row.getAttribute('data-code') || '';
                    const name = row.getAttribute('data-name') || '';
                    const cat = row.getAttribute('data-category') || '';
                    const loc = row.getAttribute('data-location') || '';
                    const status = row.getAttribute('data-status') || '';

                    const matchesSearch = query === '' 
                        || code.includes(query) 
                        || name.includes(query) 
                        || cat.includes(query) 
                        || loc.includes(query);

                    const matchesCat = selectedCat === '' || cat === selectedCat;
                    const matchesStatus = selectedStatus === '' || status === selectedStatus;

                    if (matchesSearch && matchesCat && matchesStatus) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptySearchRow) {
                    emptySearchRow.style.display = (visibleCount === 0 && totalCount > 0) ? '' : 'none';
                }

                if (counterPill) {
                    counterPill.textContent = `Showing ${visibleCount} of ${totalCount} items`;
                }
            }

            if (searchInput) searchInput.addEventListener('input', filterTableRows);
            if (categoryFilterInput) categoryFilterInput.addEventListener('change', filterTableRows);
            if (statusFilterInput) statusFilterInput.addEventListener('change', filterTableRows);

            if (searchClearBtn) {
                searchClearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    filterTableRows();
                    searchInput.focus();
                });
            }
        });

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
