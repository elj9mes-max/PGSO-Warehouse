<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Create Requisition</title>

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
            --accent-emerald: #059669;
            --accent-emerald-light: #ecfdf5;
            --accent-blue: #2563eb;
            --accent-blue-light: #eff6ff;
            --accent-indigo: #4f46e5;
            --accent-indigo-light: #eef2ff;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
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
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .user-name-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .role-pill {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        /* Main Center Wrapper */
        .page-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 24px 120px;
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }

        /* Header Section */
        .portal-header {
            text-align: center;
            margin-bottom: 36px;
            max-width: 640px;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 9999px;
            padding: 5px 14px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .portal-badge svg {
            color: var(--accent-blue);
        }

        .portal-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #09090b;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .portal-desc {
            font-size: 0.9375rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .office-station-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        /* 2 Cards Grid in the Middle */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
            width: 100%;
            max-width: 960px;
            margin-bottom: 32px;
        }

        /* Requisition Card Base */
        .req-card {
            background: #ffffff;
            border: 1.5px solid var(--border-subtle);
            border-radius: 20px;
            padding: 32px 28px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .req-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: all 0.28s ease;
        }

        /* RIS Card Customization (Emerald) */
        .req-card.card-ris::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .req-card.card-ris:hover {
            border-color: #a7f3d0;
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(5, 150, 105, 0.08), 0 8px 10px -6px rgba(5, 150, 105, 0.05);
        }

        .req-card.card-ris:hover .btn-action {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .req-card.card-ris:hover .btn-action svg {
            transform: translateX(4px);
        }

        .req-card.card-ris .icon-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .req-card.card-ris .type-pill {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        /* PR Card Customization (Indigo/Blue) */
        .req-card.card-pr::before {
            background: linear-gradient(90deg, #3b82f6, #4f46e5);
        }

        .req-card.card-pr:hover {
            border-color: #bfdbfe;
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.08), 0 8px 10px -6px rgba(37, 99, 235, 0.05);
        }

        .req-card.card-pr:hover .btn-action {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .req-card.card-pr:hover .btn-action svg {
            transform: translateX(4px);
        }

        .req-card.card-pr .icon-box {
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .req-card.card-pr .type-pill {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        /* Card Header & Content */
        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }

        .icon-box svg {
            width: 28px;
            height: 28px;
        }

        .type-pill {
            font-size: 0.7188rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .type-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .form-code {
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.02em;
            margin-bottom: 6px;
            display: block;
        }

        .card-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: #09090b;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
            line-height: 1.25;
        }

        .card-tagline {
            font-size: 0.8438rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 14px;
        }

        .card-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 22px;
        }

        /* Key Feature Checklist */
        .feature-list {
            list-style: none;
            padding: 0;
            margin-bottom: 26px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-item {
            font-size: 0.8125rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 9px;
            font-weight: 500;
        }

        .feature-check {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-ris .feature-check {
            background-color: #ecfdf5;
            color: #059669;
        }

        .card-pr .feature-check {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .feature-check svg {
            width: 11px;
            height: 11px;
        }

        /* Card Action Button */
        .btn-action {
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #f8fafc;
            border: 1.5px solid var(--border-subtle);
            color: var(--text-primary);
            transition: all 0.22s ease;
        }

        .btn-action svg {
            width: 16px;
            height: 16px;
            transition: transform 0.22s ease;
        }

        /* Decision Helper Banner */
        .helper-banner {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .helper-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .helper-icon svg {
            width: 18px;
            height: 18px;
        }

        .helper-text {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .helper-text strong {
            color: var(--text-primary);
        }

        /* Responsive Layout */
        @media (max-width: 820px) {
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .portal-title {
                font-size: 1.625rem;
            }

            .page-wrapper {
                padding: 24px 16px 120px;
            }

            .helper-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    @php
        $user = auth()->user()->load('office');
        $userInitials = strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) ?: 'ST';
    @endphp

    <!-- Staff Topbar Header -->
    <header class="staff-topbar">
        <a href="{{ route('staff.inventory') }}" class="brand-group" title="Back to Office Inventory">
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
        </a>

        <div class="user-pill">
            <div class="user-avatar">{{ $userInitials }}</div>
            <span class="user-name-label">{{ $user->full_name }}</span>
            <span class="role-pill">{{ $user->account_level?->label() ?? 'Staff' }}</span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="page-wrapper">

        <!-- Header Introduction -->
        <div class="portal-header">
            <div class="portal-badge">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span>Requisition & Issuance Portal</span>
            </div>

            <h1 class="portal-title">Create a Requisition</h1>
            <p class="portal-desc">Choose the type of request you wish to submit. Select between an in-stock Requisition and Issue Slip (RIS) or a new procurement Purchase Request (PR).</p>

            @if($user->office)
                <div class="office-station-tag">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 7v14M21 7v14M6 11h2M6 15h2M11 11h2M11 15h2M16 11h2M16 15h2M9 3h6v4H9z"/></svg>
                    <span>{{ $user->office->office_name }} ({{ $user->office->office_code }})</span>
                </div>
            @endif
        </div>

        <!-- 2 Selection Cards in the Center -->
        <div class="cards-grid">

            <!-- Card 1: Requisition and Issue Slip (RIS) -->
            <a href="{{ route('staff.requisition.ris') }}" class="req-card card-ris" id="selectRisCard">
                <div>
                    <div class="card-top">
                        <div class="icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                <path d="m3.3 7 8.7 5 8.7-5"/>
                                <path d="M12 22V12"/>
                                <circle cx="18" cy="18" r="3"/>
                                <path d="m17 18 1 1 2-2"/>
                            </svg>
                        </div>
                        <span class="type-pill">In-Stock Issuance</span>
                    </div>

                    <span class="form-code">FORM APPENDIX 63 • LGU GAM</span>
                    <h2 class="card-title">Requisition & Issue Slip (RIS)</h2>
                    <p class="card-tagline">Request available supplies & items currently held in warehouse custody</p>
                    <p class="card-desc">
                        Select supplies, stationery, consumables, or equipment from available warehouse inventory. Approved items are dispatched directly to your office custody.
                    </p>

                    <ul class="feature-list">
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>Direct issuance from active warehouse stock</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>Immediate fulfillment & property custodian release</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>Transfers custody balance to your designated office</span>
                        </li>
                    </ul>
                </div>

                <div class="btn-action">
                    <span>Create RIS Request</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>

            <!-- Card 2: Purchase Request (PR) -->
            <a href="{{ route('staff.requisition.pr') }}" class="req-card card-pr" id="selectPrCard">
                <div>
                    <div class="card-top">
                        <div class="icon-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                        </div>
                        <span class="type-pill">New Procurement</span>
                    </div>

                    <span class="form-code">FORM APPENDIX 60 • LGU GAM</span>
                    <h2 class="card-title">Purchase Request (PR)</h2>
                    <p class="card-tagline">Request procurement for non-stock items, supplies, or new equipment</p>
                    <p class="card-desc">
                        Submit a purchase requisition for items that are out-of-stock or not carried in regular warehouse inventory. Initiates formal canvass, budget allocation, and Purchase Order.
                    </p>

                    <ul class="feature-list">
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>For unstocked items or new specifications</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>Routes to Budget Office & Bids & Awards (BAC)</span>
                        </li>
                        <li class="feature-item">
                            <span class="feature-check">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span>Generates official Purchase Order (PO) upon approval</span>
                        </li>
                    </ul>
                </div>

                <div class="btn-action">
                    <span>Create PR Request</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </a>

        </div>

        <!-- Decision Helper Info Strip -->
        <div class="helper-banner">
            <div class="helper-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <div class="helper-text">
                <strong>Need help deciding?</strong> If your requested supplies or stationery are already carried and available in the warehouse, choose <strong>RIS</strong> for fast issuance. If the supplies must be purchased from outside suppliers or require budget canvassing, choose <strong>PR</strong>.
            </div>
        </div>

    </main>

    <!-- Floating Bottom Navigation -->
    <x-staffNav />

</body>
</html>
