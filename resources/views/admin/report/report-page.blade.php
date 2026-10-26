<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Audit & Reports Hub</title>
    
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
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;
            --border-focus: #0f172a;
            --text-main: #09090b;
            --text-muted: #64748b;
            --text-sub: #94a3b8;
            --primary: #0f172a;
            --primary-hover: #1e293b;
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --blue-500: #3b82f6;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --amber-50: #fffbeb;
            --amber-100: #fef3c7;
            --amber-500: #f59e0b;
            --amber-600: #d97706;
            --purple-50: #faf5ff;
            --purple-100: #f3e8ff;
            --purple-500: #a855f7;
            --purple-600: #7c3aed;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-card: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.03);
            --shadow-hover: 0 12px 28px -4px rgba(15, 23, 42, 0.08), 0 6px 16px -2px rgba(15, 23, 42, 0.04);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .reports-content-area {
            padding: 36px 44px;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Flash Message */
        .flash-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background-color: var(--emerald-50);
            border: 1px solid var(--emerald-100);
            color: var(--emerald-700);
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            animation: slideInDown 0.3s ease;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Section */
        .reports-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 32px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .role-badge-reports {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background-color: #ffffff;
            color: #334155;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-sm);
        }

        .live-pulse-dot {
            width: 7px;
            height: 7px;
            background-color: var(--emerald-600);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.5); }
            70% { box-shadow: 0 0 0 6px rgba(5, 150, 105, 0); }
            100% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0); }
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-main);
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.9375rem;
            color: var(--text-muted);
            max-width: 720px;
            line-height: 1.5;
        }

        /* KPI Quick Facts Banner */
        .reports-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 36px;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            border-color: var(--border-strong);
            box-shadow: var(--shadow-hover);
        }

        .kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .kpi-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .kpi-icon-pill {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .kpi-icon-pill.emerald { background-color: var(--emerald-50); color: var(--emerald-600); }
        .kpi-icon-pill.blue { background-color: var(--blue-50); color: var(--blue-600); }
        .kpi-icon-pill.purple { background-color: var(--purple-50); color: var(--purple-600); }
        .kpi-icon-pill.amber { background-color: var(--amber-50); color: var(--amber-600); }

        .kpi-value {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-main);
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .kpi-subtext {
            font-size: 0.7813rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ------------------------------------------------------------------
           REPORT OPTIONS HUB: 2x2 CARDS GRID
        ------------------------------------------------------------------ */
        .section-label {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--border-subtle);
        }

        .report-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .report-option-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            color: inherit;
        }

        .report-option-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -4px rgba(15, 23, 42, 0.08), 0 4px 6px -2px rgba(15, 23, 42, 0.03);
            border-color: #cbd5e1;
        }

        /* Top colored accent line */
        .report-option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            transition: height 0.2s ease;
        }

        .report-option-card:hover::before {
            height: 4px;
        }

        .report-option-card.card-stock-card::before { background: linear-gradient(90deg, #059669, #10b981); }
        .report-option-card.card-logs::before { background: linear-gradient(90deg, #2563eb, #3b82f6); }
        .report-option-card.card-office-stock::before { background: linear-gradient(90deg, #7c3aed, #a855f7); }
        .report-option-card.card-ssmi::before { background: linear-gradient(90deg, #d97706, #f59e0b); }

        .report-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            gap: 10px;
        }

        .report-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .report-option-card:hover .report-icon-box {
            transform: scale(1.05);
        }

        .report-icon-box.emerald { background-color: var(--emerald-50); color: var(--emerald-600); border: 1px solid var(--emerald-100); }
        .report-icon-box.blue { background-color: var(--blue-50); color: var(--blue-600); border: 1px solid var(--blue-100); }
        .report-icon-box.purple { background-color: var(--purple-50); color: var(--purple-600); border: 1px solid var(--purple-100); }
        .report-icon-box.amber { background-color: var(--amber-50); color: var(--amber-600); border: 1px solid var(--amber-100); }

        .report-standard-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2.5px 7px;
            border-radius: 9999px;
            font-size: 0.625rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .badge-appendix-58 { background-color: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
        .badge-logs { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .badge-office { background-color: #faf5ff; color: #6b21a8; border: 1px solid #f3e8ff; }
        .badge-appendix-64 { background-color: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }

        .report-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.015em;
            color: var(--text-main);
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .report-card-desc {
            font-size: 0.775rem;
            color: var(--text-muted);
            line-height: 1.45;
            margin-bottom: 12px;
            min-height: 34px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Feature points */
        .report-feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 14px;
            padding: 8px 10px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid #f1f5f9;
        }

        .report-feature-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            color: #475569;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .report-feature-item svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
            color: var(--emerald-600);
        }

        .report-feature-item.blue svg { color: var(--blue-600); }
        .report-feature-item.purple svg { color: var(--purple-600); }
        .report-feature-item.amber svg { color: var(--amber-600); }

        /* Card Action Row */
        .report-card-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            margin-top: auto;
        }

        .card-stat-pill {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .card-stat-pill strong {
            color: var(--text-main);
            font-weight: 700;
        }

        .btn-open-report {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background-color: var(--primary);
            color: #ffffff;
            border-radius: 7px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--primary);
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
            white-space: nowrap;
        }

        .btn-open-report svg {
            width: 12px;
            height: 12px;
            transition: transform 0.15s ease;
        }

        .report-option-card:hover .btn-open-report svg {
            transform: translateX(2px);
        }

        .btn-open-report:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 2px 5px rgba(15, 23, 42, 0.12);
        }

        /* Help & Quick Nav Footer Banner */
        .reports-support-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 28px;
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            gap: 20px;
            flex-wrap: wrap;
        }

        .support-banner-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .support-banner-icon {
            width: 44px;
            height: 44px;
            background-color: #f1f5f9;
            color: #0f172a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .support-banner-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .support-banner-desc {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .support-banner-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-sub-link {
            padding: 8px 14px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-sub-link:hover {
            background: #f1f5f9;
            color: #09090b;
            border-color: #cbd5e1;
        }

        @media (max-width: 1200px) {
            .reports-content-area {
                padding: 28px 24px;
            }

            .report-cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
        }

        @media (max-width: 640px) {
            .reports-content-area {
                padding: 20px 16px;
            }

            .report-cards-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .report-option-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <x-sidebar>
        <div class="reports-content-area">

            <!-- Flash Alert -->
            @if(session('status'))
                <div class="flash-alert">
                    <svg style="width: 18px; height: 18px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Page Header -->
            <div class="reports-header">
                <div>
                    <span class="role-badge-reports">
                        <span class="live-pulse-dot"></span>
                        Administrator Portal • Reporting Hub
                    </span>
                    <h1 class="page-title">Warehouse & Custody Reports Center</h1>
                    <p class="page-subtitle">Select an official reporting module below to review inventory stock cards, audit transaction logs, provincial office custody balances, and Summaries of Supplies & Materials Issued (SSMI).</p>
                </div>
            </div>



            <div class="section-label">Available Report Documents</div>

            <!-- ===================================================================
                 REPORT OPTIONS COMPACT 4-CARD GRID
            =================================================================== -->
            <div class="report-cards-grid">

                <!-- CARD 1: Stock Card -->
                <div class="report-option-card card-stock-card">
                    <div>
                        <div class="report-card-top">
                            <div class="report-icon-box emerald">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                    <line x1="6" y1="8" x2="18" y2="8"/>
                                    <line x1="6" y1="12" x2="14" y2="12"/>
                                </svg>
                            </div>
                            <span class="report-standard-badge badge-appendix-58">Appendix 58 • GAM</span>
                        </div>

                        <h2 class="report-card-title">Stock Card Ledger</h2>
                        <p class="report-card-desc">
                            Individual stock inventory ledger for every supply item variant. Displays beginning balances, incoming delivery receipts (IAR), dispatches, office issuances, and live running balance totals.
                        </p>

                        <div class="report-feature-list">
                            <div class="report-feature-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Receipts, issuances, & balance</span>
                            </div>
                            <div class="report-feature-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Official COA government standards</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card-action">
                        <div class="card-stat-pill">
                            <span>Catalog:</span>
                            <strong>{{ number_format($variants->count()) }} Variants</strong>
                        </div>
                        <a href="{{ Route::has('admin.reports.stock-card') ? route('admin.reports.stock-card') : (Route::has('reports.stock-card') ? route('reports.stock-card') : url('/admin/reports/stock-card')) }}" class="btn-open-report">
                            <span>Open Stock Card</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>


                <!-- CARD 4: SSMI (Summary of Supplies & Materials Issued) -->
                <div class="report-option-card card-ssmi">
                    <div>
                        <div class="report-card-top">
                            <div class="report-icon-box amber">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                            </div>
                            <span class="report-standard-badge badge-appendix-64">Appendix 64 • GAM</span>
                        </div>

                        <h2 class="report-card-title">SSMI (Summary of Supplies Issued)</h2>
                        <p class="report-card-desc">
                            Official government monthly summary report of all Supplies and Materials Issued (SSMI) based on approved Requisition and Issue Slips (RIS). Essential for month-end accounting liquidation and auditing.
                        </p>

                        <div class="report-feature-list">
                            <div class="report-feature-item amber">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Government Accounting Manual (GAM) Appendix 64 format</span>
                            </div>
                            <div class="report-feature-item amber">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Responsibility Center Code & RIS billing</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card-action">
                        <div class="card-stat-pill">
                            <span>Total Issued:</span>
                            <strong>{{ number_format($totalReleasesUnits) }} Units</strong>
                        </div>
                        <a href="{{ Route::has('admin.reports.ssmi') ? route('admin.reports.ssmi') : (Route::has('reports.ssmi') ? route('reports.ssmi') : url('/admin/reports/ssmi')) }}" class="btn-open-report">
                            <span>Open SSMI Report</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>

                <!-- CARD 2: Transaction & Audit Logs -->
                <div class="report-option-card card-logs">
                    <div>
                        <div class="report-card-top">
                            <div class="report-icon-box blue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <span class="report-standard-badge badge-logs">Live Audit Trail</span>
                        </div>

                        <h2 class="report-card-title">Transaction & Audit Logs</h2>
                        <p class="report-card-desc">
                            Complete chronological audit trail recording deliveries, office dispatches, issuances, and warehouse adjustments with actor stamps.
                        </p>

                        <div class="report-feature-list">
                            <div class="report-feature-item blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Tracks user, timestamp & reference code</span>
                            </div>
                            <div class="report-feature-item blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Filter by action, item & date range</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card-action">
                        <div class="card-stat-pill">
                            <span>Recorded:</span>
                            <strong>{{ number_format($totalLogsCount) }} Logs</strong>
                        </div>
                        <a href="{{ route('admin.inventory') }}" class="btn-open-report">
                            <span>View Audit Logs</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>

                <!-- CARD 3: Office Stock Report -->
                <div class="report-option-card card-office-stock">
                    <div>
                        <div class="report-card-top">
                            <div class="report-icon-box purple">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18"/>
                                    <path d="M5 21V7l8-4v18"/>
                                    <path d="M19 21V11l-6-4"/>
                                    <line x1="9" y1="9" x2="9" y2="9.01"/>
                                    <line x1="9" y1="13" x2="9" y2="13.01"/>
                                    <line x1="9" y1="17" x2="9" y2="17.01"/>
                                </svg>
                            </div>
                            <span class="report-standard-badge badge-office">Office Custody</span>
                        </div>

                        <h2 class="report-card-title">Office Stock Report</h2>
                        <p class="report-card-desc">
                            Inventory stock levels currently distributed and physically held across all provincial government offices and operational units.
                        </p>

                        <div class="report-feature-list">
                            <div class="report-feature-item purple">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Active custody in {{ $totalOfficesCount }} provincial offices</span>
                            </div>
                            <div class="report-feature-item purple">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Department accountability & holdings</span>
                            </div>
                        </div>
                    </div>

                    <div class="report-card-action">
                        <div class="card-stat-pill">
                            <span>Custody:</span>
                            <strong>{{ number_format($totalOfficeUnits) }} Units</strong>
                        </div>
                        <a href="{{ route('admin.offices.index') }}" class="btn-open-report">
                            <span>View Office Stock</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>


            </div>

            <!-- Quick Navigation Footer -->
            <div class="reports-support-banner">
                <div class="support-banner-left">
                    <div class="support-banner-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <div>
                        <div class="support-banner-title">Need to initiate a new inventory transaction?</div>
                        <div class="support-banner-desc">You can directly record supplier deliveries, dispatch office transfers, or release supplies from the central warehouse.</div>
                    </div>
                </div>

                <div class="support-banner-actions">
                    <a href="{{ route('admin.transfers.index') }}" class="btn-sub-link">Transfer Stocks</a>
                    <a href="{{ route('admin.releases.index') }}" class="btn-sub-link">Release Stocks</a>
                    <a href="{{ route('admin.inventory') }}" class="btn-sub-link">Manage Inventory</a>
                </div>
            </div>

        </div>
    </x-sidebar>

</body>
</html>
