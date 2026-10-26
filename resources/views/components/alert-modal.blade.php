@props([
    'id' => 'pgsoAlertModal',
    'type' => null,           // 'info', 'success', 'warning', 'error', 'danger'
    'title' => null,
    'message' => null,
    'confirmText' => 'Understood',
    'cancelText' => 'Cancel',
    'showCancel' => false,
    'checkSession' => false,
    'autoShow' => false,
])

@php
    $sessionType = null;
    $sessionTitle = null;
    $sessionMessage = null;
    $sessionErrors = [];

    if ($checkSession) {
        if ($errors->any()) {
            $sessionType = 'error';
            $sessionTitle = 'Validation Notice';
            $sessionMessage = 'Please review the highlighted issues before proceeding:';
            $sessionErrors = $errors->all();
        } elseif (session('error')) {
            $sessionType = 'error';
            $sessionTitle = 'Action Failed';
            $sessionMessage = session('error');
        } elseif (session('status') || session('success')) {
            $sessionType = 'success';
            $sessionTitle = 'Operation Successful';
            $sessionMessage = session('status') ?: session('success');
        } elseif (session('warning')) {
            $sessionType = 'warning';
            $sessionTitle = 'Attention Required';
            $sessionMessage = session('warning');
        } elseif (session('info')) {
            $sessionType = 'info';
            $sessionTitle = 'Notice';
            $sessionMessage = session('info');
        }
    }

    $initialType = $type ?: ($sessionType ?: 'info');
    $initialTitle = $title ?: ($sessionTitle ?: 'Notification');
    $initialMessage = $message ?: ($sessionMessage ?: '');
    $hasInitialErrors = !empty($sessionErrors);
    $shouldAutoOpen = $autoShow || ($checkSession && ($sessionType !== null));
@endphp

<!-- PGSO High-End Alert Modal Component -->
<div id="{{ $id }}" 
     class="pgso-alert-overlay {{ $shouldAutoOpen ? 'is-active' : '' }}" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="{{ $id }}Title"
     aria-describedby="{{ $id }}Message"
     data-type="{{ $initialType }}">

    <div class="pgso-alert-dialog" role="document">
        <!-- Close Button (X) -->
        <button type="button" class="pgso-alert-close-btn" id="{{ $id }}CloseBtn" aria-label="Close dialog" title="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Icon Badges (Type specific) -->
        <div class="pgso-alert-icon-container" id="{{ $id }}IconWrapper">
            <!-- Success Icon -->
            <div class="pgso-alert-icon pgso-icon-success {{ $initialType === 'success' ? '' : 'is-hidden' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>

            <!-- Warning Icon -->
            <div class="pgso-alert-icon pgso-icon-warning {{ $initialType === 'warning' ? '' : 'is-hidden' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>

            <!-- Error / Danger Icon -->
            <div class="pgso-alert-icon pgso-icon-error {{ ($initialType === 'error' || $initialType === 'danger') ? '' : 'is-hidden' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>

            <!-- Info Icon -->
            <div class="pgso-alert-icon pgso-icon-info {{ $initialType === 'info' ? '' : 'is-hidden' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
            </div>
        </div>

        <!-- Dialog Header & Content -->
        <div class="pgso-alert-content">
            <h3 class="pgso-alert-title" id="{{ $id }}Title">{{ $initialTitle }}</h3>
            <p class="pgso-alert-message" id="{{ $id }}Message">{{ $initialMessage }}</p>

            <!-- Optional Errors List for Validation -->
            <div class="pgso-alert-error-box {{ $hasInitialErrors ? '' : 'is-hidden' }}" id="{{ $id }}ErrorBox">
                <ul class="pgso-alert-error-list" id="{{ $id }}ErrorList">
                    @if($hasInitialErrors)
                        @foreach($sessionErrors as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>

            @if(isset($slot) && !empty(trim($slot)))
                <div class="pgso-alert-custom-slot" id="{{ $id }}Slot">
                    {{ $slot }}
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="pgso-alert-actions" id="{{ $id }}Actions">
            <button type="button" 
                    class="pgso-alert-btn pgso-alert-btn-cancel {{ ($showCancel) ? '' : 'is-hidden' }}" 
                    id="{{ $id }}CancelBtn">
                <span id="{{ $id }}CancelText">{{ $cancelText }}</span>
            </button>

            <button type="button" 
                    class="pgso-alert-btn pgso-alert-btn-confirm" 
                    id="{{ $id }}ConfirmBtn">
                <span id="{{ $id }}ConfirmText">{{ $confirmText }}</span>
            </button>
        </div>
    </div>
</div>

@once
<style>
    /* Scoped Styling for PGSO Alert Modal */
    .pgso-alert-overlay {
        position: fixed;
        inset: 0;
        background: rgba(9, 9, 11, 0.45);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), 
                    visibility 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .pgso-alert-overlay * {
        box-sizing: border-box;
    }

    .pgso-alert-overlay.is-active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    /* Modal Dialog Card */
    .pgso-alert-dialog {
        position: relative;
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border-radius: 20px;
        padding: 28px 24px 24px;
        border: 1px solid rgba(228, 228, 231, 0.8);
        box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.18), 
                    0 4px 12px rgba(0, 0, 0, 0.04);
        text-align: center;
        transform: scale(0.95) translateY(10px);
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .pgso-alert-overlay.is-active .pgso-alert-dialog {
        transform: scale(1) translateY(0);
    }

    /* Top Close (X) Button */
    .pgso-alert-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: transparent;
        border: none;
        color: #71717a;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        padding: 0;
    }

    .pgso-alert-close-btn svg {
        width: 16px;
        height: 16px;
    }

    .pgso-alert-close-btn:hover {
        background: #f4f4f5;
        color: #09090b;
    }

    /* Icon Badges */
    .pgso-alert-icon-container {
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pgso-alert-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        animation: pgsoIconPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .pgso-alert-icon svg {
        width: 28px;
        height: 28px;
    }

    @keyframes pgsoIconPop {
        0% { transform: scale(0.6); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Type Specific Icon Palettes */
    .pgso-icon-success {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.12);
    }

    .pgso-icon-warning {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
        box-shadow: 0 0 0 6px rgba(245, 158, 11, 0.12);
    }

    .pgso-icon-error {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        box-shadow: 0 0 0 6px rgba(239, 68, 68, 0.12);
    }

    .pgso-icon-info {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.12);
    }

    /* Typography */
    .pgso-alert-content {
        margin-bottom: 22px;
    }

    .pgso-alert-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #09090b;
        letter-spacing: -0.025em;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .pgso-alert-message {
        font-size: 0.875rem;
        color: #52525b;
        line-height: 1.55;
        margin: 0;
        word-break: break-word;
    }

    /* Validation Errors Box */
    .pgso-alert-error-box {
        margin-top: 14px;
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 12px;
        padding: 12px 16px;
        text-align: left;
    }

    .pgso-alert-error-list {
        margin: 0;
        padding-left: 18px;
        font-size: 0.8125rem;
        color: #991b1b;
        line-height: 1.5;
    }

    .pgso-alert-error-list li + li {
        margin-top: 4px;
    }

    /* Action Buttons */
    .pgso-alert-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .pgso-alert-btn {
        flex: 1;
        min-height: 42px;
        height: 42px;
        padding: 0 16px;
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.16s ease;
        text-decoration: none;
        outline: none;
    }

    .pgso-alert-btn-cancel {
        background: #ffffff;
        border: 1px solid #e4e4e7;
        color: #52525b;
    }

    .pgso-alert-btn-cancel:hover {
        background: #f4f4f5;
        border-color: #d4d4d8;
        color: #09090b;
    }

    .pgso-alert-btn-confirm {
        background: #09090b;
        border: 1px solid #09090b;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .pgso-alert-btn-confirm:hover {
        background: #27272a;
        border-color: #27272a;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
    }

    .pgso-alert-btn-confirm:active {
        transform: translateY(0);
    }

    /* Type-matching Confirm Button Accents */
    .pgso-alert-overlay[data-type="success"] .pgso-alert-btn-confirm {
        background: #059669;
        border-color: #059669;
    }
    .pgso-alert-overlay[data-type="success"] .pgso-alert-btn-confirm:hover {
        background: #047857;
        border-color: #047857;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }

    .pgso-alert-overlay[data-type="warning"] .pgso-alert-btn-confirm {
        background: #d97706;
        border-color: #d97706;
    }
    .pgso-alert-overlay[data-type="warning"] .pgso-alert-btn-confirm:hover {
        background: #b45309;
        border-color: #b45309;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
    }

    .pgso-alert-overlay[data-type="error"] .pgso-alert-btn-confirm,
    .pgso-alert-overlay[data-type="danger"] .pgso-alert-btn-confirm {
        background: #dc2626;
        border-color: #dc2626;
    }
    .pgso-alert-overlay[data-type="error"] .pgso-alert-btn-confirm:hover,
    .pgso-alert-overlay[data-type="danger"] .pgso-alert-btn-confirm:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }

    .pgso-alert-overlay[data-type="info"] .pgso-alert-btn-confirm {
        background: #2563eb;
        border-color: #2563eb;
    }
    .pgso-alert-overlay[data-type="info"] .pgso-alert-btn-confirm:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .is-hidden {
        display: none !important;
    }
</style>

<script>
    /**
     * PGSO Global Alert & Confirmation System
     * Reusable across all pages for informative alerts, errors, and confirmation modals.
     */
    (function () {
        const modalId = '{{ $id }}';
        let currentResolve = null;

        function getElements() {
            const overlay = document.getElementById(modalId);
            if (!overlay) return null;

            return {
                overlay: overlay,
                title: document.getElementById(`${modalId}Title`),
                message: document.getElementById(`${modalId}Message`),
                confirmBtn: document.getElementById(`${modalId}ConfirmBtn`),
                confirmText: document.getElementById(`${modalId}ConfirmText`),
                cancelBtn: document.getElementById(`${modalId}CancelBtn`),
                cancelText: document.getElementById(`${modalId}CancelText`),
                closeBtn: document.getElementById(`${modalId}CloseBtn`),
                errorBox: document.getElementById(`${modalId}ErrorBox`),
                errorList: document.getElementById(`${modalId}ErrorList`),
                icons: {
                    success: overlay.querySelector('.pgso-icon-success'),
                    warning: overlay.querySelector('.pgso-icon-warning'),
                    error: overlay.querySelector('.pgso-icon-error'),
                    info: overlay.querySelector('.pgso-icon-info'),
                }
            };
        }

        function setIcon(els, type) {
            Object.values(els.icons).forEach(icon => {
                if (icon) icon.classList.add('is-hidden');
            });

            const target = (type === 'danger') ? 'error' : (type || 'info');
            if (els.icons[target]) {
                els.icons[target].classList.remove('is-hidden');
            } else if (els.icons.info) {
                els.icons.info.classList.remove('is-hidden');
            }
        }

        function closeModal(resolvedValue) {
            const els = getElements();
            if (!els) return;

            els.overlay.classList.remove('is-active');

            if (currentResolve) {
                currentResolve(resolvedValue);
                currentResolve = null;
            }
        }

        /**
         * Open modal with customizable options.
         * Returns a Promise resolving to true (confirm) or false (cancel/close).
         */
        window.openPgsoAlert = function (options) {
            const els = getElements();
            if (!els) {
                // Fallback to browser alert if component is not mounted
                if (options.showCancel) {
                    return Promise.resolve(window.confirm(options.message || options.title || ''));
                }
                window.alert(options.message || options.title || '');
                return Promise.resolve(true);
            }

            const type = options.type || 'info';
            els.overlay.setAttribute('data-type', type);
            setIcon(els, type);

            if (els.title) {
                els.title.textContent = options.title || (
                    type === 'success' ? 'Success' :
                    type === 'warning' ? 'Attention Required' :
                    type === 'error' || type === 'danger' ? 'Error' : 'Notice'
                );
            }

            if (els.message) {
                els.message.textContent = options.message || '';
            }

            // Validation error list
            if (els.errorBox && els.errorList) {
                if (options.errors && Array.isArray(options.errors) && options.errors.length > 0) {
                    els.errorList.innerHTML = options.errors.map(e => `<li>${escapeHtml(e)}</li>`).join('');
                    els.errorBox.classList.remove('is-hidden');
                } else {
                    els.errorBox.classList.add('is-hidden');
                    els.errorList.innerHTML = '';
                }
            }

            // Buttons configuration
            if (els.confirmText) {
                els.confirmText.textContent = options.confirmText || 'Understood';
            }

            if (els.cancelBtn && els.cancelText) {
                if (options.showCancel) {
                    els.cancelText.textContent = options.cancelText || 'Cancel';
                    els.cancelBtn.classList.remove('is-hidden');
                } else {
                    els.cancelBtn.classList.add('is-hidden');
                }
            }

            els.overlay.classList.add('is-active');
            setTimeout(() => {
                if (els.confirmBtn) els.confirmBtn.focus();
            }, 50);

            return new Promise((resolve) => {
                currentResolve = resolve;
            });
        };

        // Convenience shortcuts
        window.showAlert = function (messageOrOptions, title) {
            if (typeof messageOrOptions === 'object' && messageOrOptions !== null) {
                return window.openPgsoAlert(messageOrOptions);
            }
            return window.openPgsoAlert({
                type: 'info',
                title: title || 'Notice',
                message: messageOrOptions,
                showCancel: false
            });
        };

        window.showSuccess = function (message, title) {
            return window.openPgsoAlert({
                type: 'success',
                title: title || 'Operation Successful',
                message: message,
                confirmText: 'Great',
                showCancel: false
            });
        };

        window.showError = function (message, title, errors) {
            return window.openPgsoAlert({
                type: 'error',
                title: title || 'Action Failed',
                message: message,
                errors: errors || null,
                confirmText: 'Dismiss',
                showCancel: false
            });
        };

        window.showWarning = function (message, title) {
            return window.openPgsoAlert({
                type: 'warning',
                title: title || 'Attention Required',
                message: message,
                confirmText: 'Understood',
                showCancel: false
            });
        };

        window.showConfirm = function (messageOrOptions, title) {
            if (typeof messageOrOptions === 'object' && messageOrOptions !== null) {
                return window.openPgsoAlert({
                    showCancel: true,
                    confirmText: 'Confirm',
                    cancelText: 'Cancel',
                    ...messageOrOptions
                });
            }
            return window.openPgsoAlert({
                type: 'warning',
                title: title || 'Please Confirm',
                message: messageOrOptions,
                confirmText: 'Yes, Proceed',
                cancelText: 'Cancel',
                showCancel: true
            });
        };

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Setup Event Listeners on DOM Load
        document.addEventListener('DOMContentLoaded', () => {
            const els = getElements();
            if (!els) return;

            if (els.confirmBtn) {
                els.confirmBtn.addEventListener('click', () => closeModal(true));
            }

            if (els.cancelBtn) {
                els.cancelBtn.addEventListener('click', () => closeModal(false));
            }

            if (els.closeBtn) {
                els.closeBtn.addEventListener('click', () => closeModal(false));
            }

            // Click backdrop to close
            els.overlay.addEventListener('click', (e) => {
                if (e.target === els.overlay) {
                    closeModal(false);
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (!els.overlay.classList.contains('is-active')) return;

                if (e.key === 'Escape') {
                    e.preventDefault();
                    closeModal(false);
                } else if (e.key === 'Enter' && !els.cancelBtn?.matches(':focus')) {
                    e.preventDefault();
                    closeModal(true);
                }
            });
        });
    })();
</script>
@endonce
