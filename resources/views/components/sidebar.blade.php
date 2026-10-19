<div class="pgso-layout-container" style="{{ isset($slot) && !empty(trim($slot)) ? 'display: flex; min-height: 100vh; width: 100%;' : 'display: contents;' }}">
    <aside class="pgso-sidebar" id="pgsoSidebar">
        <style>
            /* Sidebar Scoped Styling */
            .pgso-sidebar {
                width: 260px;
                min-height: 100vh;
                background-color: #ffffff;
                border-right: 1px solid #e4e4e7;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                color: #09090b;
                flex-shrink: 0;
                box-sizing: border-box;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }

            .pgso-sidebar * {
                box-sizing: border-box;
            }

            /* Top Brand Section */
            .sidebar-header {
                padding: 24px 20px 18px 20px;
                border-bottom: 1px solid #f4f4f5;
            }

            .sidebar-brand-link {
                display: flex;
                align-items: center;
                gap: 12px;
                text-decoration: none;
                color: #09090b;
            }

            .brand-icon-box {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background-color: #09090b;
                color: #ffffff;
                flex-shrink: 0;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            }

            .brand-icon-box svg {
                width: 20px;
                height: 20px;
            }

            .brand-text-group {
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .brand-name {
                font-size: 0.9375rem;
                font-weight: 700;
                letter-spacing: -0.02em;
                color: #09090b;
                line-height: 1.25;
                white-space: nowrap;
            }

            .brand-desc {
                font-size: 0.6875rem;
                font-weight: 500;
                color: #71717a;
                letter-spacing: 0.02em;
                text-transform: uppercase;
                margin-top: 2px;
                white-space: nowrap;
            }

            /* Main Navigation Links */
            .sidebar-nav-container {
                padding: 20px 14px;
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 22px;
            }

            .nav-section {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .nav-section-title {
                font-size: 0.6875rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: #a1a1aa;
                padding: 0 10px 6px 10px;
            }

            .nav-link-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 9px 12px;
                border-radius: 8px;
                text-decoration: none;
                color: #52525b;
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.15s ease;
            }

            .nav-link-item svg {
                width: 18px;
                height: 18px;
                color: #71717a;
                flex-shrink: 0;
                transition: color 0.15s ease;
            }

            .nav-link-item:hover {
                color: #09090b;
                background-color: #f4f4f5;
            }

            .nav-link-item:hover svg {
                color: #09090b;
            }

            .nav-link-item.active {
                color: #09090b;
                background-color: #f4f4f5;
                font-weight: 600;
            }

            .nav-link-item.active svg {
                color: #09090b;
            }

            .nav-badge-count {
                margin-left: auto;
                background-color: #ffffff;
                border: 1px solid #e4e4e7;
                font-size: 0.6875rem;
                font-weight: 600;
                padding: 1px 7px;
                border-radius: 9999px;
                color: #18181b;
            }

            /* Bottom Section: Settings & Logout */
            .sidebar-footer {
                padding: 16px 14px 20px 14px;
                border-top: 1px solid #f4f4f5;
                display: flex;
                flex-direction: column;
                gap: 6px;
                background-color: #ffffff;
            }

            /* User Profile Snippet in Footer */
            .footer-user-card {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 10px;
                border-radius: 8px;
                background-color: #fafafa;
                border: 1px solid #f4f4f5;
                margin-bottom: 8px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                background-color: #09090b;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
                font-weight: 700;
                flex-shrink: 0;
            }

            .user-info {
                display: flex;
                flex-direction: column;
                overflow: hidden;
                flex: 1;
            }

            .user-name-text {
                font-size: 0.8125rem;
                font-weight: 600;
                color: #09090b;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .user-role-badge {
                font-size: 0.6563rem;
                font-weight: 600;
                color: #71717a;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }

            .footer-btn {
                display: flex;
                align-items: center;
                gap: 12px;
                width: 100%;
                padding: 9px 12px;
                border-radius: 8px;
                font-family: inherit;
                font-size: 0.875rem;
                font-weight: 500;
                color: #52525b;
                text-decoration: none;
                background: none;
                border: none;
                cursor: pointer;
                transition: all 0.15s ease;
                text-align: left;
            }

            .footer-btn svg {
                width: 18px;
                height: 18px;
                color: #71717a;
                flex-shrink: 0;
                transition: color 0.15s ease;
            }

            .footer-btn:hover {
                color: #09090b;
                background-color: #f4f4f5;
            }

            .footer-btn:hover svg {
                color: #09090b;
            }

            .footer-btn.btn-logout:hover {
                color: #dc2626;
                background-color: #fef2f2;
            }

            .footer-btn.btn-logout:hover svg {
                color: #dc2626;
            }
        </style>

        <!-- Top Brand Section -->
        <div class="sidebar-header">
            <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" class="sidebar-brand-link">
                <div class="brand-icon-box" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                </div>
                <div class="brand-text-group">
                    <span class="brand-name">PGSO Inventory</span>
                    <span class="brand-desc">Admin Management</span>
                </div>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-nav-container">
            
            <!-- General Menu -->
            <div class="nav-section">
                <span class="nav-section-title">Menu</span>

                <!-- Dashboard -->
                <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}" 
                   class="nav-link-item {{ request()->is('admin/dashboard*') || request()->is('dashboard*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Inventory Items -->
                <a href="{{ Route::has('admin.inventory') ? route('admin.inventory') : url('/admin/inventory') }}" 
                   class="nav-link-item {{ request()->is('admin/inventory*') || request()->is('inventory*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m7.5 4.27 9 5.15"/>
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                    <span>Inventory Items</span>
                </a>

                <!-- Requisitions / Issuance -->
                <a href="{{ Route::has('admin.requisitions.index') ? route('admin.requisitions.index') : url('/admin/requisitions') }}" 
                   class="nav-link-item {{ request()->is('admin/requisition*') || request()->is('requisitions*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 8 9"/>
                    </svg>
                    <span>Requisitions</span>
                    @php
                        $pendingReqBadge = \App\Models\RequisitionRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingReqBadge > 0)
                        <span class="nav-badge-count" style="background-color: #09090b; color: #ffffff; font-weight: 700;">{{ $pendingReqBadge }}</span>
                    @endif
                </a>


                <!-- Supplies & Deliveries -->
                <a href="{{ route('admin.inventory.purchase-request') }}" 
                   class="nav-link-item {{ request()->is('admin/inventory/purchase-request*') || request()->is('admin/inventory/insert-purchase-request*') || request()->is('deliveries*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="13" x="1" y="3" rx="2"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/>
                        <circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>PO Deliveries</span>
                </a>
            </div>

            <!-- Transfer & Releasing -->
            <div class="nav-section">
                <span class="nav-section-title">Transfer and Releasing</span>

                <!-- Transfer -->
                <a href="{{ Route::has('admin.transfers.index') ? route('admin.transfers.index') : url('/admin/transfers') }}" 
                   class="nav-link-item {{ request()->is('admin/transfer*') || request()->is('transfer*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    <span>Transfer</span>
                </a>

                <!-- Release -->
                <a href="{{ Route::has('admin.releases.index') ? route('admin.releases.index') : url('/admin/releases') }}" 
                   class="nav-link-item {{ request()->is('admin/releases*') || request()->is('releases*') ? 'active' : '' }}">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-delete preview-icon"><path d="M10 5a2 2 0 0 0-1.344.519l-6.328 5.74a1 1 0 0 0 0 1.481l6.328 5.741A2 2 0 0 0 10 19h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/><path d="m12 9 6 6"/><path d="m18 9-6 6"/></svg>
                    <span>Release</span>
                </a>
            </div>

            <!-- Management / Records -->
            <div class="nav-section">
                <span class="nav-section-title">Records & System</span>

                <!-- Offices -->
                <a href="{{ Route::has('admin.offices.index') ? route('admin.offices.index') : url('/admin/offices') }}" 
                   class="nav-link-item {{ request()->is('admin/office*') || request()->is('office*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                        <path d="M9 22v-4h6v4"/>
                        <path d="M8 6h.01"/>
                        <path d="M16 6h.01"/>
                        <path d="M8 10h.01"/>
                        <path d="M16 10h.01"/>
                        <path d="M8 14h.01"/>
                        <path d="M16 14h.01"/>
                    </svg>
                    <span>Offices</span>
                </a>

                <!-- Reports -->
                <a href="{{ Route::has('reports.index') ? route('reports.index') : '#' }}" 
                   class="nav-link-item {{ request()->is('reports*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <span>Audit & Reports</span>
                </a>

                @if(auth()->check() && auth()->user()->isAdmin())
                    <!-- Staff / User Management (Admin Only) -->
                    <a href="{{ Route::has('admin.accounts.index') ? route('admin.accounts.index') : (Route::has('users.index') ? route('users.index') : '#') }}" 
                       class="nav-link-item {{ request()->is('admin/accounts*') || request()->is('admin/users*') || request()->is('users*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Accounts</span>
                    </a>
                @endif
            </div>

        </div>

        <!-- Bottom Section: User Info, Settings, and Logout -->
        <div class="sidebar-footer">
            
            <!-- Authenticated User Profile Summary -->
            @if(auth()->check())
                <div class="footer-user-card">
                    <div class="user-avatar" aria-hidden="true">
                        {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->username ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name-text">{{ auth()->user()->full_name }}</span>
                        <span class="user-role-badge">{{ auth()->user()->account_level?->label() ?? 'Staff' }}</span>
                    </div>
                </div>
            @endif

            <!-- Settings -->
            <a href="{{ Route::has('settings.index') ? route('settings.index') : (Route::has('settings') ? route('settings') : '#') }}" 
               class="footer-btn {{ request()->is('settings*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
                <span>Settings</span>
            </a>

            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                @csrf
                <button type="submit" class="footer-btn btn-logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>

        </div>
    </aside>

    @if(isset($slot) && !empty(trim($slot)))
        <main class="pgso-main-viewport" style="flex: 1; min-width: 0; background-color: #fafafa; overflow-y: auto;">
            {{ $slot }}
        </main>
    @endif
</div>
