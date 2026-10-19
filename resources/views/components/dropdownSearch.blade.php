@props([
    'name' => 'select_item',
    'id' => null,
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'name',
    'subtextKey' => null,
    'selected' => null,
    'placeholder' => 'Select an option...',
    'searchPlaceholder' => 'Search options...',
    'required' => false,
    'disabled' => false,
    'allowClear' => true,
    'emptyText' => 'No options found',
])

@php
    $inputId = $id ?? ($name ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) . '_' . uniqid() : 'ds_' . uniqid());
    $containerId = 'ds_container_' . $inputId;
    $normalizedOptions = [];

    if (! empty($options)) {
        foreach ($options as $key => $option) {
            if (is_object($option)) {
                $val = data_get($option, $valueKey, data_get($option, 'id', $key));
                $lbl = data_get($option, $labelKey, data_get($option, 'office_name', data_get($option, 'name', (string) $val)));
                $sub = $subtextKey ? data_get($option, $subtextKey) : null;
            } elseif (is_array($option)) {
                $val = $option[$valueKey] ?? $option['id'] ?? $option['value'] ?? $key;
                $lbl = $option[$labelKey] ?? $option['name'] ?? $option['label'] ?? $option['office_name'] ?? (string) $val;
                $sub = $subtextKey ? ($option[$subtextKey] ?? null) : null;
            } else {
                $val = is_numeric($key) ? $option : $key;
                $lbl = (string) $option;
                $sub = null;
            }

            $normalizedOptions[] = [
                'value' => (string) $val,
                'label' => (string) $lbl,
                'subtext' => $sub ? (string) $sub : null,
            ];
        }
    }

    $selectedVal = (string) (old($name, $selected) ?? '');
    $selectedItem = collect($normalizedOptions)->first(function ($item) use ($selectedVal) {
        return (string) $item['value'] === $selectedVal;
    });
@endphp

<div id="{{ $containerId }}" class="pgso-dropdown-search-wrapper" data-name="{{ $name }}">
    <!-- Hidden Form Input for seamless form submission -->
    <input type="hidden" 
           name="{{ $name }}" 
           id="{{ $inputId }}" 
           value="{{ $selectedVal }}"
           {{ $required ? 'required' : '' }}
           {{ $disabled ? 'disabled' : '' }}>

    <!-- Dropdown Trigger Button -->
    <button type="button" 
            class="pgso-ds-trigger {{ $disabled ? 'disabled' : '' }}" 
            id="{{ $containerId }}_trigger" 
            aria-haspopup="listbox" 
            aria-expanded="false"
            {{ $disabled ? 'disabled' : '' }}>
        
        <div class="pgso-ds-selected-content">
            <span class="pgso-ds-placeholder {{ $selectedItem ? 'pgso-ds-hidden' : '' }}" id="{{ $containerId }}_placeholder">
                {{ $placeholder }}
            </span>
            <span class="pgso-ds-selected-label {{ ! $selectedItem ? 'pgso-ds-hidden' : '' }}" id="{{ $containerId }}_label">
                {{ $selectedItem['label'] ?? '' }}
            </span>
            <span class="pgso-ds-selected-badge {{ (! $selectedItem || empty($selectedItem['subtext'])) ? 'pgso-ds-hidden' : '' }}" id="{{ $containerId }}_badge">
                {{ $selectedItem['subtext'] ?? '' }}
            </span>
        </div>

        <div class="pgso-ds-controls">
            @if($allowClear)
                <span class="pgso-ds-clear-btn {{ ! $selectedItem ? 'pgso-ds-hidden' : '' }}" 
                      id="{{ $containerId }}_clear" 
                      title="Clear selection" 
                      role="button" 
                      tabindex="0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </span>
            @endif

            <span class="pgso-ds-arrow" id="{{ $containerId }}_arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </span>
        </div>
    </button>

    <!-- Floating Dropdown Menu -->
    <div class="pgso-ds-menu" id="{{ $containerId }}_menu" role="listbox" aria-hidden="true">
        <!-- Live Search Bar -->
        <div class="pgso-ds-search-bar">
            <svg class="pgso-ds-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   class="pgso-ds-search-input" 
                   id="{{ $containerId }}_search" 
                   placeholder="{{ $searchPlaceholder }}" 
                   autocomplete="off" 
                   spellcheck="false">
        </div>

        <!-- Scrollable Options List -->
        <div class="pgso-ds-options-list" id="{{ $containerId }}_options">
            @forelse($normalizedOptions as $index => $opt)
                @php
                    $isSelected = (string) $opt['value'] === $selectedVal;
                @endphp
                <div class="pgso-ds-option {{ $isSelected ? 'selected' : '' }}" 
                     role="option" 
                     data-index="{{ $index }}"
                     data-value="{{ $opt['value'] }}" 
                     data-label="{{ $opt['label'] }}"
                     data-subtext="{{ $opt['subtext'] ?? '' }}"
                     aria-selected="{{ $isSelected ? 'true' : 'false' }}">
                    
                    <div class="pgso-ds-option-main">
                        <span class="pgso-ds-option-title">{{ $opt['label'] }}</span>
                        @if(! empty($opt['subtext']))
                            <span class="pgso-ds-option-subtext">{{ $opt['subtext'] }}</span>
                        @endif
                    </div>

                    <span class="pgso-ds-check-icon {{ $isSelected ? '' : 'pgso-ds-hidden' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                </div>
            @empty
                <div class="pgso-ds-empty-state">
                    {{ $emptyText }}
                </div>
            @endforelse

            <div class="pgso-ds-no-match pgso-ds-hidden" id="{{ $containerId }}_no_match">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span>{{ $emptyText }}</span>
            </div>
        </div>
    </div>
</div>

@once
<style>
    /* Scoped Styles for PGSO Searchable Dropdown */
    .pgso-dropdown-search-wrapper {
        position: relative;
        width: 100%;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        box-sizing: border-box;
    }

    .pgso-dropdown-search-wrapper * {
        box-sizing: border-box;
    }

    /* Trigger Button */
    .pgso-ds-trigger {
        width: 100%;
        min-height: 42px;
        padding: 9px 14px;
        background: #ffffff;
        border: 1px solid #e4e4e7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        text-align: left;
        color: #09090b;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .pgso-ds-trigger:hover:not(.disabled) {
        border-color: #d4d4d8;
        background: #fafafa;
    }

    .pgso-ds-trigger:focus,
    .pgso-ds-trigger.active {
        outline: none;
        border-color: #09090b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
    }

    .pgso-ds-trigger.disabled {
        background: #f4f4f5;
        border-color: #e4e4e7;
        color: #a1a1aa;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Selected Content in Trigger */
    .pgso-ds-selected-content {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .pgso-ds-placeholder {
        color: #a1a1aa;
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pgso-ds-selected-label {
        font-weight: 600;
        color: #09090b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pgso-ds-selected-badge {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.6875rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        padding: 2px 6px;
        border-radius: 6px;
        flex-shrink: 0;
    }

    /* Controls (Clear & Arrow) */
    .pgso-ds-controls {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .pgso-ds-clear-btn {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a1a1aa;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .pgso-ds-clear-btn:hover {
        background: #f4f4f5;
        color: #ef4444;
    }

    .pgso-ds-clear-btn svg {
        width: 12px;
        height: 12px;
    }

    .pgso-ds-arrow {
        color: #71717a;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .pgso-ds-arrow svg {
        width: 16px;
        height: 16px;
    }

    .pgso-ds-trigger.active .pgso-ds-arrow {
        transform: rotate(180deg);
    }

    /* Floating Menu */
    .pgso-ds-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e4e4e7;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.03);
        z-index: 1050;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: pgsoDsFadeIn 0.18s ease-out;
    }

    .pgso-ds-menu.open {
        display: flex;
    }

    /* Search Bar in Menu */
    .pgso-ds-search-bar {
        position: relative;
        padding: 10px 12px;
        border-bottom: 1px solid #f4f4f5;
        background: #ffffff;
    }

    .pgso-ds-search-icon {
        position: absolute;
        left: 22px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        color: #a1a1aa;
        pointer-events: none;
    }

    .pgso-ds-search-input {
        width: 100%;
        padding: 8px 12px 8px 34px;
        border: 1px solid #e4e4e7;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-family: inherit;
        color: #09090b;
        background: #fafafa;
        transition: all 0.15s ease;
    }

    .pgso-ds-search-input:focus {
        outline: none;
        border-color: #09090b;
        background: #ffffff;
        box-shadow: 0 0 0 2px rgba(9, 9, 11, 0.05);
    }

    /* Options List */
    .pgso-ds-options-list {
        max-height: 240px;
        overflow-y: auto;
        padding: 6px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        scrollbar-width: thin;
    }

    .pgso-ds-option {
        padding: 9px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        transition: all 0.12s ease;
    }

    .pgso-ds-option:hover,
    .pgso-ds-option.highlighted {
        background: #f4f4f5;
    }

    .pgso-ds-option.selected {
        background: #eff6ff;
        color: #1e40af;
    }

    .pgso-ds-option-main {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0;
    }

    .pgso-ds-option-title {
        font-size: 0.8438rem;
        font-weight: 600;
        color: inherit;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pgso-ds-option-subtext {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.6875rem;
        font-weight: 500;
        color: #64748b;
        background: #f1f5f9;
        padding: 1px 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .pgso-ds-check-icon {
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pgso-ds-check-icon svg {
        width: 15px;
        height: 15px;
    }

    /* Empty states */
    .pgso-ds-empty-state,
    .pgso-ds-no-match {
        padding: 20px 14px;
        text-align: center;
        color: #a1a1aa;
        font-size: 0.8125rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .pgso-ds-no-match svg {
        width: 24px;
        height: 24px;
        color: #cbd5e1;
    }

    .pgso-ds-hidden {
        display: none !important;
    }

    @keyframes pgsoDsFadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    if (typeof window.pgsoDropdownSearchInit === 'undefined') {
        window.pgsoDropdownSearchInit = true;

        document.addEventListener('click', (e) => {
            document.querySelectorAll('.pgso-dropdown-search-wrapper').forEach(wrapper => {
                const menu = wrapper.querySelector('.pgso-ds-menu');
                const trigger = wrapper.querySelector('.pgso-ds-trigger');
                if (menu && trigger && !wrapper.contains(e.target)) {
                    menu.classList.remove('open');
                    trigger.classList.remove('active');
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        });
    }

    window.setupPgsoDropdownSearch = setupPgsoDropdownSearch;

    function initAllPgsoDropdownSearches() {
        document.querySelectorAll('.pgso-dropdown-search-wrapper').forEach(wrapper => {
            if (wrapper.id && !wrapper.dataset.initialized) {
                setupPgsoDropdownSearch(wrapper.id);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllPgsoDropdownSearches);
    } else {
        initAllPgsoDropdownSearches();
    }

    // Capture-phase listener: immediately initialize any dropdown if its trigger is clicked
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.pgso-ds-trigger');
        if (trigger) {
            const wrapper = trigger.closest('.pgso-dropdown-search-wrapper');
            if (wrapper && wrapper.id && !wrapper.dataset.initialized) {
                setupPgsoDropdownSearch(wrapper.id);
            }
        }
    }, true);

    function setupPgsoDropdownSearch(containerId) {
        const container = document.getElementById(containerId);
        if (!container || container.dataset.initialized) return;
        container.dataset.initialized = 'true';

        const trigger = document.getElementById(containerId + '_trigger');
        const menu = document.getElementById(containerId + '_menu');
        const searchInput = document.getElementById(containerId + '_search');
        const optionsContainer = document.getElementById(containerId + '_options');
        const noMatch = document.getElementById(containerId + '_no_match');
        const hiddenInput = container.querySelector('input[type="hidden"]');
        const labelEl = document.getElementById(containerId + '_label');
        const placeholderEl = document.getElementById(containerId + '_placeholder');
        const badgeEl = document.getElementById(containerId + '_badge');
        const clearBtn = document.getElementById(containerId + '_clear');

        let highlightedIndex = -1;

        function getVisibleOptions() {
            return Array.from(optionsContainer.querySelectorAll('.pgso-ds-option:not(.pgso-ds-hidden)'));
        }

        function openMenu() {
            if (trigger.disabled) return;
            // Close any other open dropdowns first
            document.querySelectorAll('.pgso-ds-menu.open').forEach(m => m.classList.remove('open'));
            document.querySelectorAll('.pgso-ds-trigger.active').forEach(t => t.classList.remove('active'));

            menu.classList.add('open');
            trigger.classList.add('active');
            trigger.setAttribute('aria-expanded', 'true');
            if (searchInput) {
                searchInput.value = '';
                filterOptions('');
                setTimeout(() => searchInput.focus(), 50);
            }
        }

        function closeMenu() {
            menu.classList.remove('open');
            trigger.classList.remove('active');
            trigger.setAttribute('aria-expanded', 'false');
            highlightedIndex = -1;
            updateHighlight();
        }

        trigger.addEventListener('click', (e) => {
            if (e.target.closest('.pgso-ds-clear-btn')) return;
            if (menu.classList.contains('open')) {
                closeMenu();
            } else {
                openMenu();
            }
        });

        // Search Filter
        function filterOptions(query) {
            const q = query.trim().toLowerCase();
            const allOpts = optionsContainer.querySelectorAll('.pgso-ds-option');
            let matched = 0;

            allOpts.forEach(opt => {
                const label = (opt.dataset.label || '').toLowerCase();
                const subtext = (opt.dataset.subtext || '').toLowerCase();
                const value = (opt.dataset.value || '').toLowerCase();

                if (q === '' || label.includes(q) || subtext.includes(q) || value.includes(q)) {
                    opt.classList.remove('pgso-ds-hidden');
                    matched++;
                } else {
                    opt.classList.add('pgso-ds-hidden');
                }
            });

            if (noMatch) {
                noMatch.classList.toggle('pgso-ds-hidden', matched > 0);
            }

            highlightedIndex = -1;
            updateHighlight();
        }

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                filterOptions(e.target.value);
            });

            searchInput.addEventListener('keydown', (e) => {
                const visible = getVisibleOptions();
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (visible.length === 0) return;
                    highlightedIndex = (highlightedIndex + 1) % visible.length;
                    updateHighlight(visible);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (visible.length === 0) return;
                    highlightedIndex = (highlightedIndex - 1 + visible.length) % visible.length;
                    updateHighlight(visible);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (highlightedIndex >= 0 && visible[highlightedIndex]) {
                        selectOption(visible[highlightedIndex]);
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeMenu();
                    trigger.focus();
                }
            });
        }

        function updateHighlight(visible = getVisibleOptions()) {
            visible.forEach((opt, idx) => {
                if (idx === highlightedIndex) {
                    opt.classList.add('highlighted');
                    opt.scrollIntoView({ block: 'nearest' });
                } else {
                    opt.classList.remove('highlighted');
                }
            });
        }

        // Selection Handler
        function selectOption(optionEl) {
            const value = optionEl.dataset.value;
            const label = optionEl.dataset.label;
            const subtext = optionEl.dataset.subtext;

            hiddenInput.value = value;
            labelEl.textContent = label;
            labelEl.classList.remove('pgso-ds-hidden');
            placeholderEl.classList.add('pgso-ds-hidden');

            if (badgeEl) {
                if (subtext) {
                    badgeEl.textContent = subtext;
                    badgeEl.classList.remove('pgso-ds-hidden');
                } else {
                    badgeEl.classList.add('pgso-ds-hidden');
                }
            }

            if (clearBtn) {
                clearBtn.classList.remove('pgso-ds-hidden');
            }

            // Update option styles
            optionsContainer.querySelectorAll('.pgso-ds-option').forEach(opt => {
                const isSelected = opt.dataset.value === value;
                opt.classList.toggle('selected', isSelected);
                opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                const check = opt.querySelector('.pgso-ds-check-icon');
                if (check) check.classList.toggle('pgso-ds-hidden', !isSelected);
            });

            // Dispatch reactive event
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            hiddenInput.dispatchEvent(new CustomEvent('dropdown-selected', { 
                bubbles: true, 
                detail: { value, label, subtext } 
            }));

            closeMenu();
            try { trigger.focus(); } catch (e) {}
        }

        // Option Click
        optionsContainer.addEventListener('click', (e) => {
            const opt = e.target.closest('.pgso-ds-option');
            if (opt) {
                selectOption(opt);
            }
        });

        // Clear Selection
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                hiddenInput.value = '';
                labelEl.textContent = '';
                labelEl.classList.add('pgso-ds-hidden');
                placeholderEl.classList.remove('pgso-ds-hidden');
                if (badgeEl) badgeEl.classList.add('pgso-ds-hidden');
                clearBtn.classList.add('pgso-ds-hidden');

                optionsContainer.querySelectorAll('.pgso-ds-option').forEach(opt => {
                    opt.classList.remove('selected');
                    opt.setAttribute('aria-selected', 'false');
                    const check = opt.querySelector('.pgso-ds-check-icon');
                    if (check) check.classList.add('pgso-ds-hidden');
                });

                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                hiddenInput.dispatchEvent(new CustomEvent('dropdown-cleared', { bubbles: true }));
            });
        }
    }
</script>
@endonce
