<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Staff Office Stock Catalog</title>
    
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
            --accent-blue: #2563eb;
            --accent-emerald: #059669;
            --accent-amber: #d97706;
            --accent-rose: #e11d48;
            --accent-violet: #7c3aed;
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
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
            background: #ffffff;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 700;
            border: 1px solid var(--border-subtle);
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* Layout Container */
        .catalog-container {
            padding: 32px 36px 60px;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .view-mode-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background-color: #f1f5f9;
            color: #0f172a;
            border: 1px solid var(--border-subtle);
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 8px;
        }

        .view-mode-badge svg {
            color: var(--accent-blue);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Office Location Banner */
        .office-location-banner {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            flex-wrap: wrap;
        }

        .office-banner-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .office-banner-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background-color: #f1f5f9;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--border-subtle);
        }

        .office-banner-icon svg {
            width: 22px;
            height: 22px;
        }

        .office-banner-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--text-primary);
            flex-wrap: wrap;
        }

        .office-code-tag {
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            background-color: #f1f5f9;
            color: #334155;
            padding: 2px 7px;
            border-radius: 5px;
            border: 1px solid var(--border-subtle);
            font-weight: 600;
        }

        .duty-station-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background-color: #ecfdf5;
            color: #047857;
            padding: 2px 8px;
            border-radius: 9999px;
            border: 1px solid #a7f3d0;
        }

        .office-banner-desc {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .office-switcher-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .office-switcher-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* Stats Strip */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.7813rem;
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
        }

        .stat-icon svg {
            width: 16px;
            height: 16px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .stat-footer-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Toolbar & Controls */
        .catalog-toolbar {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .toolbar-main-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .search-box-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-box-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 42px;
            padding: 0 14px 0 42px;
            font-family: var(--font-sans);
            font-size: 0.875rem;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            background-color: #f8fafc;
            color: var(--text-primary);
            transition: all 0.15s ease;
        }

        .search-input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        }

        .filters-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .results-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .active-filter-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .clear-filters-btn {
            background: none;
            border: none;
            color: var(--accent-rose);
            font-size: 0.7813rem;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.15s ease;
        }

        .clear-filters-btn:hover {
            background-color: #fff1f2;
        }

        /* View Mode Switcher Pills */
        .view-mode-toggle-group {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 9px;
            gap: 2px;
            border: 1px solid var(--border-subtle);
        }

        .view-mode-btn {
            background: transparent;
            border: none;
            padding: 5px 11px;
            border-radius: 7px;
            font-size: 0.7813rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .view-mode-btn:hover {
            color: #0f172a;
        }

        .view-mode-btn.active {
            background: #ffffff;
            color: #0f172a;
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* E-Commerce Product Card Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
            gap: 24px;
        }

        @media (max-width: 680px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* List Mode Styling */
        .product-grid.view-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .product-grid.view-list .product-card {
            flex-direction: row;
            align-items: center;
            padding: 12px 18px;
            gap: 16px;
            border-radius: 14px;
        }

        .product-grid.view-list .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.06);
        }

        .product-grid.view-list .card-hero-banner {
            width: 54px;
            height: 54px;
            min-width: 54px;
            border-radius: 12px;
            border-bottom: none;
            border: 1px solid var(--border-subtle);
            flex-shrink: 0;
        }

        .product-grid.view-list .hero-pattern,
        .product-grid.view-list .hero-badge-top-left,
        .product-grid.view-list .hero-badge-top-right {
            display: none;
        }

        .product-grid.view-list .hero-category-icon-wrap {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            box-shadow: none;
            border: none;
            background: transparent;
        }

        .product-grid.view-list .hero-category-icon-wrap svg {
            width: 22px;
            height: 22px;
        }

        .product-grid.view-list .card-content {
            padding: 0;
            display: grid;
            grid-template-columns: minmax(220px, 2.2fr) minmax(130px, 1fr) minmax(160px, 1.3fr);
            align-items: center;
            gap: 16px;
            min-width: 0;
            flex: 1;
        }

        .product-grid.view-list .sku-row {
            margin-bottom: 2px;
            justify-content: flex-start;
            gap: 8px;
        }

        .list-status-badge {
            display: none;
            margin-left: auto;
        }

        .product-grid.view-list .list-status-badge {
            display: inline-flex;
        }

        .product-grid.view-list .product-title {
            font-size: 0.9063rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
            margin-bottom: 2px;
            height: auto;
            -webkit-line-clamp: 1;
        }

        .product-grid.view-list .variant-spec-label {
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0;
        }

        .product-grid.view-list .card-location-row {
            margin-bottom: 0;
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-grid.view-list .stock-meter-box {
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
        }

        .product-grid.view-list .stock-meter-header {
            margin-bottom: 4px;
        }

        .product-grid.view-list .stock-large-num {
            font-size: 1rem;
        }

        .product-grid.view-list .card-footer {
            padding: 0;
            border-top: none;
            background: transparent;
            margin-left: auto;
            flex-shrink: 0;
            gap: 16px;
        }

        .product-grid.view-list .price-stack {
            text-align: right;
            min-width: 100px;
        }

        .product-grid.view-list .unit-price-text {
            font-size: 0.9063rem;
        }

        .product-grid.view-list .quick-view-btn {
            height: 36px;
            padding: 0 14px;
            font-size: 0.7813rem;
            white-space: nowrap;
        }

        @media (max-width: 1024px) {
            .product-grid.view-list .card-content {
                grid-template-columns: minmax(180px, 1.8fr) minmax(130px, 1.2fr);
            }
            .product-grid.view-list .card-location-row {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .product-grid.view-list .product-card {
                flex-direction: column;
                align-items: stretch;
                padding: 16px;
            }
            .product-grid.view-list .card-hero-banner {
                width: 100%;
                height: 60px;
            }
            .product-grid.view-list .card-content {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .product-grid.view-list .card-footer {
                margin-left: 0;
                padding-top: 12px;
                border-top: 1px solid var(--border-subtle);
            }
        }

        .product-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1;
            box-shadow: 0 14px 28px -6px rgba(15, 23, 42, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
        }

        /* Card Hero Banner */
        .card-hero-banner {
            height: 148px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border-bottom: 1px solid #f1f5f9;
        }

        /* Subtle background geometric grid overlay */
        .hero-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(148, 163, 184, 0.2) 1px, transparent 1px);
            background-size: 16px 16px;
            opacity: 0.6;
        }

        .hero-category-icon-wrap {
            position: relative;
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.2s ease;
        }

        .product-card:hover .hero-category-icon-wrap {
            transform: scale(1.08) rotate(2deg);
        }

        .hero-category-icon-wrap svg {
            width: 30px;
            height: 30px;
        }

        /* Floating Badges on Hero Banner */
        .hero-badge-top-left {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
        }

        .hero-badge-top-right {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
        }

        .category-chip {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(4px);
            color: #334155;
            padding: 3px 9px;
            border-radius: 9999px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.03em;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .status-badge-instock {
            background: rgba(236, 253, 245, 0.94);
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .status-badge-lowstock {
            background: rgba(254, 243, 199, 0.94);
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .status-badge-outofstock {
            background: rgba(255, 241, 242, 0.94);
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .status-indicator-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .dot-instock {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            animation: pulseDot 2s infinite;
        }

        .dot-lowstock {
            background-color: #f59e0b;
        }

        .dot-outofstock {
            background-color: #f43f5e;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        /* Card Content Area */
        .card-content {
            padding: 18px 20px 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .sku-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            gap: 8px;
        }

        .sku-code-tag {
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            color: #64748b;
            background-color: #f8fafc;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .copy-sku-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        .copy-sku-btn:hover {
            color: #0f172a;
            transform: scale(1.1);
        }

        .copy-sku-btn svg {
            width: 14px;
            height: 14px;
        }

        .product-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.35;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .variant-spec-label {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin-bottom: 12px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-location-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 16px;
        }

        .card-location-row svg {
            width: 13px;
            height: 13px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        /* Stock Health Meter Box */
        .stock-meter-box {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: auto;
            margin-bottom: 14px;
        }

        .stock-meter-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .stock-balance-wrap {
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .stock-large-num {
            font-size: 1.375rem;
            font-weight: 800;
            font-family: var(--font-mono);
            color: var(--text-primary);
            line-height: 1;
        }

        .stock-unit-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .stock-threshold-label {
            font-size: 0.6875rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .progress-track {
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.4s ease;
        }

        .progress-instock {
            background-color: #10b981;
        }

        .progress-lowstock {
            background-color: #f59e0b;
        }

        .progress-outofstock {
            background-color: #f43f5e;
        }

        /* Card Footer */
        .card-footer {
            padding: 14px 20px;
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .price-stack {
            display: flex;
            flex-direction: column;
        }

        .unit-price-text {
            font-size: 0.9375rem;
            font-weight: 800;
            color: var(--text-primary);
            font-family: var(--font-mono);
            line-height: 1.1;
        }

        .price-unit-subtext {
            font-size: 0.6875rem;
            color: #64748b;
            margin-top: 2px;
        }

        .quick-view-btn {
            background-color: #0f172a;
            color: #ffffff;
            border: 1px solid #0f172a;
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 0.7813rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .quick-view-btn:hover {
            background-color: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
        }

        .quick-view-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Empty State */
        .empty-catalog-state {
            grid-column: 1 / -1;
            background-color: #ffffff;
            border: 2px dashed #e2e8f0;
            border-radius: 18px;
            padding: 56px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-icon-wrap {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background-color: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .empty-icon-wrap svg {
            width: 34px;
            height: 34px;
        }

        .empty-title {
            font-size: 1.1875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .empty-desc {
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 420px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .empty-reset-btn {
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 9px;
            padding: 9px 18px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .empty-reset-btn:hover {
            background-color: #1e293b;
        }

        /* Quick View Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .modal-backdrop.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background-color: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 620px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            transform: scale(0.96) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .modal-backdrop.open .modal-box {
            transform: scale(1) translateY(0);
        }

        .modal-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 28px 28px 24px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            position: relative;
        }

        .modal-close-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .modal-close-btn svg {
            width: 18px;
            height: 18px;
        }

        .modal-category-chip {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255, 255, 255, 0.15);
            padding: 3px 10px;
            border-radius: 9999px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .modal-title {
            font-size: 1.375rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .modal-variant {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .modal-body {
            padding: 24px 28px;
        }

        .modal-stats-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .modal-stat-item {
            text-align: center;
        }

        .modal-stat-label {
            font-size: 0.6875rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .modal-stat-val {
            font-size: 1.1875rem;
            font-weight: 800;
            font-family: var(--font-mono);
            color: var(--text-primary);
            margin-top: 2px;
        }

        .modal-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .modal-detail-cell {
            background-color: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 10px 14px;
        }

        .modal-detail-label {
            font-size: 0.6875rem;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
        }

        .modal-detail-val {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 2px;
            word-break: break-all;
        }

        .modal-detail-mono {
            font-family: var(--font-mono);
        }

        .modal-desc-box {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 24px;
        }

        .modal-desc-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .modal-desc-text {
            font-size: 0.8438rem;
            color: #64748b;
            line-height: 1.5;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #f1f5f9;
            padding-top: 18px;
        }

        .modal-btn-dismiss {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 8px 18px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .modal-btn-dismiss:hover {
            background-color: #e2e8f0;
        }

        /* Floating Toast Notification */
        .toast-container {
            position: fixed;
            bottom: 86px;
            right: 24px;
            z-index: 10001;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast-item {
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            animation: toastSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: auto;
        }

        .toast-item svg {
            width: 16px;
            height: 16px;
            color: #10b981;
            flex-shrink: 0;
        }

        @keyframes toastSlideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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

        $sortFilterOptions = [
            ['id' => 'stock-desc', 'name' => 'Stock: Highest First', 'code' => 'QTY ↓'],
            ['id' => 'stock-asc', 'name' => 'Stock: Lowest First', 'code' => 'QTY ↑'],
            ['id' => 'name-asc', 'name' => 'Name: A to Z', 'code' => 'A-Z'],
            ['id' => 'name-desc', 'name' => 'Name: Z to A', 'code' => 'Z-A'],
            ['id' => 'cost-desc', 'name' => 'Price: Highest First', 'code' => '₱ ↓'],
            ['id' => 'cost-asc', 'name' => 'Price: Lowest First', 'code' => '₱ ↑'],
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

    <div class="catalog-container">

        <!-- Flash Alert -->
        @if(session('status'))
            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px; font-size: 0.8438rem; display: flex; align-items: center; gap: 10px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fff1f2; border: 1px solid #fecdd3; color: #be123c; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px; font-size: 0.8438rem; display: flex; align-items: center; gap: 10px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <span class="view-mode-badge">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Office Stock Catalog</span>
                </span>
                <h1 class="page-title">Office Stock Inventory</h1>
                <p class="page-subtitle">Browse and inspect custody allocations, available units, and catalog specifications for {{ $activeOffice ? $activeOffice->office_name : 'general custody' }}.</p>
            </div>
        </div>

        <!-- Designated Office Banner & Office Switcher (DropdownSearch) -->
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
                <div style="min-width: 240px;">
                    <x-dropdown-search 
                        name="office_id"
                        id="staffCatalogOfficeSelect"
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

        <!-- Key Metrics Strip -->
        <div class="stats-grid">
            <!-- Total Custody Units -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Stock in Custody</span>
                    <div class="stat-icon" style="background-color: #eff6ff; color: #2563eb;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalStockUnits) }}</div>
                <div class="stat-footer-text">Physical units currently assigned</div>
            </div>

            <!-- Active Catalog SKUs -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Catalog Products</span>
                    <div class="stat-icon" style="background-color: #f5f3ff; color: #7c3aed;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($allocatedItemsCount) }}</div>
                <div class="stat-footer-text">Distinct item variants in custody</div>
            </div>

            <!-- Low Stock Warnings -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Low Stock Alerts</span>
                    <div class="stat-icon" style="background-color: #fffbeb; color: #d97706;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                </div>
                <div class="stat-value" style="{{ $lowStockCount > 0 ? 'color: #d97706;' : '' }}">{{ number_format($lowStockCount) }}</div>
                <div class="stat-footer-text">{{ $outOfStockCount }} completely depleted</div>
            </div>

            <!-- Total Custody Valuation -->
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Custody Valuation</span>
                    <div class="stat-icon" style="background-color: #ecfdf5; color: #059669;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
                <div class="stat-value" style="font-family: var(--font-mono); font-size: 1.375rem;">₱{{ number_format($totalStockValue, 2) }}</div>
                <div class="stat-footer-text">Estimated inventory acquisition value</div>
            </div>
        </div>

        <!-- Filter & Search Toolbar (All Dropdowns use DropdownSearch component) -->
        <div class="catalog-toolbar">
            <div class="toolbar-main-row">
                <!-- Search Input -->
                <div class="search-box-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input 
                        type="text" 
                        id="catalogSearchInput" 
                        class="search-input" 
                        placeholder="Search products by title, SKU, barcode, specification, or room..."
                        autocomplete="off"
                    >
                </div>

                <!-- Dropdown Search Filters -->
                <div class="filters-group">
                    <!-- DropdownSearch: Category Filter -->
                    <div style="min-width: 190px;">
                        <x-dropdown-search 
                            name="catalog_category_filter"
                            id="catalogCategoryFilterSelect"
                            :options="$categoryFilterOptions"
                            valueKey="id"
                            labelKey="name"
                            placeholder="All Categories"
                            searchPlaceholder="Filter category..."
                            :allowClear="true"
                        />
                    </div>

                    <!-- DropdownSearch: Status Filter -->
                    <div style="min-width: 180px;">
                        <x-dropdown-search 
                            name="catalog_status_filter"
                            id="catalogStatusFilterSelect"
                            :options="$statusFilterOptions"
                            valueKey="id"
                            labelKey="name"
                            placeholder="All Stock Statuses"
                            searchPlaceholder="Filter status..."
                            :allowClear="true"
                        />
                    </div>

                    <!-- DropdownSearch: Sort Filter -->
                    <div style="min-width: 190px;">
                        <x-dropdown-search 
                            name="catalog_sort_filter"
                            id="catalogSortFilterSelect"
                            :options="$sortFilterOptions"
                            valueKey="id"
                            labelKey="name"
                            selected="stock-desc"
                            placeholder="Sort By..."
                            searchPlaceholder="Sort order..."
                            :allowClear="false"
                        />
                    </div>
                </div>
            </div>

            <div class="results-meta-row">
                <span id="catalogCountText">Showing {{ $officeInventories->count() }} of {{ $officeInventories->count() }} catalog items</span>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="active-filter-chips">
                        <button type="button" class="clear-filters-btn" id="clearAllFiltersBtn" style="display: none;">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            <span>Reset Filters</span>
                        </button>
                    </div>

                    <!-- View Mode Toggle: Grid vs List -->
                    <div class="view-mode-toggle-group" id="viewModeToggleGroup" role="group" aria-label="Catalog View Mode">
                        <button type="button" class="view-mode-btn active" id="btnModeGrid" data-view="grid" title="Grid View" aria-pressed="true">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                            </svg>
                            <span>Grid</span>
                        </button>
                        <button type="button" class="view-mode-btn" id="btnModeList" data-view="list" title="List View" aria-pressed="false">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            <span>List</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- E-Commerce Product Card Grid -->
        <div class="product-grid" id="productGridContainer">
            @forelse($officeInventories as $oi)
                @php
                    $variant = $oi->variant;
                    $item = $variant?->inventoryItem;
                    $itemName = $item?->name ?? 'Uncataloged Item';
                    $variantName = $variant?->variant_name ?? 'Standard Edition';
                    $category = $variant?->effective_category ?? ($item?->categoryModel?->name ?? ($item?->category ?? 'General Supplies'));
                    $sku = $variant?->sku ?: ($variant?->barcode ?: ($item?->item_code ?: 'N/A'));
                    $barcode = $variant?->barcode ?: 'N/A';
                    $itemCode = $item?->item_code ?: 'N/A';
                    $unit = $variant?->effective_unit ?? ($variant?->unit ?? ($item?->unit ?? 'units'));
                    $reorderLevel = (int) ($variant?->reorder_level ?? 5);
                    $location = $variant?->location ?? ($item?->location ?? 'General Storage');
                    $unitCost = (float) ($variant?->unit_cost ?? ($item?->unit_cost ?? 0));
                    $totalLineValue = $oi->quantity * $unitCost;

                    // Status Calculation
                    $status = 'In Stock';
                    $statusBadgeClass = 'status-badge-instock';
                    $dotClass = 'dot-instock';
                    $progressClass = 'progress-instock';

                    if ($oi->quantity <= 0) {
                        $status = 'Out of Stock';
                        $statusBadgeClass = 'status-badge-outofstock';
                        $dotClass = 'dot-outofstock';
                        $progressClass = 'progress-outofstock';
                    } elseif ($oi->quantity <= $reorderLevel) {
                        $status = 'Low Stock';
                        $statusBadgeClass = 'status-badge-lowstock';
                        $dotClass = 'dot-lowstock';
                        $progressClass = 'progress-lowstock';
                    }

                    // Progress percentage calculation
                    $maxBenchmark = max(10, $reorderLevel * 4);
                    $stockPercent = min(100, max(4, round(($oi->quantity / $maxBenchmark) * 100)));
                    if ($oi->quantity <= 0) {
                        $stockPercent = 0;
                    }

                    // Category visual styling & icon
                    $catLower = strtolower($category);
                    $iconColor = '#0f172a';
                    $bannerGradient = 'linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%)';
                    
                    if (str_contains($catLower, 'office') || str_contains($catLower, 'paper') || str_contains($catLower, 'stationery')) {
                        $iconColor = '#2563eb';
                        $bannerGradient = 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)';
                    } elseif (str_contains($catLower, 'it') || str_contains($catLower, 'tech') || str_contains($catLower, 'hardware') || str_contains($catLower, 'electronic')) {
                        $iconColor = '#7c3aed';
                        $bannerGradient = 'linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%)';
                    } elseif (str_contains($catLower, 'med') || str_contains($catLower, 'health') || str_contains($catLower, 'first aid')) {
                        $iconColor = '#e11d48';
                        $bannerGradient = 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)';
                    } elseif (str_contains($catLower, 'clean') || str_contains($catLower, 'janitor') || str_contains($catLower, 'sanit')) {
                        $iconColor = '#059669';
                        $bannerGradient = 'linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%)';
                    } elseif (str_contains($catLower, 'furnitur') || str_contains($catLower, 'fixture')) {
                        $iconColor = '#d97706';
                        $bannerGradient = 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)';
                    }
                @endphp

                <article 
                    class="product-card"
                    data-id="{{ $oi->id }}"
                    data-name="{{ strtolower($itemName) }}"
                    data-variant="{{ strtolower($variantName) }}"
                    data-sku="{{ strtolower($sku) }}"
                    data-barcode="{{ strtolower($barcode) }}"
                    data-code="{{ strtolower($itemCode) }}"
                    data-category="{{ strtolower($category) }}"
                    data-status="{{ $status }}"
                    data-quantity="{{ (int) $oi->quantity }}"
                    data-cost="{{ (float) $unitCost }}"
                    data-total-val="{{ (float) $totalLineValue }}"
                    data-location="{{ strtolower($location) }}"
                >
                    <!-- Visual Hero Banner -->
                    <div class="card-hero-banner" style="background: {{ $bannerGradient }};">
                        <div class="hero-pattern"></div>
                        
                        <!-- Category Badge Top-Left -->
                        <div class="hero-badge-top-left">
                            <span class="category-chip">
                                <span>{{ $category }}</span>
                            </span>
                        </div>

                        <!-- Stock Status Badge Top-Right -->
                        {{-- <div class="hero-badge-top-right">
                            <span class="status-badge {{ $statusBadgeClass }}">
                                <span class="status-indicator-dot {{ $dotClass }}"></span>
                                <span>{{ $status }}</span>
                            </span>
                        </div> --}}

                        <!-- Visual Category Icon -->
                        <div class="hero-category-icon-wrap" style="color: {{ $iconColor }};">
                            @if(str_contains($catLower, 'office') || str_contains($catLower, 'paper') || str_contains($catLower, 'stationery'))
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                    <path d="M6 6h10"/><path d="M6 10h10"/>
                                </svg>
                            @elseif(str_contains($catLower, 'it') || str_contains($catLower, 'tech') || str_contains($catLower, 'hardware') || str_contains($catLower, 'electronic'))
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/>
                                </svg>
                            @elseif(str_contains($catLower, 'med') || str_contains($catLower, 'health'))
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                </svg>
                            @elseif(str_contains($catLower, 'clean') || str_contains($catLower, 'janitor'))
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                                </svg>
                            @elseif(str_contains($catLower, 'furnitur'))
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/><path d="M3 11v5a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"/><path d="M5 18v2"/><path d="M19 18v2"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                    <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-content">
                        <!-- SKU Code & Copy Button -->
                        <div class="sku-row">
                            <span class="sku-code-tag" title="SKU: {{ $sku }}">
                                <span>SKU:</span>
                                <strong>{{ $sku }}</strong>
                            </span>
                            <button type="button" class="copy-sku-btn" data-sku="{{ $sku }}" title="Copy SKU to Clipboard">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2"/>
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>
                                </svg>
                            </button>
                            <span class="list-status-badge status-badge {{ $statusBadgeClass }}">
                                <span class="status-indicator-dot {{ $dotClass }}"></span>
                                <span>{{ $status }}</span>
                            </span>
                        </div>

                        <!-- Product Title -->
                        <h2 class="product-title" title="{{ $variantName }}">{{ $variantName }}</h2>

                        <!-- Variant Spec Subtitle -->
                        <p class="variant-spec-label" title="{{ $itemName }}">{{ $itemName }}</p>

                        <!-- Storage Location -->
                        <div class="card-location-row">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span>{{ $location }}</span>
                        </div>

                        <!-- Stock Level Progress Meter -->
                        <div class="stock-meter-box">
                            <div class="stock-meter-header">
                                <div class="stock-balance-wrap">
                                    <span class="stock-large-num">{{ number_format($oi->quantity) }}</span>
                                    <span class="stock-unit-label">{{ $unit }}</span>
                                </div>
                                {{-- <span class="stock-threshold-label">Min: {{ $reorderLevel }} {{ $unit }}</span> --}}
                            </div>
                            <div class="progress-track" title="{{ $oi->quantity }} {{ $unit }} available (Threshold: {{ $reorderLevel }})">
                                <div class="progress-fill {{ $progressClass }}" style="width: {{ $stockPercent }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Pricing & Quick View Modal Trigger -->
                    <div class="card-footer">
                        <div class="price-stack">
                            <span class="unit-price-text">₱{{ number_format($unitCost, 2) }}</span>
                            <span class="price-unit-subtext">Total: ₱{{ number_format($totalLineValue, 2) }}</span>
                        </div>

                        <button 
                            type="button" 
                            class="quick-view-btn"
                            data-quickview
                            data-name="{{ $itemName }}"
                            data-variant="{{ $variantName }}"
                            data-sku="{{ $sku }}"
                            data-barcode="{{ $barcode }}"
                            data-itemcode="{{ $itemCode }}"
                            data-category="{{ $category }}"
                            data-status="{{ $status }}"
                            data-quantity="{{ number_format($oi->quantity) }}"
                            data-unit="{{ $unit }}"
                            data-reorder="{{ $reorderLevel }}"
                            data-location="{{ $location }}"
                            data-cost="₱{{ number_format($unitCost, 2) }}"
                            data-total="₱{{ number_format($totalLineValue, 2) }}"
                            data-desc="{{ $item?->description ?: 'No detailed catalog description provided for this item.' }}"
                            data-office="{{ $activeOffice ? $activeOffice->office_name : 'General Storage' }}"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>Quick View</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="empty-catalog-state">
                    <div class="empty-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                            <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                        </svg>
                    </div>
                    <h3 class="empty-title">No Stock Allocated Yet</h3>
                    <p class="empty-desc">There are currently no supply balances or items assigned to {{ $activeOffice ? $activeOffice->office_name : 'this office' }}. Contact warehouse admin or submit a requisition request.</p>
                </div>
            @endforelse

            <!-- Client Filter Empty State (Hidden by default) -->
            <div class="empty-catalog-state" id="clientFilterEmptyState" style="display: none;">
                <div class="empty-icon-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <h3 class="empty-title">No Matching Items Found</h3>
                <p class="empty-desc">We couldn't find any stock items matching your active search and filter criteria. Try adjusting or clearing your filters.</p>
                <button type="button" class="empty-reset-btn" id="emptyStateResetBtn">Reset All Filters</button>
            </div>
        </div>

    </div>

    <!-- Quick View Modal -->
    <div class="modal-backdrop" id="quickViewModalBackdrop" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-box" id="quickViewModalBox">
            <div class="modal-hero">
                <button type="button" class="modal-close-btn" id="modalCloseBtn" aria-label="Close Modal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <span class="modal-category-chip" id="modalCategoryTag">Office Supplies</span>
                <h2 class="modal-title" id="modalTitle">Product Title</h2>
                <p class="modal-variant" id="modalVariant">Variant Description</p>
            </div>

            <div class="modal-body">
                <!-- Top Key Metrics Strip -->
                <div class="modal-stats-strip">
                    <div class="modal-stat-item">
                        <div class="modal-stat-label">In Custody</div>
                        <div class="modal-stat-val" id="modalStockQuantity">0</div>
                    </div>
                    <div class="modal-stat-item">
                        <div class="modal-stat-label">Unit Cost</div>
                        <div class="modal-stat-val" id="modalUnitCost">₱0.00</div>
                    </div>
                    <div class="modal-stat-item">
                        <div class="modal-stat-label">Total Cost</div>
                        <div class="modal-stat-val" id="modalTotalVal">₱0.00</div>
                    </div>
                </div>

                <!-- Detailed Specs Grid -->
                <div class="modal-details-grid">
                    <div class="modal-detail-cell">
                        <div class="modal-detail-label">Item Code</div>
                        <div class="modal-detail-val modal-detail-mono" id="modalSku">N/A</div>
                    </div>
                    {{-- <div class="modal-detail-cell">
                        <div class="modal-detail-label">Barcode</div>
                        <div class="modal-detail-val modal-detail-mono" id="modalBarcode">N/A</div>
                    </div>
                    <div class="modal-detail-cell">
                        <div class="modal-detail-label">Master Item Code</div>
                        <div class="modal-detail-val modal-detail-mono" id="modalItemCode">N/A</div>
                    </div> --}}
                    <div class="modal-detail-cell">
                        <div class="modal-detail-label">Stock Status</div>
                        <div class="modal-detail-val" id="modalStatus">In Stock</div>
                    </div>
                    <div class="modal-detail-cell">
                        <div class="modal-detail-label">Reorder Level</div>
                        <div class="modal-detail-val" id="modalReorder">5 units</div>
                    </div>
                    <div class="modal-detail-cell">
                        <div class="modal-detail-label">Designated Storage</div>
                        <div class="modal-detail-val" id="modalLocation">General Warehouse</div>
                    </div>
                    <div class="modal-detail-cell" style="grid-column: 1 / -1;">
                        <div class="modal-detail-label">Custody Office</div>
                        <div class="modal-detail-val" id="modalOffice">Office Name</div>
                    </div>
                </div>

                <!-- Description Box -->
                <div class="modal-desc-box">
                    <div class="modal-desc-title">Catalog Description & Specs</div>
                    <p class="modal-desc-text" id="modalDesc">No details provided.</p>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="modal-btn-dismiss" id="modalDismissBtn">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Copy Toast -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bottom Navigation Component -->
    <x-staffNav />

    <!-- Interactive Scripting -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('catalogSearchInput');
            const categorySelect = document.getElementById('catalogCategoryFilterSelect');
            const statusSelect = document.getElementById('catalogStatusFilterSelect');
            const sortSelect = document.getElementById('catalogSortFilterSelect');
            const officeSelect = document.getElementById('staffCatalogOfficeSelect');
            const countText = document.getElementById('catalogCountText');
            const clearBtn = document.getElementById('clearAllFiltersBtn');
            const resetBtn = document.getElementById('emptyStateResetBtn');
            const emptyFilterState = document.getElementById('clientFilterEmptyState');
            const gridContainer = document.getElementById('productGridContainer');
            const toastContainer = document.getElementById('toastContainer');

            // Quick View Modal Elements
            const modalBackdrop = document.getElementById('quickViewModalBackdrop');
            const modalCloseBtn = document.getElementById('modalCloseBtn');
            const modalDismissBtn = document.getElementById('modalDismissBtn');

            // Office Switcher: When office dropdown changes, reload page with office_id query
            if (officeSelect && !officeSelect.disabled) {
                officeSelect.addEventListener('change', (e) => {
                    const selectedOfficeId = e.target.value;
                    if (selectedOfficeId) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('office_id', selectedOfficeId);
                        window.location.href = url.toString();
                    }
                });
            }

            // Real-Time Filter & Sort Logic
            function applyFiltersAndSort() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const categoryVal = (categorySelect?.value || '').trim().toLowerCase();
                const statusVal = (statusSelect?.value || '').trim();
                const sortVal = sortSelect?.value || 'stock-desc';

                const cards = Array.from(document.querySelectorAll('.product-card'));
                const totalCards = cards.length;
                let visibleCount = 0;

                cards.forEach(card => {
                    const name = card.dataset.name || '';
                    const variant = card.dataset.variant || '';
                    const sku = card.dataset.sku || '';
                    const barcode = card.dataset.barcode || '';
                    const code = card.dataset.code || '';
                    const category = card.dataset.category || '';
                    const status = card.dataset.status || '';
                    const location = card.dataset.location || '';

                    // Match Text Query
                    const matchesQuery = !query || 
                        name.includes(query) || 
                        variant.includes(query) || 
                        sku.includes(query) || 
                        barcode.includes(query) || 
                        code.includes(query) ||
                        category.includes(query) || 
                        location.includes(query);

                    // Match Category
                    const matchesCategory = !categoryVal || category === categoryVal;

                    // Match Status
                    const matchesStatus = !statusVal || status === statusVal;

                    if (matchesQuery && matchesCategory && matchesStatus) {
                        card.style.display = '';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Sort Visible Cards
                cards.sort((a, b) => {
                    const qtyA = parseInt(a.dataset.quantity || 0, 10);
                    const qtyB = parseInt(b.dataset.quantity || 0, 10);
                    const costA = parseFloat(a.dataset.cost || 0);
                    const costB = parseFloat(b.dataset.cost || 0);
                    const nameA = a.dataset.name || '';
                    const nameB = b.dataset.name || '';

                    switch (sortVal) {
                        case 'stock-desc':
                            return qtyB - qtyA;
                        case 'stock-asc':
                            return qtyA - qtyB;
                        case 'cost-desc':
                            return costB - costA;
                        case 'cost-asc':
                            return costA - costB;
                        case 'name-asc':
                            return nameA.localeCompare(nameB);
                        case 'name-desc':
                            return nameB.localeCompare(nameA);
                        default:
                            return 0;
                    }
                });

                // Re-append sorted cards to grid container (preserve empty states)
                cards.forEach(card => gridContainer.appendChild(card));
                if (emptyFilterState) {
                    gridContainer.appendChild(emptyFilterState);
                }

                // Update Results Meta
                if (countText) {
                    countText.textContent = `Showing ${visibleCount} of ${totalCards} catalog items`;
                }

                // Toggle Clear Button
                const hasActiveFilter = Boolean(query || categoryVal || statusVal);
                if (clearBtn) {
                    clearBtn.style.display = hasActiveFilter ? 'inline-flex' : 'none';
                }

                // Toggle Empty State
                if (emptyFilterState) {
                    emptyFilterState.style.display = (totalCards > 0 && visibleCount === 0) ? 'flex' : 'none';
                }
            }

            // Event Listeners for Filters
            if (searchInput) {
                searchInput.addEventListener('input', applyFiltersAndSort);
            }
            if (categorySelect) {
                categorySelect.addEventListener('change', applyFiltersAndSort);
            }
            if (statusSelect) {
                statusSelect.addEventListener('change', applyFiltersAndSort);
            }
            if (sortSelect) {
                sortSelect.addEventListener('change', applyFiltersAndSort);
            }

            // Reset Filters
            function resetAllFilters() {
                if (searchInput) searchInput.value = '';
                
                // Clear dropdown-search custom components
                if (categorySelect) {
                    categorySelect.value = '';
                    categorySelect.dispatchEvent(new Event('change'));
                }
                if (statusSelect) {
                    statusSelect.value = '';
                    statusSelect.dispatchEvent(new Event('change'));
                }
                applyFiltersAndSort();
            }

            if (clearBtn) clearBtn.addEventListener('click', resetAllFilters);
            if (resetBtn) resetBtn.addEventListener('click', resetAllFilters);

            // Copy SKU to Clipboard
            document.querySelectorAll('.copy-sku-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const sku = btn.dataset.sku;
                    if (!sku || sku === 'N/A') return;

                    navigator.clipboard.writeText(sku).then(() => {
                        showToast(`Copied SKU: ${sku}`);
                        
                        // Temporarily change icon
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = `<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                        }, 1800);
                    }).catch(() => {
                        showToast(`Could not copy SKU`);
                    });
                });
            });

            // Toast helper
            function showToast(message) {
                if (!toastContainer) return;
                const toast = document.createElement('div');
                toast.className = 'toast-item';
                toast.innerHTML = `
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>${message}</span>
                `;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px)';
                    setTimeout(() => toast.remove(), 250);
                }, 2400);
            }

            // Quick View Modal Logic
            function openQuickView(data) {
                document.getElementById('modalTitle').textContent = data.variant || 'Catalog Item';
                document.getElementById('modalVariant').textContent = data.variant || '';
                document.getElementById('modalCategoryTag').textContent = data.category || 'General';
                document.getElementById('modalStockQuantity').textContent = `${data.quantity} ${data.unit}`;
                document.getElementById('modalUnitCost').textContent = data.cost || '₱0.00';
                document.getElementById('modalTotalVal').textContent = data.total || '₱0.00';
                document.getElementById('modalSku').textContent = data.sku || 'N/A';
                // document.getElementById('modalBarcode').textContent = data.barcode || 'N/A';
                // document.getElementById('modalItemCode').textContent = data.itemcode || 'N/A';
                document.getElementById('modalStatus').textContent = data.status || 'In Stock';
                document.getElementById('modalReorder').textContent = `${data.reorder} ${data.unit}`;
                document.getElementById('modalLocation').textContent = data.location || 'General Storage';
                document.getElementById('modalOffice').textContent = data.office || 'Office';
                document.getElementById('modalDesc').textContent = data.desc || 'No details provided.';

                modalBackdrop.classList.add('open');
                modalBackdrop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeQuickView() {
                modalBackdrop.classList.remove('open');
                modalBackdrop.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('[data-quickview]').forEach(btn => {
                btn.addEventListener('click', () => {
                    openQuickView({
                        name: btn.dataset.name,
                        variant: btn.dataset.variant,
                        sku: btn.dataset.sku,
                        barcode: btn.dataset.barcode,
                        itemcode: btn.dataset.itemcode,
                        category: btn.dataset.category,
                        status: btn.dataset.status,
                        quantity: btn.dataset.quantity,
                        unit: btn.dataset.unit,
                        reorder: btn.dataset.reorder,
                        location: btn.dataset.location,
                        cost: btn.dataset.cost,
                        total: btn.dataset.total,
                        desc: btn.dataset.desc,
                        office: btn.dataset.office,
                    });
                });
            });

            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeQuickView);
            if (modalDismissBtn) modalDismissBtn.addEventListener('click', closeQuickView);
            if (modalBackdrop) {
                modalBackdrop.addEventListener('click', (e) => {
                    if (e.target === modalBackdrop) closeQuickView();
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modalBackdrop.classList.contains('open')) {
                    closeQuickView();
                }
            });

            // View Mode Switcher (Grid vs List)
            const btnModeGrid = document.getElementById('btnModeGrid');
            const btnModeList = document.getElementById('btnModeList');
            const productGridContainer = document.getElementById('productGridContainer');

            function setViewMode(mode) {
                if (mode === 'list') {
                    if (productGridContainer) productGridContainer.classList.add('view-list');
                    if (btnModeList) {
                        btnModeList.classList.add('active');
                        btnModeList.setAttribute('aria-pressed', 'true');
                    }
                    if (btnModeGrid) {
                        btnModeGrid.classList.remove('active');
                        btnModeGrid.setAttribute('aria-pressed', 'false');
                    }
                    try { localStorage.setItem('staff_inventory_view_mode', 'list'); } catch (e) {}
                } else {
                    if (productGridContainer) productGridContainer.classList.remove('view-list');
                    if (btnModeGrid) {
                        btnModeGrid.classList.add('active');
                        btnModeGrid.setAttribute('aria-pressed', 'true');
                    }
                    if (btnModeList) {
                        btnModeList.classList.remove('active');
                        btnModeList.setAttribute('aria-pressed', 'false');
                    }
                    try { localStorage.setItem('staff_inventory_view_mode', 'grid'); } catch (e) {}
                }
            }

            if (btnModeGrid) btnModeGrid.addEventListener('click', () => setViewMode('grid'));
            if (btnModeList) btnModeList.addEventListener('click', () => setViewMode('list'));

            // Restore saved view mode preference
            try {
                const savedViewMode = localStorage.getItem('staff_inventory_view_mode') || 'grid';
                if (savedViewMode === 'list') {
                    setViewMode('list');
                }
            } catch (e) {}

            // Initial sort
            applyFiltersAndSort();
        });
    </script>
</body>
</html>
