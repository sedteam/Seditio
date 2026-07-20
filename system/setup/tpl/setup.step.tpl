<!-- BEGIN: STEP_WELCOME -->
<div class="step-content" id="step-1">
    <h2>{PHP.L.setup_welcome_title}</h2>
    <p class="step-desc">{PHP.L.setup_welcome_desc}</p>
    
    <div class="form-group" style="max-width: 400px; margin: 40px 0;">
        <label class="form-label">{PHP.L.setup_select_language}</label>
        <select class="form-input" id="langinstall-select">
            {LANG_OPTIONS}
        </select>
    </div>
    
    <div class="step-actions">
        <div></div>
        <button class="btn btn-primary" onclick="setup.nextStep()">
            {PHP.L.setup_next_step} →
        </button>
    </div>
</div>
<!-- END: STEP_WELCOME -->

<!-- BEGIN: STEP_SYSTEM -->
<div class="step-content" id="step-2">
    <h2>{PHP.L.setup_system_check_title}</h2>
    <p class="step-desc">{PHP.L.setup_system_check_desc}</p>
    
    <div class="checks-list" id="checks-list">
        <!-- BEGIN: CHECK_ROW -->
        <div class="check-item" style="animation: fadeIn 0.3s ease-out forwards;">
            <span class="check-name">{CHECK_NAME}</span>
            <span class="check-dots"></span>
            <span class="check-value">{CHECK_VALUE}</span>
            <span class="check-status {CHECK_STATUS_CLASS}">{CHECK_STATUS_SYMBOL}</span>
        </div>
        <!-- END: CHECK_ROW -->
    </div>
    
    <div class="step-actions">
        <button class="btn btn-secondary" onclick="setup.prevStep()">
            ← {PHP.L.setup_prev_step}
        </button>
        <button class="btn btn-primary" id="btn-step2-next" onclick="setup.nextStep()" {CAN_PROCEED_DISABLED}>
            {PHP.L.setup_next_step} →
        </button>
    </div>
</div>
<!-- END: STEP_SYSTEM -->

<!-- BEGIN: STEP_DATABASE -->
<div class="step-content" id="step-3">
    <h2>{PHP.L.setup_db_title}</h2>
    <p class="step-desc">{PHP.L.setup_db_desc}</p>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_db_host}</label>
            <input type="text" class="form-input" id="db-host" value="{DB_HOST}">
            <div class="form-hint">{PHP.L.setup_db_host_hint}</div>
        </div>
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_db_name}</label>
            <input type="text" class="form-input" id="db-name" value="{DB_NAME}">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_db_user}</label>
            <input type="text" class="form-input" id="db-user" value="{DB_USER}">
        </div>
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_db_password}</label>
            <input type="password" class="form-input" id="db-pass" value="{DB_PASS}">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_db_prefix}</label>
            <input type="text" class="form-input" id="db-prefix" value="{DB_PREFIX}">
            <div class="form-hint">{PHP.L.setup_db_prefix_hint}</div>
        </div>
        <div class="form-group" style="display: flex; flex-direction: column; justify-content: center;">
            <label class="checkbox-container">
                <input type="checkbox" id="db-clear" {DB_CLEAR_CHECKED}>
                <strong>{PHP.L.setup_db_clear}</strong>
            </label>
            <div class="form-hint" style="margin-left: 24px;">{PHP.L.setup_db_clear_hint}</div>
        </div>
    </div>
    
    <div class="db-test-row">
        <button class="btn btn-test" id="btn-test-db" onclick="setup.testDatabase()" style="display: flex; align-items: center; gap: 6px;">
            <i class="ic-plug"></i> {PHP.L.setup_test_connection}
        </button>
        <span id="db-test-result" class="db-test-result"></span>
    </div>
    
    <div class="step-actions">
        <button class="btn btn-secondary" onclick="setup.prevStep()">
            ← {PHP.L.setup_prev_step}
        </button>
        <button class="btn btn-primary" onclick="setup.nextStep()">
            {PHP.L.setup_next_step} →
        </button>
    </div>
</div>
<!-- END: STEP_DATABASE -->

<!-- BEGIN: STEP_SETTINGS -->
<div class="step-content" id="step-4">
    <h2>{PHP.L.setup_settings_title}</h2>
    <p class="step-desc">{PHP.L.setup_system_check_desc}</p>
    
    <div class="form-group">
        <label class="form-label">{PHP.L.setup_default_skin}</label>
        <div class="skins-grid" id="skins-grid">
            <!-- BEGIN: SKIN_ROW -->
            <div class="skin-card {SKIN_ACTIVE}" data-skin="{SKIN_CODE}" onclick="setup.selectSkin('{SKIN_CODE}')">
                <img class="skin-preview" src="{SKIN_PREVIEW}" alt="{SKIN_NAME}">
                <div class="skin-info">
                    <div class="skin-name">{SKIN_NAME}</div>
                </div>
            </div>
            <!-- END: SKIN_ROW -->
        </div>
    </div>
    
    <div class="form-group" style="max-width: 400px;">
        <label class="form-label">{PHP.L.setup_default_lang}</label>
        <select class="form-input" id="site-lang">
            <!-- BEGIN: LANG_ROW -->
            <option value="{LANG_CODE}" {LANG_SELECTED}>{LANG_NAME} ({LANG_CODE})</option>
            <!-- END: LANG_ROW -->
        </select>
    </div>
    
    <h3 style="margin: 30px 0 15px 0; font-size: 18px; font-weight: 600; color: var(--text-heading); display: flex; align-items: center; gap: 8px;">
        <i class="ic-user"></i> {PHP.L.setup_admin_account}
    </h3>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_admin_name}</label>
            <input type="text" class="form-input" id="admin-name" value="{ADMIN_NAME}">
            <div class="form-hint">{PHP.L.setup_ownaccount_name}</div>
        </div>
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_admin_pass}</label>
            <div class="password-input-group">
                <input type="password" class="form-input" id="admin-pass" value="{ADMIN_PASS}" style="flex: 1">
                <button class="btn btn-secondary" onclick="setup.generatePassword()" style="padding: 10px 14px; display: flex; align-items: center; gap: 6px;">
                    <i class="ic-repeat"></i> {PHP.L.setup_generate_password}
                </button>
            </div>
            <div class="password-strength" id="password-strength-container">
                <div class="password-strength-bar" id="password-strength-bar"></div>
            </div>
            <div class="form-hint">{PHP.L.setup_least8chars}</div>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_admin_email}</label>
            <input type="email" class="form-input" id="admin-email" value="{ADMIN_EMAIL}">
            <div class="form-hint">{PHP.L.setup_doublecheck}</div>
        </div>
        <div class="form-group">
            <label class="form-label">{PHP.L.setup_admin_country}</label>
            <select class="form-input" id="admin-country">
                {COUNTRY_OPTIONS}
            </select>
        </div>
    </div>
    
    <div class="step-actions">
        <button class="btn btn-secondary" onclick="setup.prevStep()">
            ← {PHP.L.setup_prev_step}
        </button>
        <button class="btn btn-primary" onclick="setup.nextStep()">
            {PHP.L.setup_next_step} →
        </button>
    </div>
</div>
<!-- END: STEP_SETTINGS -->

<!-- BEGIN: STEP_EXTENSIONS -->
<div class="step-content" id="step-5">
    <h2>{PHP.L.setup_extensions_title}</h2>
    
    <div class="ext-tabs">
        <div class="ext-tab active" id="tab-btn-modules" onclick="setup.switchExtTab('modules')">{PHP.L.setup_tab_modules}</div>
        <div class="ext-tab" id="tab-btn-plugins" onclick="setup.switchExtTab('plugins')">{PHP.L.setup_tab_plugins}</div>
    </div>
    
    <div class="ext-container" id="ext-container-modules">
        <!-- BEGIN: MODULE_ROW -->
        <div class="ext-card {MODULE_LOCKED_CLASS}">
            <div class="ext-icon">{MODULE_ICON_HTML}</div>
            <div class="ext-info">
                <div class="ext-name-row">
                    <span class="ext-name">{MODULE_NAME}</span>
                    {MODULE_BADGE}
                </div>
                <div class="ext-desc" title="{MODULE_DESC}">{MODULE_DESC}</div>
            </div>
            <div>
                <label class="switch">
                    <input type="checkbox" class="module-toggle" data-code="{MODULE_CODE}" {MODULE_CHECKED} {MODULE_DISABLED}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <!-- END: MODULE_ROW -->
        <!-- BEGIN: NO_MODULES -->
        <div style="padding: 20px; text-align: center; color: var(--text-light)">{PHP.L.setup_no_modules}</div>
        <!-- END: NO_MODULES -->
    </div>
    
    <div class="ext-container" id="ext-container-plugins" style="display: none">
        <!-- BEGIN: PLUGIN_ROW -->
        <div class="ext-card">
            <div class="ext-icon">{PLUGIN_ICON_HTML}</div>
            <div class="ext-info">
                <div class="ext-name-row">
                    <span class="ext-name">{PLUGIN_NAME}</span>
                </div>
                <div class="ext-desc" title="{PLUGIN_DESC}">{PLUGIN_DESC}</div>
            </div>
            <div>
                <label class="switch">
                    <input type="checkbox" class="plugin-toggle" data-code="{PLUGIN_CODE}" {PLUGIN_CHECKED}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <!-- END: PLUGIN_ROW -->
        <!-- BEGIN: NO_PLUGINS -->
        <div style="padding: 20px; text-align: center; color: var(--text-light)">{PHP.L.setup_no_plugins}</div>
        <!-- END: NO_PLUGINS -->
    </div>
    
    <div class="step-actions">
        <button class="btn btn-secondary" onclick="setup.prevStep()">
            ← {PHP.L.setup_prev_step}
        </button>
        <button class="btn btn-primary" onclick="setup.nextStep()" style="display: flex; align-items: center; gap: 6px;">
            <i class="ic-zap"></i> {PHP.L.setup_install}
        </button>
    </div>
</div>
<!-- END: STEP_EXTENSIONS -->

<!-- BEGIN: STEP_INSTALL -->
<div class="step-content" id="step-6">
    <h2>{PHP.L.setup_installing_title}</h2>
    <p class="step-desc"></p>
    
    <div class="install-terminal" id="install-terminal">
        <div class="terminal-line"><span class="prompt">&gt;</span> Initializing installation...</div>
    </div>
    
    <div id="install-result" style="display: none"></div>
</div>
<!-- END: STEP_INSTALL -->

<!-- BEGIN: STEP_COMPLETE -->
<div class="setup-complete" id="step-complete">
    <div class="setup-complete-icon"><i class="ic-check success-icon"></i></div>
    <h2>{PHP.L.setup_success_title}</h2>
    <p>{PHP.L.setup_success_desc}</p>
    <div class="complete-links">
        <a href="index.php" class="btn btn-primary" style="display: flex; align-items: center; gap: 6px;">
            <i class="ic-home"></i> {PHP.L.setup_go_home}
        </a>
        <a href="admin" class="btn btn-secondary" style="display: flex; align-items: center; gap: 6px;">
            <i class="ic-settings"></i> {PHP.L.setup_go_admin}
        </a>
    </div>
</div>
<!-- END: STEP_COMPLETE -->
