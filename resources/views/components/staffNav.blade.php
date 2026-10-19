<!-- Bottom Navigation Spacer to prevent content overlap -->
<div class="staff-bottom-spacer" style="height: 80px; width: 100%;" aria-hidden="true"></div>

<!-- Staff Bottom Navigation Bar -->
<nav class="staff-bottom-nav" id="staffBottomNav" aria-label="Staff Navigation">
    <style>
        .staff-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 68px;
            background-color: #ffffff;
            border-top: 1px solid #e4e4e7;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.04), 0 -1px 3px rgba(0, 0, 0, 0.02);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .staff-nav-inner {
            width: 100%;
            max-width: 680px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0 16px;
        }

        .staff-nav-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: #71717a;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.7188rem;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .staff-nav-item svg {
            width: 20px;
            height: 20px;
            color: #71717a;
            transition: color 0.15s ease, transform 0.15s ease;
        }

        .staff-nav-item:hover {
            color: #09090b;
        }

        .staff-nav-item:hover svg {
            color: #09090b;
            transform: translateY(-1px);
        }

        .staff-nav-item.active {
            color: #09090b;
            font-weight: 700;
        }

        .staff-nav-item.active svg {
            color: #09090b;
        }

        /* Top active indicator line */
        .staff-nav-item.active::after {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 2.5px;
            background-color: #09090b;
            border-radius: 9999px;
        }

        /* Badge for requisitions */
        .staff-nav-badge {
            position: absolute;
            top: 4px;
            right: 8px;
            background-color: #09090b;
            color: #ffffff;
            font-size: 0.625rem;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 9999px;
            line-height: 1.2;
        }

        /* Sign Out Button styling */
        .staff-nav-logout-btn {
            color: #71717a;
        }

        .staff-nav-logout-btn:hover {
            color: #dc2626 !important;
        }

        .staff-nav-logout-btn:hover svg {
            color: #dc2626 !important;
        }

        @media (max-width: 480px) {
            .staff-nav-inner {
                padding: 0 8px;
            }
            .staff-nav-item {
                padding: 6px 10px;
                font-size: 0.6563rem;
            }
            .staff-nav-item svg {
                width: 18px;
                height: 18px;
            }
        }
    </style>

    <div class="staff-nav-inner">
        <!-- Inventory / Stock Dashboard -->
        <a href="{{ Route::has('staff.inventory') ? route('staff.inventory') : (Route::has('inventory.index') ? route('inventory.index') : url('/staff/inventory')) }}" 
           class="staff-nav-item {{ request()->is('staff/inventory*') || request()->is('staff/dashboard*') || request()->is('inventory*') ? 'active' : '' }}"
           title="Office Stock Inventory">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                <path d="m3.3 7 8.7 5 8.7-5"/>
                <path d="M12 22V12"/>
            </svg>
            <span>Inventory</span>
        </a>

        <!-- Requisitions with badge -->
        <a href="{{ Route::has('staff.requisition.index') ? route('staff.requisition.index') : (Route::has('requisitions.index') ? route('requisitions.index') : url('/staff/requisition')) }}" 
           class="staff-nav-item {{ request()->is('staff/requisition*') || request()->is('requisitions*') ? 'active' : '' }}"
           title="Requisitions & Issuance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            <span class="staff-nav-badge">6</span>
            <span>Requisitions</span>
        </a>

        <!-- Request Status & Logs Monitoring -->
        <a href="{{ Route::has('staff.request-stat') ? route('staff.request-stat') : url('/staff/request-stat') }}" 
           class="staff-nav-item {{ request()->is('staff/request-stat*') || request()->is('request-stat*') ? 'active' : '' }}"
           title="Monitor Requisition Request Status & Logs">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-git-pull-request preview-icon"><circle cx="18" cy="18" r="3"/><circle cx="6" cy="6" r="3"/><path d="M13 6h3a2 2 0 0 1 2 2v7"/><line x1="6" x2="6" y1="9" y2="21"/></svg>
            <span>Request Stat</span>
        </a>

        <!-- Sign Out Form -->
        <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="staff-nav-item staff-nav-logout-btn" title="Sign Out of Session">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</nav>
