<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Admin Dashboard & Release Calendar</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            --border-focus: #0f172a;
            --text-main: #09090b;
            --text-muted: #64748b;
            --text-sub: #94a3b8;
            --primary: #0f172a;
            --primary-hover: #1e293b;
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --blue-50: #eff6ff;
            --blue-100: #dbeafe;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --amber-50: #fffbeb;
            --amber-100: #fef3c7;
            --amber-600: #d97706;
            --purple-50: #faf5ff;
            --purple-100: #f3e8ff;
            --purple-600: #9333ea;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .dashboard-content-area {
            padding: 32px 40px;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Header */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .role-badge-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background-color: #09090b;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 8px;
        }

        .live-pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-main);
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 6px;
            max-width: 650px;
        }

        /* Header Quick Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #09090b;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #09090b;
        }

        .btn-action-primary:hover {
            background-color: #27272a;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }

        .btn-action-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #ffffff;
            color: var(--text-main);
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border-subtle);
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .btn-action-secondary:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        /* Flash Message */
        .flash-alert {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        /* Full-Width Office Request Reminder Alert */
        .pending-office-request-alert {
            width: 100%;
            background: linear-gradient(135deg, #fffbeb 0%, #ffffff 45%);
            border: 1px solid #fde68a;
            border-left: 5px solid #f59e0b;
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 16px -2px rgba(245, 158, 11, 0.12), 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
            position: relative;
            box-sizing: border-box;
            animation: alertFadeDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes alertFadeDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pending-office-request-alert.alert-dismissing {
            opacity: 0;
            transform: translateY(-8px);
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
            max-height: 0;
            overflow: hidden;
            border-width: 0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .request-alert-main {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            min-width: 0;
            flex: 1;
        }

        .request-alert-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background-color: #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(245, 158, 11, 0.2);
            position: relative;
        }

        .request-alert-icon-box svg {
            width: 22px;
            height: 22px;
        }

        .request-alert-beacon {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background-color: #f59e0b;
            border: 2px solid #ffffff;
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            animation: pulse-ring 2s infinite;
        }

        .request-alert-body {
            min-width: 0;
            flex: 1;
        }

        .request-alert-top-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }

        .request-alert-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.6875rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #92400e;
            background-color: #fef3c7;
            border: 1px solid #fde68a;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        .request-alert-time {
            font-size: 0.75rem;
            color: #71717a;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .request-alert-title {
            font-size: 1rem;
            font-weight: 700;
            color: #09090b;
            line-height: 1.35;
            letter-spacing: -0.015em;
            margin: 0;
        }

        .request-alert-title strong {
            color: #b45309;
            font-weight: 800;
        }

        .request-alert-message {
            font-size: 0.8125rem;
            color: #52525b;
            line-height: 1.45;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .request-alert-details-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 8px;
            font-size: 0.75rem;
            color: #71717a;
        }

        .request-alert-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            border: 1px solid #e4e4e7;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
            color: #3f3f46;
        }

        .request-alert-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-alert-review {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #09090b;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border: 1px solid #09090b;
            white-space: nowrap;
        }

        .btn-alert-review:hover {
            background-color: #27272a;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-alert-dismiss {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #71717a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
            padding: 0;
        }

        .btn-alert-dismiss:hover {
            background-color: #f4f4f5;
            color: #09090b;
            border-color: #cbd5e1;
        }

        @media (max-width: 840px) {
            .pending-office-request-alert {
                flex-direction: column;
                align-items: stretch;
                padding: 16px;
            }
            .request-alert-actions {
                width: 100%;
                justify-content: space-between;
                margin-top: 6px;
            }
            .btn-alert-review {
                flex: 1;
                justify-content: center;
            }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 20px 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #334155;
        }

        .stat-icon-badge.emerald {
            background: var(--emerald-50);
            color: var(--emerald-600);
        }

        .stat-icon-badge.blue {
            background: var(--blue-50);
            color: var(--blue-600);
        }

        .stat-icon-badge.purple {
            background: var(--purple-50);
            color: var(--purple-600);
        }

        .stat-number {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-sub {
            font-size: 0.7813rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Calendar Section Container */
        .calendar-section {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            margin-bottom: 32px;
            overflow: visible;
            position: relative;
        }

        /* Calendar Section Header & Toolbar */
        .calendar-toolbar {
            padding: 22px 28px;
            border-bottom: 1px solid var(--border-subtle);
            background: #ffffff;
            border-radius: 18px 18px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 18px;
            position: relative;
            z-index: 30;
        }

        .calendar-title-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .current-month-heading {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            min-width: 170px;
        }

        .month-nav-btn-group {
            display: inline-flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 3px;
            gap: 2px;
        }

        .month-nav-btn {
            background: transparent;
            border: none;
            padding: 6px 12px;
            border-radius: 7px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .month-nav-btn:hover {
            background: #ffffff;
            color: var(--text-main);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .month-nav-btn.today-btn {
            padding: 6px 14px;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .calendar-filters-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .calendar-office-dropdown-container {
            width: 600px;
        }

        .calendar-office-dropdown-container .pgso-dropdown-search-wrapper {
            position: relative;
        }

        .calendar-office-dropdown-container .pgso-ds-trigger {
            height: 38px;
            min-height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 600;
            border: 1px solid var(--border-subtle);
            background-color: #ffffff;
            color: var(--text-main);
        }

        .calendar-office-dropdown-container .pgso-ds-selected-label,
        .calendar-office-dropdown-container .pgso-ds-placeholder {
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .calendar-office-dropdown-container .pgso-ds-menu {
            min-width: 280px;
            z-index: 1050;
        }

        .calendar-search-wrapper {
            position: relative;
        }

        .calendar-search-wrapper svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            color: #94a3b8;
        }

        .calendar-search-input {
            height: 38px;
            padding: 8px 14px 8px 34px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.8125rem;
            color: var(--text-main);
            width: 240px;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }

        .calendar-search-input:focus {
            border-color: #09090b;
            width: 270px;
        }

        /* Calendar Workspace Grid & Inspector Layout */
        .calendar-workspace {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 390px;
            background: #ffffff;
            min-height: 640px;
            border-radius: 0 0 18px 18px;
            overflow: hidden;
        }

        /* Left Big Calendar Grid */
        .calendar-grid-column {
            padding: 24px;
            border-right: 1px solid var(--border-subtle);
            overflow-x: auto;
        }

        /* Weekday Headers */
        .calendar-weekdays-row {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 8px;
        }

        .weekday-cell {
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 4px;
        }

        .weekday-cell.weekend {
            color: #94a3b8;
        }

        /* Day Cells Grid */
        .calendar-days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .day-cell {
            height: 84px;
            min-height: 84px;
            max-height: 84px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
            box-sizing: border-box;
            user-select: none;
        }

        .day-cell:hover {
            border-color: #94a3b8;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
            z-index: 2;
        }

        .day-cell.other-month {
            background: #fafafa;
            opacity: 0.35;
            cursor: default;
        }

        .day-cell.other-month:hover {
            transform: none;
            box-shadow: none;
            border-color: var(--border-subtle);
        }

        .day-cell.is-today {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .day-cell.is-selected {
            border-color: #09090b !important;
            box-shadow: 0 0 0 2px #09090b, 0 4px 12px rgba(0, 0, 0, 0.08) !important;
            background-color: #ffffff;
            z-index: 3;
        }

        .day-cell.has-releases {
            background-color: #fbfdfc;
            border-color: #bbf7d0;
        }

        .day-cell.has-releases:hover {
            border-color: #86efac;
        }

        .day-cell.has-releases.is-selected {
            border-color: #09090b !important;
        }

        .day-cell-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .day-number {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #334155;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .day-cell.is-today .day-number {
            background: #09090b;
            color: #ffffff;
            font-weight: 800;
        }

        .day-cell.is-selected .day-number {
            font-weight: 800;
        }

        .day-cell-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
        }

        /* Number Badge on Day Cell */
        .day-release-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7188rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            background: var(--emerald-50);
            color: var(--emerald-700);
            border: 1px solid #a7f3d0;
            transition: all 0.15s ease;
            line-height: 1.2;
        }

        .day-release-count-badge .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            flex-shrink: 0;
        }

        .day-cell:hover .day-release-count-badge {
            background: var(--emerald-100);
            border-color: #6ee7b7;
        }

        .day-release-units-sub {
            font-size: 0.6563rem;
            font-weight: 600;
            color: #64748b;
        }

        /* Right Column: Day Release Inspector Panel */
        .calendar-inspector-column {
            background: #fafafa;
            padding: 26px 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            position: relative;
        }

        .inspector-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .inspector-date-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .inspector-date-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .inspector-summary-pill-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }

        .inspector-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.7188rem;
            font-weight: 700;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            color: var(--text-main);
        }

        .inspector-badge.highlight {
            background: var(--emerald-50);
            border-color: #a7f3d0;
            color: var(--emerald-700);
        }

        /* Inspector Releases Scroll Area */
        .inspector-releases-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            max-height: 520px;
            padding-right: 2px;
        }

        .inspector-release-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.15s ease;
        }

        .inspector-release-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
        }

        .card-meta-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .ref-number-tag {
            font-size: 0.6875rem;
            font-weight: 800;
            color: #0f172a;
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 5px;
            letter-spacing: 0.02em;
        }

        .release-time-tag {
            font-size: 0.7188rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .card-item-title {
            font-size: 0.9063rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .card-variant-title {
            font-size: 0.7813rem;
            color: #475569;
            margin-bottom: 10px;
        }

        .card-dispatch-metrics {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card-qty-badge {
            font-size: 0.8125rem;
            font-weight: 800;
            color: var(--emerald-700);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .card-office-text {
            font-size: 0.7188rem;
            font-weight: 600;
            color: #334155;
            text-align: right;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-details-grid {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 0.75rem;
            color: #475569;
            border-top: 1px dashed var(--border-subtle);
            padding-top: 8px;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .detail-row svg {
            width: 13px;
            height: 13px;
            color: #64748b;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .detail-label {
            font-weight: 600;
            color: #334155;
        }

        /* Empty Inspector State */
        .inspector-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 48px 16px;
            color: var(--text-muted);
            height: 100%;
        }

        .inspector-empty-state svg {
            width: 44px;
            height: 44px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .inspector-empty-state h4 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .inspector-empty-state p {
            font-size: 0.7813rem;
            line-height: 1.4;
            max-width: 240px;
            margin-bottom: 16px;
        }

        .btn-stage-for-date {
            font-size: 0.7813rem;
            font-weight: 700;
            background: #ffffff;
            color: #0f172a;
            border: 1px solid var(--border-subtle);
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-stage-for-date:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* Bottom Section: Recent Releases Stream Table */
        .recent-releases-box {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            margin-bottom: 32px;
        }

        .recent-releases-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .recent-releases-title {
            font-size: 1.125rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .saas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8438rem;
        }

        .saas-table th {
            text-align: left;
            font-weight: 700;
            color: #64748b;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-subtle);
            font-size: 0.7188rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #fafafa;
        }

        .saas-table td {
            padding: 14px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
            vertical-align: middle;
        }

        .saas-table tr:hover td {
            background-color: #f8fafc;
        }

        .btn-jump-to-date {
            background: transparent;
            border: 1px solid var(--border-subtle);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.7188rem;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-jump-to-date:hover {
            background: #09090b;
            color: #ffffff;
            border-color: #09090b;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .calendar-workspace {
                grid-template-columns: 1fr;
            }
            .calendar-grid-column {
                border-right: none;
                border-bottom: 1px solid var(--border-subtle);
            }
        }

        @media (max-width: 768px) {
            .dashboard-content-area {
                padding: 20px 16px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .day-cell {
                height: 72px;
                min-height: 72px;
                max-height: 72px;
                padding: 6px 8px;
            }
            .calendar-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .calendar-office-dropdown-container,
            .calendar-office-dropdown-container .pgso-dropdown-search-wrapper,
            .calendar-search-wrapper,
            .calendar-search-input {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    @php
        $calendarOfficeOptions = collect([
            ['id' => '', 'name' => 'All Custody Offices', 'code' => 'ALL'],
        ])->concat($offices->map(fn($o) => [
            'id' => (string) $o->id,
            'name' => $o->office_name,
            'code' => $o->office_code,
        ]))->toArray();
    @endphp

    <x-sidebar>
        <div class="dashboard-content-area">

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

       

            <!-- Admin Header -->
            <div class="admin-header">
                <div>
                    <span class="role-badge-admin">
                        <span class="live-pulse-dot"></span>
                        Administrator Portal
                    </span>
                    <h1 class="page-title">Warehouse Administration & Releases</h1>
                    <p class="page-subtitle">Welcome back, {{ auth()->user()->full_name }}. Monitor warehouse stock holdings, custody dispatches, and schedule releases across provincial offices.</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('admin.releases.index') }}" class="btn-action-primary">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Stage Stock Release</span>
                    </a>

                    <a href="{{ route('admin.transfers.index') }}" class="btn-action-secondary">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 16V4M7 4L3 8M7 4L11 8M17 8V20M17 20L21 16M17 20L13 16"/>
                        </svg>
                        <span>Transfer Stock</span>
                    </a>

                    <a href="{{ route('admin.inventory') }}" class="btn-action-secondary">
                        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        <span>Inventory Catalog</span>
                    </a>
                </div>
            </div>
                 <!-- Full Width Alert for Pending Office Requisition Requests -->
            @php
                $pendingOfficeRequests = $pendingOfficeRequests ?? \App\Models\RequisitionRequest::with(['office', 'user', 'items'])->where('status', 'pending')->latest()->get();
                $pendingOfficeRequestsCount = $pendingOfficeRequestsCount ?? $pendingOfficeRequests->count();
                $latestOfficeRequest = $pendingOfficeRequests->first();
            @endphp

            @if($pendingOfficeRequestsCount > 0 && $latestOfficeRequest)
                <div class="pending-office-request-alert" id="pendingOfficeRequestsAlert" role="alert">
                    <div class="request-alert-main">
                        <div class="request-alert-icon-box" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 8 9"/>
                            </svg>
                            <span class="request-alert-beacon"></span>
                        </div>

                        <div class="request-alert-body">
                            <div class="request-alert-top-row">
                                <span class="request-alert-badge">
                                    <span class="live-pulse-dot" style="background-color: #f59e0b; width: 6px; height: 6px;"></span>
                                    Warehouse Reminder &bull; Action Required
                                </span>
                                @if($latestOfficeRequest->created_at)
                                    <span class="request-alert-time">
                                        <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        {{ $latestOfficeRequest->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="request-alert-title">
                                @if($pendingOfficeRequestsCount === 1)
                                    Requisition request sent by <strong>{{ $latestOfficeRequest->office?->office_name ?? ($latestOfficeRequest->requested_by_name ?? 'an Office') }}</strong>
                                @else
                                    <strong>{{ $pendingOfficeRequestsCount }} requisition requests</strong> sent by offices awaiting warehouse review
                                @endif
                            </h3>

                            {{-- <p class="request-alert-message">
                                @if($pendingOfficeRequestsCount === 1)
                                    The warehouse has received request <strong>#{{ $latestOfficeRequest->request_number }}</strong> ({{ $latestOfficeRequest->request_type }} &bull; {{ $latestOfficeRequest->items->count() }} {{ \Illuminate\Support\Str::plural('item', $latestOfficeRequest->items->count()) }}). Please take a look at the requisition to inspect requested quantities and issue stocks.
                                @else
                                    Offices have submitted pending supply requests that require warehouse attention. Latest: <strong>#{{ $latestOfficeRequest->request_number }}</strong> from <strong>{{ $latestOfficeRequest->office?->office_name ?? 'an Office' }}</strong>. Please take a look to review allocations and schedule stock releases.
                                @endif
                            </p> --}}

                            <div class="request-alert-details-row">
                                <span class="request-alert-chip">
                                    <svg style="width: 12px; height: 12px; color: #f59e0b;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    Office: {{ $latestOfficeRequest->office?->office_name ?? ($latestOfficeRequest->office?->office_code ?? 'Office') }}
                                </span>
                                <span class="request-alert-chip">
                                    <svg style="width: 12px; height: 12px; color: #64748b;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    Requested by: {{ $latestOfficeRequest->requested_by_name ?? ($latestOfficeRequest->user?->full_name ?? 'Staff') }}
                                </span>
                                <span class="request-alert-chip">
                                    <svg style="width: 12px; height: 12px; color: #10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    Tracking: {{ $latestOfficeRequest->request_number }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="request-alert-actions">
                        <a href="{{ route('admin.requisitions.index') }}" class="btn-alert-review">
                            <span>Take a Look at Request</span>
                            <svg style="width: 15px; height: 15px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <button type="button" class="btn-alert-dismiss" onclick="dismissOfficeRequestAlert()" aria-label="Dismiss alert" title="Dismiss notification">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    function dismissOfficeRequestAlert() {
                        const alertEl = document.getElementById('pendingOfficeRequestsAlert');
                        if (alertEl) {
                            alertEl.classList.add('alert-dismissing');
                            setTimeout(function() {
                                alertEl.remove();
                            }, 300);
                        }
                    }
                </script>
            @endif

            <!-- Stats Overview Row -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Total Stock Releases</span>
                        <span class="stat-icon-badge emerald">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                        </span>
                    </div>
                    <div class="stat-number">{{ number_format($totalReleasesCount) }}</div>
                    <span class="stat-sub">
                        <strong style="color: #059669;">Recorded in audit logs</strong>
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Dispatched This Month</span>
                        <span class="stat-icon-badge blue">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </span>
                    </div>
                    <div class="stat-number">{{ number_format($monthReleasesCount) }}</div>
                    <span class="stat-sub">
                        <span>{{ number_format($monthUnitsReleased) }} total units released</span>
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Total Inventory Stock</span>
                        <span class="stat-icon-badge purple">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="stat-number">{{ number_format($totalInventoryStock) }}</div>
                    <span class="stat-sub">
                        <span>{{ number_format($totalInventoryItems) }} catalog items</span>
                    </span>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <span class="stat-label">Custody Offices</span>
                        <span class="stat-icon-badge">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                            </svg>
                        </span>
                    </div>
                    <div class="stat-number">{{ number_format($activeOfficesCount) }}</div>
                    <span class="stat-sub">
                        <span>{{ $staffCount }} warehouse personnel active</span>
                    </span>
                </div>
            </div>

            <!-- BIG CALENDAR OF RELEASES SECTION -->
            <div class="calendar-section" id="releasesCalendarCard">
                <!-- Toolbar -->
                <div class="calendar-toolbar">
                    <div class="calendar-title-group">
                        <div class="month-nav-btn-group">
                            <button type="button" class="month-nav-btn" id="prevMonthBtn" title="Previous Month">
                                <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <button type="button" class="month-nav-btn today-btn" id="todayMonthBtn">Today</button>
                            <button type="button" class="month-nav-btn" id="nextMonthBtn" title="Next Month">
                                <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        </div>
                        <h2 class="current-month-heading" id="calendarMonthTitle">October 2026</h2>
                    </div>

                    <!-- Filters & Search inside Calendar -->
                    <div class="calendar-filters-group">
                        <div class="calendar-office-dropdown-container">
                            <x-dropdown-search 
                                name="calendar_office_filter" 
                                id="calendarOfficeFilter" 
                                :options="$calendarOfficeOptions"
                                valueKey="id" 
                                labelKey="name" 
                                subtextKey="code" 
                                placeholder="All Custody Offices"
                                searchPlaceholder="Search office name or code..." 
                                :allowClear="true" 
                            />
                        </div>

                        <div class="calendar-search-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" id="calendarSearchInput" class="calendar-search-input" placeholder="Search item, variant, or recipient...">
                        </div>
                    </div>
                </div>

                <!-- Calendar Workspace (Grid + Inspector) -->
                <div class="calendar-workspace">
                    <!-- Left: Main Big Calendar Grid -->
                    <div class="calendar-grid-column">
                        <div class="calendar-weekdays-row">
                            <div class="weekday-cell weekend">Sun</div>
                            <div class="weekday-cell">Mon</div>
                            <div class="weekday-cell">Tue</div>
                            <div class="weekday-cell">Wed</div>
                            <div class="weekday-cell">Thu</div>
                            <div class="weekday-cell">Fri</div>
                            <div class="weekday-cell weekend">Sat</div>
                        </div>

                        <!-- Days Grid Populated via JS -->
                        <div class="calendar-days-grid" id="calendarDaysGrid">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                    <!-- Right: Day Release Inspector Panel -->
                    <aside class="calendar-inspector-column" id="calendarInspectorPanel">
                        <div class="inspector-header">
                            <span class="inspector-date-label">Releases on Selected Date</span>
                            <h3 class="inspector-date-title" id="inspectorDateTitle">Select a date</h3>
                            <div class="inspector-summary-pill-row">
                                <span class="inspector-badge highlight" id="inspectorReleasesCountPill">0 Releases</span>
                                <span class="inspector-badge" id="inspectorUnitsCountPill">0 Units Dispatched</span>
                            </div>
                        </div>

                        <!-- Releases List on this Day -->
                        <div class="inspector-releases-list" id="inspectorReleasesList">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </aside>
                </div>
            </div>

            <!-- Recent Releases Stream Table -->
            <div class="recent-releases-box">
                <div class="recent-releases-header">
                    <div>
                        <h2 class="recent-releases-title">Recent Stock Release Dispatches</h2>
                        <p style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 2px;">Latest issuances recorded across all custody offices with full audit details.</p>
                    </div>

                    <a href="{{ route('admin.releases.index') }}" class="btn-action-secondary" style="font-size: 0.8125rem;">
                        <span>Stage New Release &rarr;</span>
                    </a>
                </div>

                @if($recentReleases->isEmpty())
                    <div style="text-align: center; padding: 40px; color: var(--text-muted); background: #fafafa; border-radius: 12px; border: 1px dashed var(--border-subtle);">
                        <svg style="width: 36px; height: 36px; color: #cbd5e1; margin-bottom: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p style="font-weight: 600; color: var(--text-main);">No stock releases logged yet.</p>
                        <p style="font-size: 0.8125rem; margin-top: 4px;">Items released from office custody will appear here and on the calendar automatically.</p>
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="saas-table">
                            <thead>
                                <tr>
                                    <th>Ref / Batch #</th>
                                    <th>Date & Time</th>
                                    <th>Item & Variant</th>
                                    <th>Qty Released</th>
                                    <th>Source Office</th>
                                    <th>Recipient / Purpose</th>
                                    <th style="text-align: right;">Calendar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReleases as $rel)
                                    <tr>
                                        <td>
                                            <span class="ref-number-tag">{{ $rel->reference_number ?? ('RIS-' . $rel->created_at->format('Y') . '-' . str_pad($rel->id, 4, '0', STR_PAD_LEFT)) }}</span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600; font-size: 0.8125rem;">{{ $rel->created_at->format('M d, Y') }}</div>
                                            <div style="font-size: 0.7188rem; color: var(--text-muted);">{{ $rel->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text-main);">{{ $rel->item_name ?? 'Standard Item' }}</div>
                                            <div style="font-size: 0.75rem; color: #475569;">{{ $rel->variant_name ?? 'Default' }}</div>
                                        </td>
                                        <td>
                                            <span style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; color: #059669; background: var(--emerald-50); padding: 3px 8px; border-radius: 6px; font-size: 0.7813rem;">
                                                {{ $rel->quantity }} {{ $rel->variant?->effective_unit ?? 'units' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 600; color: #1e293b; font-size: 0.8125rem;">{{ $rel->office_name ?? ($rel->office?->office_name ?? 'Custody Office') }}</span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600; color: var(--text-main); font-size: 0.8125rem;">{{ $rel->recipient ?: 'Unspecified' }}</div>
                                            <div style="font-size: 0.7188rem; color: var(--text-muted);">{{ $rel->purpose ?: 'General stock issuance' }}</div>
                                        </td>
                                        <td style="text-align: right;">
                                            <button type="button" class="btn-jump-to-date" onclick="jumpToDate('{{ $rel->created_at->format('Y-m-d') }}')">
                                                <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                                </svg>
                                                <span>View on Calendar</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </x-sidebar>

    <!-- Calendar Interactive Script -->
    <script>
        // Master release events dataset from backend
        const allReleaseEvents = @json($calendarEvents);

        // Calendar State
        let currentCalendarDate = new Date();
        let selectedDateStr = formatDateKey(new Date());
        let activeOfficeFilter = '';
        let searchQuery = '';

        // Month Names
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        document.addEventListener('DOMContentLoaded', () => {
            // Find most recent release date if today has no releases so inspector shows rich data by default
            const todayStr = formatDateKey(new Date());
            const hasTodayReleases = allReleaseEvents.some(e => e.date === todayStr);

            if (!hasTodayReleases && allReleaseEvents.length > 0) {
                // Default to latest release date
                selectedDateStr = allReleaseEvents[0].date;
                const [y, m, d] = selectedDateStr.split('-').map(Number);
                currentCalendarDate = new Date(y, m - 1, d);
            } else {
                selectedDateStr = todayStr;
            }

            setupCalendarNavigation();
            setupCalendarFilters();
            renderCalendar();
            renderInspector(selectedDateStr);
        });

        function setupCalendarNavigation() {
            document.getElementById('prevMonthBtn').addEventListener('click', () => {
                currentCalendarDate.setMonth(currentCalendarDate.getMonth() - 1);
                renderCalendar();
            });

            document.getElementById('nextMonthBtn').addEventListener('click', () => {
                currentCalendarDate.setMonth(currentCalendarDate.getMonth() + 1);
                renderCalendar();
            });

            document.getElementById('todayMonthBtn').addEventListener('click', () => {
                currentCalendarDate = new Date();
                selectedDateStr = formatDateKey(new Date());
                renderCalendar();
                renderInspector(selectedDateStr);
            });
        }

        function setupCalendarFilters() {
            const officeSelect = document.getElementById('calendarOfficeFilter');
            if (officeSelect) {
                const handleOfficeFilterChange = () => {
                    activeOfficeFilter = officeSelect.value;
                    renderCalendar();
                    renderInspector(selectedDateStr);
                };

                officeSelect.addEventListener('change', handleOfficeFilterChange);
                officeSelect.addEventListener('dropdown-selected', handleOfficeFilterChange);
                officeSelect.addEventListener('dropdown-cleared', handleOfficeFilterChange);
            }

            const searchInput = document.getElementById('calendarSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    searchQuery = e.target.value.trim().toLowerCase();
                    renderCalendar();
                    renderInspector(selectedDateStr);
                });
            }
        }

        function formatDateKey(dateObj) {
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function getFilteredEvents() {
            return allReleaseEvents.filter(ev => {
                // Office filter
                if (activeOfficeFilter && activeOfficeFilter !== 'ALL' && activeOfficeFilter !== '' && String(ev.officeId) !== String(activeOfficeFilter)) {
                    return false;
                }

                // Text search query
                if (searchQuery !== '') {
                    const blob = `${ev.itemName} ${ev.variantName} ${ev.officeName} ${ev.recipient} ${ev.purpose} ${ev.referenceNumber} ${ev.batchId}`.toLowerCase();
                    if (!blob.includes(searchQuery)) {
                        return false;
                    }
                }

                return true;
            });
        }

        function renderCalendar() {
            const year = currentCalendarDate.getFullYear();
            const month = currentCalendarDate.getMonth();

            // Update title
            document.getElementById('calendarMonthTitle').textContent = `${monthNames[month]} ${year}`;

            const daysContainer = document.getElementById('calendarDaysGrid');
            daysContainer.innerHTML = '';

            const firstDayIndex = new Date(year, month, 1).getDay(); // 0 (Sun) to 6 (Sat)
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const todayStr = formatDateKey(new Date());
            const filteredEvents = getFilteredEvents();

            // Group events by date string "YYYY-MM-DD"
            const eventsByDate = {};
            filteredEvents.forEach(ev => {
                if (!eventsByDate[ev.date]) {
                    eventsByDate[ev.date] = [];
                }
                eventsByDate[ev.date].push(ev);
            });

            // 1. Previous Month Padding Days
            for (let i = firstDayIndex - 1; i >= 0; i--) {
                const dayNum = daysInPrevMonth - i;
                const prevMonthDate = new Date(year, month - 1, dayNum);
                const dateKey = formatDateKey(prevMonthDate);

                const cell = createDayCell(dayNum, dateKey, true, false, eventsByDate[dateKey] || []);
                daysContainer.appendChild(cell);
            }

            // 2. Current Month Days
            for (let dayNum = 1; dayNum <= daysInMonth; dayNum++) {
                const dateObj = new Date(year, month, dayNum);
                const dateKey = formatDateKey(dateObj);
                const isToday = (dateKey === todayStr);

                const cell = createDayCell(dayNum, dateKey, false, isToday, eventsByDate[dateKey] || []);
                daysContainer.appendChild(cell);
            }

            // 3. Next Month Padding Days to complete 35 or 42 grid cells
            const totalCellsSoFar = firstDayIndex + daysInMonth;
            const remainingCells = (totalCellsSoFar % 7 === 0) ? 0 : (7 - (totalCellsSoFar % 7));

            for (let dayNum = 1; dayNum <= remainingCells; dayNum++) {
                const nextMonthDate = new Date(year, month + 1, dayNum);
                const dateKey = formatDateKey(nextMonthDate);

                const cell = createDayCell(dayNum, dateKey, true, false, eventsByDate[dateKey] || []);
                daysContainer.appendChild(cell);
            }
        }

        function createDayCell(dayNum, dateKey, isOtherMonth, isToday, events) {
            const cell = document.createElement('div');
            cell.className = 'day-cell';
            if (isOtherMonth) cell.classList.add('other-month');
            if (isToday) cell.classList.add('is-today');
            if (events.length > 0) cell.classList.add('has-releases');
            if (dateKey === selectedDateStr) cell.classList.add('is-selected');

            cell.setAttribute('data-date', dateKey);

            // Header of day cell: day number
            const topRow = document.createElement('div');
            topRow.className = 'day-cell-top';

            const numSpan = document.createElement('span');
            numSpan.className = 'day-number';
            numSpan.textContent = dayNum;
            topRow.appendChild(numSpan);

            cell.appendChild(topRow);

            // Bottom of day cell: ONLY number badge if releases exist on this day
            if (events.length > 0) {
                const bottomRow = document.createElement('div');
                bottomRow.className = 'day-cell-bottom';

                const totalUnits = events.reduce((sum, e) => sum + e.quantity, 0);
                const badge = document.createElement('span');
                badge.className = 'day-release-count-badge';
                badge.title = `${events.length} release${events.length > 1 ? 's' : ''} (${totalUnits} units dispatched)`;
                badge.innerHTML = `
                    <span class="badge-dot"></span>
                    <span>${events.length}</span>
                `;
                bottomRow.appendChild(badge);

                if (totalUnits > 0) {
                    const unitsLabel = document.createElement('span');
                    unitsLabel.className = 'day-release-units-sub';
                    unitsLabel.textContent = `${totalUnits}u`;
                    bottomRow.appendChild(unitsLabel);
                }

                cell.appendChild(bottomRow);
            }

            // Click Handler
            cell.addEventListener('click', () => {
                selectDate(dateKey);
            });

            return cell;
        }

        function selectDate(dateKey) {
            selectedDateStr = dateKey;

            // Highlight in DOM
            document.querySelectorAll('.day-cell').forEach(c => {
                c.classList.toggle('is-selected', c.getAttribute('data-date') === dateKey);
            });

            renderInspector(dateKey);
        }

        function jumpToDate(dateKey) {
            const [year, month, day] = dateKey.split('-').map(Number);
            currentCalendarDate = new Date(year, month - 1, day);
            selectedDateStr = dateKey;

            renderCalendar();
            renderInspector(dateKey);

            // Smooth scroll up to calendar card
            const calCard = document.getElementById('releasesCalendarCard');
            if (calCard) {
                calCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function renderInspector(dateKey) {
            const dateTitle = document.getElementById('inspectorDateTitle');
            const releasesCountPill = document.getElementById('inspectorReleasesCountPill');
            const unitsCountPill = document.getElementById('inspectorUnitsCountPill');
            const listContainer = document.getElementById('inspectorReleasesList');

            if (!dateKey) {
                dateTitle.textContent = 'None Selected';
                listContainer.innerHTML = '';
                return;
            }

            // Format date readable
            const [y, m, d] = dateKey.split('-').map(Number);
            const dateObj = new Date(y, m - 1, d);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateTitle.textContent = dateObj.toLocaleDateString('en-US', options);

            const filteredEvents = getFilteredEvents();
            const dateEvents = filteredEvents.filter(e => e.date === dateKey);

            const totalUnits = dateEvents.reduce((acc, curr) => acc + curr.quantity, 0);

            releasesCountPill.textContent = `${dateEvents.length} Release${dateEvents.length === 1 ? '' : 's'}`;
            unitsCountPill.textContent = `${totalUnits} Units Dispatched`;

            if (dateEvents.length === 0) {
                listContainer.innerHTML = `
                    <div class="inspector-empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <h4>No Releases on This Date</h4>
                        <p>There are no stock disbursements recorded in custody logs for ${dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}.</p>
                        <a href="{{ route('admin.releases.index') }}" class="btn-stage-for-date">
                            + Stage Stock Release
                        </a>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = dateEvents.map(ev => {
                return `
                    <div class="inspector-release-card">
                        <div class="card-meta-top">
                            <span class="ref-number-tag">${escapeHtml(ev.referenceNumber)}</span>
                            <span class="release-time-tag">${escapeHtml(ev.time)}</span>
                        </div>

                        <div class="card-item-title">${escapeHtml(ev.itemName)}</div>
                        <div class="card-variant-title">${escapeHtml(ev.variantName)}</div>

                        <div class="card-dispatch-metrics">
                            <div class="card-qty-badge">
                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>${ev.quantity} ${escapeHtml(ev.unit)} Released</span>
                            </div>
                            <div class="card-office-text" title="${escapeHtml(ev.officeName)}">
                                🏢 ${escapeHtml(ev.officeName)}
                            </div>
                        </div>

                        <div class="card-details-grid">
                            <div class="detail-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <div><span class="detail-label">Recipient:</span> ${escapeHtml(ev.recipient)}</div>
                            </div>

                            <div class="detail-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <div><span class="detail-label">Purpose:</span> ${escapeHtml(ev.purpose)}</div>
                            </div>

                            <div class="detail-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                                <div><span class="detail-label">Dispatched By:</span> ${escapeHtml(ev.releasedBy)}</div>
                            </div>

                            ${ev.remarks ? `
                                <div class="detail-row" style="margin-top: 2px;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <div style="font-style: italic; color: #64748b;">${escapeHtml(ev.remarks)}</div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>