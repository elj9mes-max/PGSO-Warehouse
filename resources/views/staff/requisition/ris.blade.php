<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PGSO Warehouse - Requisition and Issue Slip (RIS)</title>

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
            --accent-emerald-dark: #065f46;
            --accent-blue: #2563eb;
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
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
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
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 9999px;
        }

        /* Cart Toggle Button in Topbar */
        .cart-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: #059669;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);
        }

        .cart-toggle-btn:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
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
        .ris-container {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 24px 28px 120px;
            flex: 1;
        }

        /* Subheader & Breadcrumb */
        .ris-header-row {
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
            color: var(--accent-emerald);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .back-nav-link:hover {
            color: #047857;
            text-decoration: underline;
        }

        .crumb-sep {
            color: var(--text-muted);
        }

        .crumb-active {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .ris-title-area h1 {
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
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .ris-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* Office Station Pill */
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
            background: #ecfdf5;
            color: #059669;
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
        }

        /* Filter Toolbar */
        .catalog-toolbar {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .toolbar-main-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .search-box-wrap {
            flex: 1;
            min-width: 260px;
            position: relative;
        }

        .search-box-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #94a3b8;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 42px;
            padding: 0 14px 0 42px;
            background: #f8fafc;
            border: 1.5px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--accent-emerald);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
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
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .clear-filters-btn {
            background: none;
            border: none;
            color: #ef4444;
            font-size: 0.7813rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .clear-filters-btn:hover {
            background: #fee2e2;
        }

        /* Product Cards Grid: Full Width */
        .catalog-main-col {
            width: 100%;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 22px;
            width: 100%;
        }

        .product-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .product-card:hover {
            border-color: #a7f3d0;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.06), 0 4px 6px -4px rgba(5, 150, 105, 0.04);
        }

        .product-card.in-cart {
            border-color: #10b981;
            box-shadow: 0 0 0 1.5px #10b981, 0 8px 16px rgba(16, 185, 129, 0.08);
        }

        /* Card Hero Banner */
        .card-hero-banner {
            height: 110px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border-subtle);
        }

        .hero-badge-top-left {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

        .category-chip {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 9999px;
            padding: 3px 10px;
            font-size: 0.6875rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .hero-badge-top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
        }

        .status-badge-instock { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .status-badge-lowstock { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        .status-badge-outofstock { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }

        .status-indicator-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .dot-instock { background-color: #10b981; }
        .dot-lowstock { background-color: #f59e0b; }
        .dot-outofstock { background-color: #f43f5e; }

        .hero-category-icon-wrap svg {
            width: 44px;
            height: 44px;
            opacity: 0.85;
        }

        /* Card Content */
        .card-content {
            padding: 16px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .sku-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .sku-code-tag {
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 600;
        }

        .product-title {
            font-size: 0.9688rem;
            font-weight: 700;
            color: #09090b;
            line-height: 1.35;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.7em;
        }

        .variant-spec-label {
            font-size: 0.7813rem;
            color: #475569;
            font-weight: 500;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-location-row {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .card-location-row svg {
            width: 13px;
            height: 13px;
        }

        /* Stock Meter */
        .stock-meter-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 9px 12px;
            margin-top: auto;
            margin-bottom: 14px;
        }

        .stock-meter-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .stock-balance-wrap {
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .stock-large-num {
            font-size: 1.125rem;
            font-weight: 800;
            color: #09090b;
            font-family: var(--font-mono);
        }

        .stock-unit-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .stock-threshold-label {
            font-size: 0.6875rem;
            color: var(--text-muted);
        }

        .progress-track {
            height: 5px;
            background: #e2e8f0;
            border-radius: 9999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 9999px;
        }

        .progress-instock { background: #10b981; }
        .progress-lowstock { background: #f59e0b; }
        .progress-outofstock { background: #f43f5e; }

        /* Card Footer: Add to Cart Action Area */
        .card-footer {
            padding: 12px 18px 16px;
            border-top: 1px solid var(--border-subtle);
            background: #ffffff;
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
            color: #09090b;
            font-family: var(--font-mono);
        }

        .price-unit-subtext {
            font-size: 0.6875rem;
            color: var(--text-muted);
        }

        /* Add to Cart Button */
        .add-cart-btn {
            background: #0f172a;
            color: #ffffff;
            border: none;
            padding: 8px 14px;
            border-radius: 9px;
            font-size: 0.8125rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .add-cart-btn:hover:not(:disabled) {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
        }

        .add-cart-btn:disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        .add-cart-btn svg {
            width: 14px;
            height: 14px;
        }

        /* In-Card Quantity Stepper */
        .card-qty-stepper {
            display: none;
            align-items: center;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 9px;
            overflow: hidden;
        }

        .card-qty-stepper.active {
            display: flex;
        }

        .stepper-btn {
            background: transparent;
            border: none;
            color: #065f46;
            width: 28px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            transition: background 0.15s ease;
        }

        .stepper-btn:hover {
            background: #d1fae5;
        }

        .stepper-val {
            font-family: var(--font-mono);
            font-size: 0.8125rem;
            font-weight: 800;
            color: #065f46;
            min-width: 28px;
            text-align: center;
            padding: 0 4px;
        }

        /* ================= DOUBLE-WIDTH SLIDE-OVER SIDE CART DRAWER (Fits 2 cards per row) ================= */
        .side-cart-col {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            height: 93vh;
            width: 1060px;
            max-width: 96vw;
            background: #ffffff;
            border-left: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            box-shadow: -10px 0 45px rgba(0, 0, 0, 0.16);
            z-index: 300;
            transform: translateX(105%);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .side-cart-col.open {
            transform: translateX(0);
        }

        .cart-drawer-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(3px);
            z-index: 290;
            transition: opacity 0.25s ease;
        }

        .cart-drawer-backdrop.open {
            display: block;
        }

        .side-cart-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }

        .cart-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cart-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.15);
        }

        .cart-header-icon svg {
            width: 19px;
            height: 19px;
        }

        .cart-title-text strong {
            display: block;
            font-size: 1rem;
            font-weight: 800;
            color: #09090b;
            line-height: 1.2;
        }

        .cart-title-text span {
            font-size: 0.6875rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            letter-spacing: 0.04em;
        }

        .cart-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .clear-cart-btn {
            background: none;
            border: 1px solid #fee2e2;
            color: #dc2626;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .clear-cart-btn:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .drawer-close-btn {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            color: #64748b;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .drawer-close-btn:hover {
            background: #f1f5f9;
            color: #09090b;
            border-color: #cbd5e1;
        }

        /* Cart Items List Container (2-Column Grid in Double-Width Drawer) */
        .cart-items-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px 24px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            align-content: start;
            gap: 14px;
            background: #f8fafc;
        }

        /* Empty Cart State */
        .cart-empty-state {
            grid-column: 1 / -1;
            padding: 70px 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .empty-cart-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #ffffff;
            color: #94a3b8;
            border: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-cart-icon-circle svg {
            width: 30px;
            height: 30px;
        }

        .empty-cart-title {
            font-size: 1.0625rem;
            font-weight: 800;
            color: #1e293b;
        }

        .empty-cart-desc {
            font-size: 0.875rem;
            color: #64748b;
            max-width: 360px;
            line-height: 1.55;
        }

        /* Cart Item Row Card (Fits 2 cards in one row) */
        .cart-item-card {
            background: #ffffff;
            border: 1.5px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
            min-height: 136px;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .cart-item-card:hover {
            border-color: #a7f3d0;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.07);
        }

        .cart-item-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .cart-item-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #09090b;
            line-height: 1.35;
            margin-bottom: 3px;
        }

        .cart-item-variant {
            font-size: 0.8125rem;
            color: #475569;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .cart-item-badges-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cart-item-sku-tag {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #475569;
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 600;
        }

        .cart-item-limit-tag {
            font-size: 0.6875rem;
            color: #065f46;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 2px 7px;
            border-radius: 5px;
            font-weight: 600;
        }

        .cart-item-remove-btn {
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            color: #94a3b8;
            cursor: pointer;
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .cart-item-remove-btn:hover {
            color: #ef4444;
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .cart-item-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px dashed var(--border-subtle);
            gap: 12px;
            flex-wrap: wrap;
        }

        .cart-item-stepper-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stepper-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }

        .cart-item-stepper {
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 1.5px solid var(--border-subtle);
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .cart-btn-qty {
            width: 32px;
            height: 32px;
            background: #ffffff;
            border: none;
            color: #0f172a;
            cursor: pointer;
            font-size: 1.0625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .cart-btn-qty:hover:not(:disabled) {
            background: #f1f5f9;
            color: #059669;
        }

        .cart-btn-qty:disabled {
            color: #cbd5e1;
            cursor: not-allowed;
            background: #f8fafc;
        }

        .cart-input-qty {
            width: 44px;
            height: 32px;
            border: none;
            background: transparent;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            font-weight: 800;
            text-align: center;
            color: #09090b;
        }

        .cart-input-qty:focus {
            outline: none;
            background: #f8fafc;
        }

        .cart-unit-label {
            font-size: 0.7813rem;
            font-weight: 600;
            color: #475569;
        }

        .cart-item-price-stack {
            text-align: right;
        }

        .cart-line-subtotal {
            font-size: 1rem;
            font-weight: 800;
            color: #059669;
            font-family: var(--font-mono);
        }

        .cart-line-unit-rate {
            font-size: 0.7188rem;
            color: var(--text-muted);
        }

        /* Cart Footer / Checkout Area */
        .side-cart-footer {
            padding: 18px 24px;
            border-top: 1px solid var(--border-subtle);
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.02);
        }

        .cart-footer-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 20px;
            align-items: start;
        }

        .cart-summary-block {
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #f8fafc;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .cart-summary-line {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .cart-summary-total {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            padding-top: 8px;
            border-top: 1px dashed var(--border-subtle);
        }

        .cart-summary-total strong {
            font-size: 0.9375rem;
            color: #09090b;
            font-weight: 800;
        }

        .cart-total-amount {
            font-size: 1.3125rem;
            font-weight: 800;
            color: #059669;
            font-family: var(--font-mono);
        }

        .cart-purpose-wrap {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .cart-purpose-label {
            font-size: 0.7188rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .cart-purpose-input {
            width: 100%;
            padding: 9px 12px;
            font-size: 0.8438rem;
            border: 1.5px solid var(--border-subtle);
            border-radius: 9px;
            background: #f8fafc;
            color: var(--text-primary);
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .cart-purpose-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        .cart-action-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .submit-ris-btn {
            flex: 1;
            background: #059669;
            color: #ffffff;
            border: none;
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .submit-ris-btn:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
        }

        .submit-ris-btn:disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            box-shadow: none;
        }

        .close-drawer-bottom-btn {
            width: auto;
            flex-shrink: 0;
            background: #f8fafc;
            color: #475569;
            border: 1px solid var(--border-subtle);
            padding: 13px 18px;
            border-radius: 12px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .close-drawer-bottom-btn:hover {
            background: #f1f5f9;
            color: #09090b;
            border-color: #cbd5e1;
        }

        @keyframes badgePulseAnim {
            0% { transform: scale(1); }
            40% { transform: scale(1.35); }
            100% { transform: scale(1); }
        }

        .badge-pulse {
            animation: badgePulseAnim 0.35s ease-out;
        }

        /* Floating Cart Trigger Button (Always accessible) */
        .floating-cart-btn {
            position: fixed;
            bottom: 84px;
            right: 28px;
            z-index: 100;
            background: #0f172a;
            color: #ffffff;
            border: 2px solid #334155;
            padding: 12px 20px;
            border-radius: 9999px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            font-size: 0.875rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.22s ease;
        }

        .floating-cart-btn:hover {
            background: #059669;
            border-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(5, 150, 105, 0.35);
        }

        .floating-cart-btn .float-badge {
            background: #10b981;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-family: var(--font-mono);
            font-weight: 800;
        }

        /* Empty State for Grid */
        .grid-empty-state {
            grid-column: 1 / -1;
            padding: 60px 24px;
            text-align: center;
            background: #ffffff;
            border: 1px dashed var(--border-subtle);
            border-radius: 16px;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            bottom: 90px;
            left: 24px;
            z-index: 400;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast-pill {
            background: #09090b;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            animation: toastSlideUp 0.25s ease;
        }

        @keyframes toastSlideUp {
            from { transform: translateY(16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* RIS Preview Modal */
        .ris-modal-backdrop {
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

        .ris-modal-backdrop.open {
            display: flex;
        }

        .ris-modal-box {
            background: #ffffff;
            border-radius: 18px;
            width: 100%;
            max-width: 1240px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: modalFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from { transform: scale(0.96); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .ris-modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }

        .ris-modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        .ris-slip-paper {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 24px;
            border-radius: 8px;
            font-size: 0.8125rem;
        }

        .ris-paper-header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 2px solid #0f172a;
            margin-bottom: 16px;
        }

        .ris-paper-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .ris-paper-table th, .ris-paper-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }

        .ris-paper-table th {
            background: #f1f5f9;
            font-weight: 700;
        }

        .ris-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            background: #ffffff;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .side-cart-col {
                width: 100vw;
                max-width: 100vw;
            }

            .cart-items-body {
                grid-template-columns: 1fr;
            }

            .cart-footer-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .cart-action-buttons {
                flex-direction: column;
            }

            .close-drawer-bottom-btn {
                width: 100%;
            }
        }

        @media (max-width: 640px) {
            .ris-container {
                padding: 16px 16px 120px;
            }

            .floating-cart-btn {
                right: 16px;
                bottom: 80px;
                padding: 10px 16px;
                font-size: 0.8125rem;
            }
        }
    </style>
</head>
<body>

    @php
        $user = auth()->user()->load('office');
        $userInitials = strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1) . substr($user->last_name ?? '', 0, 1)) ?: 'ST';

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
    @endphp

    <!-- Staff Top Header -->
    <header class="staff-topbar">
        <a href="{{ route('staff.inventory') }}" class="brand-group" title="Back to Staff Inventory">
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

        <div class="topbar-actions">
            <!-- Click-to-Toggle Cart Button -->
            <button type="button" class="cart-toggle-btn" id="openCartTopBtn" title="Click to Review RIS Selected Items">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span>RIS Cart</span>
                <span class="cart-badge-pill" id="cartTopCounter">0</span>
            </button>

            <div class="user-pill">
                <div class="user-avatar">{{ $userInitials }}</div>
                <span class="user-name-label">{{ $user->full_name }}</span>
                <span class="role-pill">{{ $user->account_level?->label() ?? 'Staff' }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="ris-container">

        <!-- Breadcrumbs & Header -->
        <div class="ris-header-row">
            <div class="ris-title-area">
                <div class="breadcrumb-wrap">
                    <a href="{{ route('staff.requisition.index') }}" class="back-nav-link">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        <span>Requisition Options</span>
                    </a>
                    <span class="crumb-sep">/</span>
                    <span class="crumb-active">RIS Stock Selection</span>
                </div>

                <h1>
                    <span>Requisition and Issue Slip (RIS)</span>
                    <span class="form-code-badge">APPENDIX 63</span>
                </h1>
                <p class="ris-subtitle">Select multiple in-stock supplies and inventory items from your office to generate an official RIS request slip.</p>
            </div>

            <!-- Designated Office Station Pill -->
            <div class="office-duty-card">
                <div class="station-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div class="station-text">
                    <strong>{{ $activeOffice ? $activeOffice->office_name : 'No Office Assigned (Floating Staff)' }}</strong>
                    <span>{{ $activeOffice ? 'CODE: ' . $activeOffice->office_code : 'GENERAL POOL' }}</span>
                </div>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="catalog-toolbar">
            <div class="toolbar-main-row">
                <!-- Search Box -->
                <div class="search-box-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input 
                        type="text" 
                        id="risSearchInput" 
                        class="search-input" 
                        placeholder="Search items by name, SKU, barcode, specification, or room..."
                        autocomplete="off"
                    >
                </div>

                <!-- Dropdown Search Filters -->
                <div class="filters-group">
                    <div style="min-width: 180px;">
                        <x-dropdown-search 
                            name="category_filter"
                            id="risCategorySelect"
                            :options="$categoryFilterOptions"
                            valueKey="id"
                            labelKey="name"
                            placeholder="All Categories"
                            searchPlaceholder="Filter category..."
                            :allowClear="true"
                        />
                    </div>

                    <div style="min-width: 170px;">
                        <x-dropdown-search 
                            name="status_filter"
                            id="risStatusSelect"
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
                            id="risSortSelect"
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
                <span id="risCountText">Showing {{ $officeInventories->count() }} of {{ $officeInventories->count() }} available stock items</span>
                <button type="button" class="clear-filters-btn" id="risResetFiltersBtn" style="display: none;">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    <span>Reset Filters</span>
                </button>
            </div>
        </div>

        <!-- Full-Width E-Commerce Product Cards Grid -->
        <div class="catalog-main-col">
            <div class="product-grid" id="risProductGrid">
                @forelse($officeInventories as $oi)
                    @php
                        $variant = $oi->variant;
                        $item = $variant?->inventoryItem;
                        $itemName = $item?->name ?? 'Uncataloged Item';
                        $variantName = $variant?->variant_name ?? 'Standard Edition';
                        $category = $item?->category ?? 'General Supplies';
                        $sku = $variant?->sku ?: ($variant?->barcode ?: ($item?->item_code ?: 'N/A'));
                        $barcode = $variant?->barcode ?: 'N/A';
                        $unit = $variant?->effective_unit ?? ($variant?->unit ?? ($item?->unit ?? 'units'));
                        $reorderLevel = (int) ($variant?->reorder_level ?? 5);
                        $location = $variant?->location ?? ($item?->location ?? 'General Storage');
                        $unitCost = (float) ($variant?->unit_cost ?? ($item?->unit_cost ?? 0));
                        $availableQty = (int) $oi->quantity;

                        // Stock status
                        $status = 'In Stock';
                        $statusClass = 'status-badge-instock';
                        $dotClass = 'dot-instock';
                        $progressClass = 'progress-instock';

                        if ($availableQty <= 0) {
                            $status = 'Out of Stock';
                            $statusClass = 'status-badge-outofstock';
                            $dotClass = 'dot-outofstock';
                            $progressClass = 'progress-outofstock';
                        } elseif ($availableQty <= $reorderLevel) {
                            $status = 'Low Stock';
                            $statusClass = 'status-badge-lowstock';
                            $dotClass = 'dot-lowstock';
                            $progressClass = 'progress-lowstock';
                        }

                        $stockPercent = $reorderLevel > 0 ? min(100, max(8, ($availableQty / ($reorderLevel * 3)) * 100)) : 100;

                        // Theme Gradients
                        $catLower = strtolower($category);
                        $iconColor = '#059669';
                        $bannerGradient = 'linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%)';
                        if (str_contains($catLower, 'office') || str_contains($catLower, 'paper')) {
                            $iconColor = '#2563eb';
                            $bannerGradient = 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)';
                        } elseif (str_contains($catLower, 'tech') || str_contains($catLower, 'it')) {
                            $iconColor = '#7c3aed';
                            $bannerGradient = 'linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%)';
                        } elseif (str_contains($catLower, 'med') || str_contains($catLower, 'health')) {
                            $iconColor = '#e11d48';
                            $bannerGradient = 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)';
                        }
                    @endphp

                    <article 
                        class="product-card" 
                        id="card_{{ $oi->id }}"
                        data-id="{{ $oi->id }}"
                        data-name="{{ strtolower($itemName) }}"
                        data-variant="{{ strtolower($variantName) }}"
                        data-sku="{{ strtolower($sku) }}"
                        data-category="{{ strtolower($category) }}"
                        data-status="{{ $status }}"
                        data-quantity="{{ $availableQty }}"
                        data-cost="{{ $unitCost }}"
                        data-unit="{{ $unit }}"
                        data-location="{{ strtolower($location) }}"
                    >
                        <!-- Banner -->
                        <div class="card-hero-banner" style="background: {{ $bannerGradient }};">
                            <div class="hero-badge-top-left">
                                <span class="category-chip">{{ $category }}</span>
                            </div>
                            <div class="hero-badge-top-right">
                                <span class="status-badge {{ $statusClass }}">
                                    <span class="status-indicator-dot {{ $dotClass }}"></span>
                                    <span>{{ $status }}</span>
                                </span>
                            </div>
                            <div class="hero-category-icon-wrap" style="color: {{ $iconColor }};">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                    <path d="m3.3 7 8.7 5 8.7-5"/>
                                    <path d="M12 22V12"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="card-content">
                            <div class="sku-row">
                                <span class="sku-code-tag">SKU: {{ $sku }}</span>
                                <span style="font-size: 0.7188rem; color: #64748b; font-weight: 600;">{{ $unit }}</span>
                            </div>

                            <h2 class="product-title" title="{{ $itemName }}">{{ $itemName }}</h2>
                            <p class="variant-spec-label" title="{{ $variantName }}">{{ $variantName }}</p>

                            <div class="card-location-row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ $location }}</span>
                            </div>

                            <!-- Stock Meter -->
                            <div class="stock-meter-box">
                                <div class="stock-meter-header">
                                    <div class="stock-balance-wrap">
                                        <span class="stock-large-num">{{ number_format($availableQty) }}</span>
                                        <span class="stock-unit-label">{{ $unit }} in stock</span>
                                    </div>
                                    <span class="stock-threshold-label">Min: {{ $reorderLevel }}</span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill {{ $progressClass }}" style="width: {{ $stockPercent }}%;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions: Price + Add to Cart -->
                        <div class="card-footer">
                            <div class="price-stack">
                                <span class="unit-price-text">₱{{ number_format($unitCost, 2) }}</span>
                                <span class="price-unit-subtext">Per {{ $unit }}</span>
                            </div>

                            <!-- Add to Cart Button -->
                            <button 
                                type="button" 
                                class="add-cart-btn" 
                                id="addBtn_{{ $oi->id }}"
                                data-add-to-cart
                                data-id="{{ $oi->id }}"
                                data-name="{{ $itemName }}"
                                data-variant="{{ $variantName }}"
                                data-sku="{{ $sku }}"
                                data-unit="{{ $unit }}"
                                data-max="{{ $availableQty }}"
                                data-cost="{{ $unitCost }}"
                                {{ $availableQty <= 0 ? 'disabled' : '' }}
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>{{ $availableQty <= 0 ? 'Out of Stock' : 'Add to Cart' }}</span>
                            </button>

                            <!-- Active Stepper (when item is in cart) -->
                            <div class="card-qty-stepper" id="stepper_{{ $oi->id }}">
                                <button type="button" class="stepper-btn" data-step-minus="{{ $oi->id }}">−</button>
                                <span class="stepper-val" id="stepperVal_{{ $oi->id }}">1</span>
                                <button type="button" class="stepper-btn" data-step-plus="{{ $oi->id }}" data-max="{{ $availableQty }}">+</button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="grid-empty-state">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#94a3b8" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="m9 16 3-3 3 3"/></svg>
                        <h3 style="margin-top: 12px; font-size: 1.125rem; font-weight: 700;">No Office Stock Found</h3>
                        <p style="color: #64748b; font-size: 0.875rem; margin-top: 4px;">There are currently no items in this office custody available for RIS requisition.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Floating Trigger Button (Bottom-Right) -->
    <button type="button" class="floating-cart-btn" id="floatingCartBtn" title="Review Selected RIS Items">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
        <span>Review Cart</span>
        <span class="float-badge" id="floatCartCounter">0</span>
    </button>

    <!-- Drawer Backdrop (Click to Dismiss) -->
    <div class="cart-drawer-backdrop" id="cartBackdrop"></div>

    <!-- ================= WIDE SLIDE-OVER SIDE CART DRAWER (530px) ================= -->
    <aside class="side-cart-col" id="sideCartPanel" aria-label="RIS Requisition Cart Drawer">
        <!-- Cart Header -->
        <div class="side-cart-header">
            <div class="cart-header-title">
                <div class="cart-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
                <div class="cart-title-text">
                    <strong>RIS Request Cart</strong>
                    <span id="cartItemCountLabel">0 ITEMS SELECTED</span>
                </div>
            </div>

            <div class="cart-header-actions">
                <button type="button" class="clear-cart-btn" id="clearCartBtn" title="Clear all items in cart">Clear</button>
                <button type="button" class="drawer-close-btn" id="closeCartBtn" title="Close Cart (Esc)" aria-label="Close Cart">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>

        <!-- Cart Items Scrollable List (Wide View) -->
        <div class="cart-items-body" id="cartItemsList">
            <!-- Dynamic Cart Items Rendered Here via JavaScript -->
            <div class="cart-empty-state" id="cartEmptyState">
                <div class="empty-cart-icon-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </div>
                <h4 class="empty-cart-title">Your RIS Cart is Empty</h4>
                <p class="empty-cart-desc">Click <strong>+ Add to Cart</strong> on any stock item in the catalog to build your requisition slip.</p>
            </div>
        </div>

        <!-- Cart Footer & Summary (Grid Layout in Double-Width Drawer) -->
        <div class="side-cart-footer" id="cartFooterArea">
            <div class="cart-footer-grid">
                <!-- Purpose / Remarks Input -->
                <div class="cart-purpose-wrap">
                    <label class="cart-purpose-label" for="risPurposeInput">Purpose / Requisition Notes</label>
                    <input 
                        type="text" 
                        id="risPurposeInput" 
                        class="cart-purpose-input" 
                        placeholder="e.g. Regular office consumption & operations"
                        value="Official usage for {{ $activeOffice ? $activeOffice->office_name : 'office operations' }}"
                    >
                    <span style="font-size: 0.7188rem; color: #64748b; margin-top: 3px;">Printed on the header of the official RIS slip</span>
                </div>

                <!-- Summary Breakdown Block -->
                <div class="cart-summary-block">
                    <div class="cart-summary-line">
                        <span>Distinct Catalog SKUs:</span>
                        <strong id="cartSummaryItemsCount">0 items</strong>
                    </div>

                    <div class="cart-summary-line">
                        <span>Total Units Requested:</span>
                        <strong id="cartSummaryTotalUnits">0 units</strong>
                    </div>

                    <div class="cart-summary-total">
                        <strong>Estimated Total Amount:</strong>
                        <span class="cart-total-amount" id="cartSummaryTotalAmount">₱0.00</span>
                    </div>
                </div>
            </div>

            <div class="cart-action-buttons">
                <!-- Generate Button -->
                <button type="button" class="submit-ris-btn" id="generateRisBtn" disabled>
                    <span>Review & Generate RIS Slip</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>

                <!-- Continue Browsing Button -->
                <button type="button" class="close-drawer-bottom-btn" id="continueBrowsingBtn">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Continue Selecting Items</span>
                </button>
            </div>
        </div>
    </aside>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Official RIS Slip Preview Modal -->
    <div class="ris-modal-backdrop" id="risModalBackdrop">
        <div class="ris-modal-box">
            <div class="ris-modal-header">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #09090b;">Requisition & Issue Slip (RIS) Summary</h3>
                    <span style="font-size: 0.75rem; color: #64748b; font-family: var(--font-mono);">APPENDIX 63 • READY FOR SUBMISSION</span>
                </div>
                <button type="button" id="closeRisModalBtn" style="background: none; border: none; cursor: pointer; color: #64748b;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <div class="ris-modal-body">
                <div class="ris-slip-paper" id="printableRisSlip">
                    <div class="ris-paper-header">
                        <h4 style="font-size: 1rem; font-weight: 800; text-transform: uppercase;">PROVINCIAL GENERAL SERVICES OFFICE</h4>
                        <div style="font-size: 0.75rem; color: #475569;">Province Warehouse Inventory & Supply Custody</div>
                        <h2 style="font-size: 1.25rem; font-weight: 800; margin-top: 8px;">REQUISITION AND ISSUE SLIP (RIS)</h2>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.8125rem;">
                        <div>
                            <div><strong>Division/Office:</strong> {{ $activeOffice ? $activeOffice->office_name : 'General Office' }}</div>
                            <div><strong>Office Code:</strong> {{ $activeOffice ? $activeOffice->office_code : 'N/A' }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div><strong>Date:</strong> {{ date('F d, Y') }}</div>
                            <div><strong>Requisitioner:</strong> {{ $user->full_name }}</div>
                        </div>
                    </div>

                    <div style="margin-bottom: 8px;">
                        <strong>Purpose:</strong> <span id="modalPurposeDisplay">Regular office supplies</span>
                    </div>

                    <table class="ris-paper-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">#</th>
                                <th>Item Description & Specification</th>
                                <th>SKU</th>
                                <th style="width: 70px; text-align: center;">Unit</th>
                                <th style="width: 70px; text-align: center;">Req Qty</th>
                                <th style="text-align: right;">Unit Cost</th>
                                <th style="text-align: right;">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody id="modalRisItemsTableBody">
                            <!-- Populated dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right; font-weight: 700;">TOTALS:</td>
                                <td id="modalTotalUnitsTd" style="text-align: center; font-weight: 800;">0</td>
                                <td></td>
                                <td id="modalTotalCostTd" style="text-align: right; font-weight: 800; color: #059669;">₱0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Confirm Request & Save Section inside ris-modal-body -->
                <div class="confirm-save-box" id="confirmSaveBox" style="margin-top: 18px; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1.5px solid #86efac; border-radius: 14px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 18px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.08);">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: #059669; color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(5, 150, 105, 0.25);">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 0.9375rem; color: #065f46; font-weight: 800;">Confirm & Save Requisition Request</strong>
                            <span style="font-size: 0.7813rem; color: #166534; line-height: 1.4; display: block; margin-top: 2px;">
                                Submits this request to database records and forwards it for PGSO verification & warehouse release.
                            </span>
                        </div>
                    </div>

                    <button type="button" id="confirmSaveRequestBtn" style="background: #059669; color: #ffffff; border: none; padding: 12px 24px; border-radius: 11px; font-weight: 800; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35); transition: all 0.2s ease; flex-shrink: 0;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span id="confirmSaveBtnText">Confirm & Save Request</span>
                    </button>
                </div>

                <!-- Success Confirmation Banner (Hidden by default, shown after saving) -->
                <div id="saveSuccessBanner" style="display: none; margin-top: 18px; background: #ffffff; border: 2px solid #10b981; border-radius: 14px; padding: 22px 24px; text-align: center; flex-direction: column; align-items: center; gap: 12px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div>
                        <h4 style="font-size: 1.125rem; font-weight: 800; color: #065f46;" id="successReqNumberHeading">Requisition Request Saved!</h4>
                        <p style="font-size: 0.8438rem; color: #64748b; margin-top: 4px;" id="successReqMessage">Your requisition has been logged to the database and is now pending PGSO approval.</p>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-top: 8px; flex-wrap: wrap;">
                        <a href="{{ route('staff.request-stat') }}" class="btn-action-view" style="background: #059669; color: #ffffff; border: none; padding: 10px 18px; border-radius: 9px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M13 6h3a2 2 0 0 1 2 2v7"/><line x1="6" x2="6" y1="9" y2="21"/></svg>
                            <span>Monitor in Request Stat</span>
                        </a>
                        <a href="{{ route('staff.requisition.ris-form') }}" class="btn-action-view" style="background: #0f172a; color: #ffffff; border: none; padding: 10px 18px; border-radius: 9px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Official Form</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ris-modal-footer">
                <button type="button" id="dismissModalBtn" style="padding: 9px 16px; border: 1px solid #e2e8f0; background: #fff; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    Edit Items
                </button>
                <a href="{{ route('staff.requisition.ris-form') }}" id="openPrintableRisFormBtn" style="padding: 9px 16px; border: 1px solid #0f172a; background: #0f172a; color: #fff; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; text-decoration: none; font-size: 0.8125rem;">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>Official Form Template</span>
                </a>
                <button type="button" id="confirmPrintRisBtn" style="padding: 9px 18px; border: none; background: #059669; color: #fff; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    <span>Print Quick Slip</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Bottom Navigation -->
    <x-staffNav />

    <!-- Live Side Cart & Interactive Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cart State Management
            let cart = {}; // Key: itemId, Value: { id, name, variant, sku, unit, cost, max, quantity }

            // DOM Elements
            const cartItemsList = document.getElementById('cartItemsList');
            const cartEmptyState = document.getElementById('cartEmptyState');
            const cartTopCounter = document.getElementById('cartTopCounter');
            const floatCartCounter = document.getElementById('floatCartCounter');
            const cartItemCountLabel = document.getElementById('cartItemCountLabel');
            const cartSummaryItemsCount = document.getElementById('cartSummaryItemsCount');
            const cartSummaryTotalUnits = document.getElementById('cartSummaryTotalUnits');
            const cartSummaryTotalAmount = document.getElementById('cartSummaryTotalAmount');
            const generateRisBtn = document.getElementById('generateRisBtn');
            const clearCartBtn = document.getElementById('clearCartBtn');
            const sideCartPanel = document.getElementById('sideCartPanel');
            const cartBackdrop = document.getElementById('cartBackdrop');
            const openCartTopBtn = document.getElementById('openCartTopBtn');
            const floatingCartBtn = document.getElementById('floatingCartBtn');
            const closeCartBtn = document.getElementById('closeCartBtn');
            const continueBrowsingBtn = document.getElementById('continueBrowsingBtn');
            const toastContainer = document.getElementById('toastContainer');
            const risPurposeInput = document.getElementById('risPurposeInput');

            // Modal Elements
            const risModalBackdrop = document.getElementById('risModalBackdrop');
            const closeRisModalBtn = document.getElementById('closeRisModalBtn');
            const dismissModalBtn = document.getElementById('dismissModalBtn');
            const confirmPrintRisBtn = document.getElementById('confirmPrintRisBtn');
            const modalPurposeDisplay = document.getElementById('modalPurposeDisplay');
            const modalRisItemsTableBody = document.getElementById('modalRisItemsTableBody');
            const modalTotalUnitsTd = document.getElementById('modalTotalUnitsTd');
            const modalTotalCostTd = document.getElementById('modalTotalCostTd');

            // Currency Formatter
            function formatCurrency(val) {
                return '₱' + Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Show Toast Alert
            function showToast(message, icon = '✓') {
                const toast = document.createElement('div');
                toast.className = 'toast-pill';
                toast.innerHTML = `<span>${icon}</span> <span>${message}</span>`;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toast.style.transition = 'all 0.3s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px)';
                    setTimeout(() => toast.remove(), 300);
                }, 2200);
            }

            // Drawer Toggle Controls
            function openCart() {
                sideCartPanel.classList.add('open');
                cartBackdrop.classList.add('open');
                document.body.style.overflow = 'hidden';
            }

            function closeCart() {
                sideCartPanel.classList.remove('open');
                cartBackdrop.classList.remove('open');
                document.body.style.overflow = '';
            }

            function toggleCart() {
                if (sideCartPanel.classList.contains('open')) {
                    closeCart();
                } else {
                    openCart();
                }
            }

            if (openCartTopBtn) openCartTopBtn.addEventListener('click', toggleCart);
            if (floatingCartBtn) floatingCartBtn.addEventListener('click', toggleCart);
            if (closeCartBtn) closeCartBtn.addEventListener('click', closeCart);
            if (continueBrowsingBtn) continueBrowsingBtn.addEventListener('click', closeCart);
            if (cartBackdrop) cartBackdrop.addEventListener('click', closeCart);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sideCartPanel.classList.contains('open')) {
                    closeCart();
                }
            });

            // Add Item to Cart (Does NOT auto-open cart, allowing continuous item selection)
            function addToCart(itemData) {
                const id = String(itemData.id);
                if (cart[id]) {
                    if (cart[id].quantity < cart[id].max) {
                        cart[id].quantity += 1;
                        showToast(`Updated "${cart[id].name}" to ${cart[id].quantity} ${cart[id].unit}`);
                    } else {
                        showToast(`Reached maximum office stock limit (${cart[id].max} ${cart[id].unit})`, '⚠️');
                        return;
                    }
                } else {
                    cart[id] = {
                        id: id,
                        name: itemData.name,
                        variant: itemData.variant,
                        sku: itemData.sku,
                        unit: itemData.unit,
                        cost: parseFloat(itemData.cost) || 0,
                        max: parseInt(itemData.max) || 1,
                        quantity: 1,
                    };
                    showToast(`Added "${cart[id].name}" to RIS Cart`);
                }

                syncCardUI(id);
                renderCart();
            }

            // Remove Item from Cart
            function removeFromCart(id) {
                id = String(id);
                if (cart[id]) {
                    const name = cart[id].name;
                    delete cart[id];
                    syncCardUI(id);
                    renderCart();
                    showToast(`Removed "${name}" from cart`, '✕');
                }
            }

            // Update Item Quantity in Cart
            function updateQuantity(id, newQty) {
                id = String(id);
                if (!cart[id]) return;

                newQty = parseInt(newQty);
                if (isNaN(newQty) || newQty <= 0) {
                    removeFromCart(id);
                    return;
                }

                if (newQty > cart[id].max) {
                    newQty = cart[id].max;
                    showToast(`Limited to available office stock of ${cart[id].max} ${cart[id].unit}`, '⚠️');
                }

                cart[id].quantity = newQty;
                syncCardUI(id);
                renderCart();
            }

            // Sync Product Card in Grid with Cart State
            function syncCardUI(id) {
                const addBtn = document.getElementById(`addBtn_${id}`);
                const stepper = document.getElementById(`stepper_${id}`);
                const stepperVal = document.getElementById(`stepperVal_${id}`);
                const card = document.getElementById(`card_${id}`);

                if (!card) return;

                if (cart[id]) {
                    card.classList.add('in-cart');
                    if (addBtn) addBtn.style.display = 'none';
                    if (stepper) {
                        stepper.classList.add('active');
                        if (stepperVal) stepperVal.textContent = cart[id].quantity;
                    }
                } else {
                    card.classList.remove('in-cart');
                    if (addBtn) addBtn.style.display = 'inline-flex';
                    if (stepper) stepper.classList.remove('active');
                }
            }

            // Render Side Cart UI (Double-Width 2-Column Grid Layout)
            function renderCart() {
                const itemKeys = Object.keys(cart);
                const distinctCount = itemKeys.length;
                let totalUnits = 0;
                let totalCost = 0;

                // Clear dynamic items while preserving empty state element
                const existingItemCards = cartItemsList.querySelectorAll('.cart-item-card');
                existingItemCards.forEach(el => el.remove());

                if (distinctCount === 0) {
                    cartEmptyState.style.display = 'flex';
                    generateRisBtn.disabled = true;
                } else {
                    cartEmptyState.style.display = 'none';
                    generateRisBtn.disabled = false;

                    itemKeys.forEach(id => {
                        const item = cart[id];
                        const lineTotal = item.quantity * item.cost;
                        totalUnits += item.quantity;
                        totalCost += lineTotal;

                        const itemCard = document.createElement('div');
                        itemCard.className = 'cart-item-card';
                        itemCard.id = `cartItemRow_${id}`;

                        itemCard.innerHTML = `
                            <div class="cart-item-top">
                                <div class="cart-item-info">
                                    <div class="cart-item-name" title="${item.name}">${item.name}</div>
                                    <div class="cart-item-variant" title="${item.variant}">${item.variant}</div>
                                    <div class="cart-item-badges-wrap">
                                        <span class="cart-item-sku-tag">SKU: ${item.sku}</span>
                                        <span class="cart-item-limit-tag">Stock: ${item.max} ${item.unit}</span>
                                    </div>
                                </div>
                                <button type="button" class="cart-item-remove-btn" data-cart-remove="${id}" title="Remove this item">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </div>
                            <div class="cart-item-bottom">
                                <div class="cart-item-stepper-wrap">
                                    <span class="stepper-label">Qty:</span>
                                    <div class="cart-item-stepper">
                                        <button type="button" class="cart-btn-qty" data-cart-minus="${id}">−</button>
                                        <input type="number" class="cart-input-qty" data-cart-input="${id}" value="${item.quantity}" min="1" max="${item.max}">
                                        <button type="button" class="cart-btn-qty" data-cart-plus="${id}" ${item.quantity >= item.max ? 'disabled' : ''}>+</button>
                                    </div>
                                    <span class="cart-unit-label">${item.unit}</span>
                                </div>
                                <div class="cart-item-price-stack">
                                    <div class="cart-line-subtotal">${formatCurrency(lineTotal)}</div>
                                    <div class="cart-line-unit-rate">${item.quantity} ${item.unit} @ ${formatCurrency(item.cost)}</div>
                                </div>
                            </div>
                        `;

                        cartItemsList.appendChild(itemCard);
                    });
                }

                // Update summary counters
                cartTopCounter.textContent = distinctCount;
                if (floatCartCounter) floatCartCounter.textContent = distinctCount;
                cartItemCountLabel.textContent = `${distinctCount} ITEMS SELECTED`;
                cartSummaryItemsCount.textContent = `${distinctCount} item${distinctCount === 1 ? '' : 's'}`;
                cartSummaryTotalUnits.textContent = `${totalUnits} unit${totalUnits === 1 ? '' : 's'}`;
                cartSummaryTotalAmount.textContent = formatCurrency(totalCost);

                // Subtle pulse feedback on badges
                if (cartTopCounter) {
                    cartTopCounter.classList.remove('badge-pulse');
                    void cartTopCounter.offsetWidth;
                    cartTopCounter.classList.add('badge-pulse');
                }
                if (floatCartCounter) {
                    floatCartCounter.classList.remove('badge-pulse');
                    void floatCartCounter.offsetWidth;
                    floatCartCounter.classList.add('badge-pulse');
                }
            }

            // Click Handlers Delegation (Add, Increment, Decrement, Remove)
            document.addEventListener('click', (e) => {
                // Add to Cart Trigger
                const addBtn = e.target.closest('[data-add-to-cart]');
                if (addBtn && !addBtn.disabled) {
                    addToCart({
                        id: addBtn.dataset.id,
                        name: addBtn.dataset.name,
                        variant: addBtn.dataset.variant,
                        sku: addBtn.dataset.sku,
                        unit: addBtn.dataset.unit,
                        cost: addBtn.dataset.cost,
                        max: addBtn.dataset.max,
                    });
                    return;
                }

                // In-Card Stepper Minus
                const stepMinus = e.target.closest('[data-step-minus]');
                if (stepMinus) {
                    const id = stepMinus.dataset.stepMinus;
                    if (cart[id]) {
                        updateQuantity(id, cart[id].quantity - 1);
                    }
                    return;
                }

                // In-Card Stepper Plus
                const stepPlus = e.target.closest('[data-step-plus]');
                if (stepPlus) {
                    const id = stepPlus.dataset.stepPlus;
                    if (cart[id]) {
                        updateQuantity(id, cart[id].quantity + 1);
                    }
                    return;
                }

                // Side Cart Stepper Minus
                const cartMinus = e.target.closest('[data-cart-minus]');
                if (cartMinus) {
                    const id = cartMinus.dataset.cartMinus;
                    if (cart[id]) {
                        updateQuantity(id, cart[id].quantity - 1);
                    }
                    return;
                }

                // Side Cart Stepper Plus
                const cartPlus = e.target.closest('[data-cart-plus]');
                if (cartPlus) {
                    const id = cartPlus.dataset.cartPlus;
                    if (cart[id]) {
                        updateQuantity(id, cart[id].quantity + 1);
                    }
                    return;
                }

                // Side Cart Remove Button
                const cartRemove = e.target.closest('[data-cart-remove]');
                if (cartRemove) {
                    const id = cartRemove.dataset.cartRemove;
                    removeFromCart(id);
                    return;
                }
            });

            // Cart Input Direct Change
            cartItemsList.addEventListener('change', (e) => {
                const qtyInput = e.target.closest('[data-cart-input]');
                if (qtyInput) {
                    const id = qtyInput.dataset.cartInput;
                    updateQuantity(id, qtyInput.value);
                }
            });

            // Clear All Cart Items
            clearCartBtn.addEventListener('click', () => {
                const keys = Object.keys(cart);
                if (keys.length === 0) return;

                if (confirm('Are you sure you want to clear all items from your RIS cart?')) {
                    keys.forEach(id => syncCardUI(id));
                    cart = {};
                    renderCart();
                    showToast('Cleared all items in RIS cart');
                }
            });

            // ================= REAL-TIME FILTERING & SEARCH =================
            const searchInput = document.getElementById('risSearchInput');
            const categorySelect = document.getElementById('risCategorySelect');
            const statusSelect = document.getElementById('risStatusSelect');
            const sortSelect = document.getElementById('risSortSelect');
            const countText = document.getElementById('risCountText');
            const resetBtn = document.getElementById('risResetFiltersBtn');
            const gridContainer = document.getElementById('risProductGrid');
            const cards = Array.from(gridContainer.querySelectorAll('.product-card'));
            const totalCardsCount = cards.length;

            function applyFilters() {
                const query = (searchInput?.value || '').trim().toLowerCase();
                const selectedCat = (categorySelect?.value || '').trim().toLowerCase();
                const selectedStatus = (statusSelect?.value || '').trim().toLowerCase();
                const selectedSort = sortSelect?.value || 'stock-desc';

                let visibleCards = [];

                cards.forEach(card => {
                    const name = card.dataset.name || '';
                    const variant = card.dataset.variant || '';
                    const sku = card.dataset.sku || '';
                    const category = card.dataset.category || '';
                    const status = (card.dataset.status || '').toLowerCase();
                    const location = card.dataset.location || '';

                    // Search check
                    const matchesQuery = !query || 
                        name.includes(query) || 
                        variant.includes(query) || 
                        sku.includes(query) || 
                        category.includes(query) || 
                        location.includes(query);

                    // Category check
                    const matchesCategory = !selectedCat || category === selectedCat;

                    // Status check
                    const matchesStatus = !selectedStatus || status === selectedStatus;

                    if (matchesQuery && matchesCategory && matchesStatus) {
                        card.style.display = 'flex';
                        visibleCards.push(card);
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Sort visible cards
                visibleCards.sort((a, b) => {
                    const nameA = a.dataset.name || '';
                    const nameB = b.dataset.name || '';
                    const qtyA = parseInt(a.dataset.quantity) || 0;
                    const qtyB = parseInt(b.dataset.quantity) || 0;
                    const costA = parseFloat(a.dataset.cost) || 0;
                    const costB = parseFloat(b.dataset.cost) || 0;

                    switch (selectedSort) {
                        case 'stock-desc': return qtyB - qtyA;
                        case 'stock-asc': return qtyA - qtyB;
                        case 'name-asc': return nameA.localeCompare(nameB);
                        case 'name-desc': return nameB.localeCompare(nameA);
                        case 'cost-desc': return costB - costA;
                        case 'cost-asc': return costA - costB;
                        default: return 0;
                    }
                });

                // Re-append sorted cards
                visibleCards.forEach(card => gridContainer.appendChild(card));

                // Meta row count text
                countText.textContent = `Showing ${visibleCards.length} of ${totalCardsCount} available stock items`;

                const hasActiveFilters = Boolean(query || selectedCat || selectedStatus || (selectedSort !== 'stock-desc'));
                resetBtn.style.display = hasActiveFilters ? 'inline-flex' : 'none';
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (categorySelect) categorySelect.addEventListener('change', applyFilters);
            if (statusSelect) statusSelect.addEventListener('change', applyFilters);
            if (sortSelect) sortSelect.addEventListener('change', applyFilters);

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    if (categorySelect) {
                        categorySelect.value = '';
                        categorySelect.dispatchEvent(new Event('change'));
                    }
                    if (statusSelect) {
                        statusSelect.value = '';
                        statusSelect.dispatchEvent(new Event('change'));
                    }
                    if (sortSelect) {
                        sortSelect.value = 'stock-desc';
                        sortSelect.dispatchEvent(new Event('change'));
                    }
                    applyFilters();
                });
            }

            // ================= GENERATE RIS SUMMARY MODAL =================
            generateRisBtn.addEventListener('click', () => {
                const itemKeys = Object.keys(cart);
                if (itemKeys.length === 0) return;

                const purposeText = risPurposeInput.value.trim() || 'Official Office Supply Consumption';
                modalPurposeDisplay.textContent = purposeText;

                modalRisItemsTableBody.innerHTML = '';
                let totalUnits = 0;
                let totalCost = 0;

                itemKeys.forEach((id, index) => {
                    const item = cart[id];
                    const lineCost = item.quantity * item.cost;
                    totalUnits += item.quantity;
                    totalCost += lineCost;

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="text-align: center; color: #64748b; font-family: var(--font-mono);">${index + 1}</td>
                        <td>
                            <strong>${item.name}</strong>
                            <div style="font-size: 0.7188rem; color: #64748b;">${item.variant}</div>
                        </td>
                        <td style="font-family: var(--font-mono); font-size: 0.75rem;">${item.sku}</td>
                        <td style="text-align: center;">${item.unit}</td>
                        <td style="text-align: center; font-weight: 700; font-family: var(--font-mono);">${item.quantity}</td>
                        <td style="text-align: right; font-family: var(--font-mono);">${formatCurrency(item.cost)}</td>
                        <td style="text-align: right; font-weight: 700; font-family: var(--font-mono);">${formatCurrency(lineCost)}</td>
                    `;
                    modalRisItemsTableBody.appendChild(tr);
                });

                modalTotalUnitsTd.textContent = totalUnits;
                modalTotalCostTd.textContent = formatCurrency(totalCost);

                // Reset confirm save state on opening modal
                const confirmSaveBox = document.getElementById('confirmSaveBox');
                const saveSuccessBanner = document.getElementById('saveSuccessBanner');
                const confirmSaveRequestBtn = document.getElementById('confirmSaveRequestBtn');
                const confirmSaveBtnText = document.getElementById('confirmSaveBtnText');
                const successReqNumberHeading = document.getElementById('successReqNumberHeading');
                const successReqMessage = document.getElementById('successReqMessage');

                if (confirmSaveBox) confirmSaveBox.style.display = 'flex';
                if (saveSuccessBanner) saveSuccessBanner.style.display = 'none';
                if (confirmSaveRequestBtn) {
                    confirmSaveRequestBtn.disabled = false;
                    if (confirmSaveBtnText) confirmSaveBtnText.textContent = 'Confirm & Save Request';
                }

                risModalBackdrop.classList.add('open');
            });

            function closeModal() {
                risModalBackdrop.classList.remove('open');
            }

            if (closeRisModalBtn) closeRisModalBtn.addEventListener('click', closeModal);
            if (dismissModalBtn) dismissModalBtn.addEventListener('click', closeModal);
            risModalBackdrop.addEventListener('click', (e) => {
                if (e.target === risModalBackdrop) closeModal();
            });

            // Handle Confirm & Save Requisition Request to Database
            const confirmSaveRequestBtn = document.getElementById('confirmSaveRequestBtn');
            const confirmSaveBtnText = document.getElementById('confirmSaveBtnText');
            const confirmSaveBox = document.getElementById('confirmSaveBox');
            const saveSuccessBanner = document.getElementById('saveSuccessBanner');
            const successReqNumberHeading = document.getElementById('successReqNumberHeading');
            const successReqMessage = document.getElementById('successReqMessage');

            if (confirmSaveRequestBtn) {
                confirmSaveRequestBtn.addEventListener('click', () => {
                    const itemKeys = Object.keys(cart);
                    if (itemKeys.length === 0) {
                        alert('Your cart is empty. Please select items first.');
                        return;
                    }

                    confirmSaveRequestBtn.disabled = true;
                    if (confirmSaveBtnText) confirmSaveBtnText.textContent = 'Saving to Database...';

                    const purposeValue = (risPurposeInput ? risPurposeInput.value.trim() : '') || 'Official Office Supply Consumption';
                    const itemsPayload = itemKeys.map(id => ({
                        id: cart[id].id,
                        name: cart[id].name,
                        variant: cart[id].variant,
                        sku: cart[id].sku,
                        unit: cart[id].unit,
                        cost: cart[id].cost,
                        quantity: cart[id].quantity
                    }));

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                    fetch('{{ route('staff.requisition.ris.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            purpose: purposeValue,
                            items: itemsPayload
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showToast(`Saved Requisition ${data.request_number}!`, '✓');

                            // Clear cart and synchronize grid
                            cart = {};
                            renderCart();
                            document.querySelectorAll('.product-card.in-cart').forEach(card => {
                                card.classList.remove('in-cart');
                                const addB = card.querySelector('.add-cart-btn');
                                if (addB) addB.style.display = 'inline-flex';
                                const step = card.querySelector('.in-card-stepper');
                                if (step) step.classList.remove('active');
                            });

                            // Display success UI in modal body
                            if (confirmSaveBox) confirmSaveBox.style.display = 'none';
                            if (saveSuccessBanner) {
                                saveSuccessBanner.style.display = 'flex';
                                if (successReqNumberHeading) successReqNumberHeading.textContent = `Requisition ${data.request_number} Saved!`;
                                if (successReqMessage) successReqMessage.textContent = `Your request has been successfully saved to the database and forwarded for PGSO verification.`;
                            }
                        } else {
                            alert(data.message || 'Error saving requisition.');
                            confirmSaveRequestBtn.disabled = false;
                            if (confirmSaveBtnText) confirmSaveBtnText.textContent = 'Confirm & Save Request';
                        }
                    })
                    .catch(error => {
                        console.error('Save error:', error);
                        const msg = (error && error.message) ? error.message : 'Failed to save requisition to database.';
                        alert(msg);
                        confirmSaveRequestBtn.disabled = false;
                        if (confirmSaveBtnText) confirmSaveBtnText.textContent = 'Confirm & Save Request';
                    });
                });
            }

            // Open Official Printable RIS Form Template with Current Cart Items
            const openPrintableRisFormBtn = document.getElementById('openPrintableRisFormBtn');
            if (openPrintableRisFormBtn) {
                openPrintableRisFormBtn.addEventListener('click', () => {
                    sessionStorage.setItem('ris_cart_items', JSON.stringify(cart));
                    sessionStorage.setItem('ris_purpose', (risPurposeInput ? risPurposeInput.value.trim() : '') || 'Official Office Supply Consumption');
                });
            }

            // Confirm & Print
            confirmPrintRisBtn.addEventListener('click', () => {
                window.print();
            });
        });
    </script>

</body>
</html>
