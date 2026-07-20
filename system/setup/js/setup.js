/**
 * Seditio Setup — SPA Modern Installer JavaScript
 * Vanilla JS, no dependencies
 */

const ICONS = {
    hand: `<i class="ic-hand-move"></i>`,
    shield: `<i class="ic-shield-check"></i>`,
    database: `<i class="ic-server"></i>`,
    settings: `<i class="ic-settings"></i>`,
    puzzle: `<i class="ic-plug"></i>`,
    rocket: `<i class="ic-zap"></i>`,
    box: `<i class="ic-box"></i>`,
    plug: `<i class="ic-plug"></i>`,
    dices: `<i class="ic-repeat"></i>`,
    user: `<i class="ic-user"></i>`,
    home: `<i class="ic-home"></i>`,
    check: `<i class="ic-check success-icon"></i>`
};

class SeditioSetup {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 6;
        this.formData = {
            db: {
                host: 'localhost',
                name: '',
                user: 'root',
                pass: '',
                prefix: 'sed_',
                clear: 0
            },
            site: {
                skin: 'sympfy',
                lang: 'ru',
                admin_name: 'admin',
                admin_pass: '',
                admin_email: '',
                admin_country: 'RU'
            },
            extensions: {
                modules: [],
                plugins: []
            }
        };
        this.dbTested = false;
        
        this.init();
    }
    
    init() {
        if (typeof SETUP_CLEAN !== 'undefined' && SETUP_CLEAN) {
            sessionStorage.removeItem('setup_step');
            sessionStorage.removeItem('setup_data');
        }
        this.restoreState();
        this.renderStepper();
        this.updateStepper();
        
        // If restoring a step greater than 1, fetch it on load
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep);
        } else {
            this.initWelcome();
        }
    }
    
    // ==========================================
    // STEPPER
    // ==========================================
    
    renderStepper() {
        const stepper = document.getElementById('stepper');
        if (!stepper) return;
        
        let stepsHTML = '';
        for (let i = 1; i <= this.totalSteps; i++) {
            const num = i;
            const title = SETUP_LANG[`step${i}_title`] || `Step ${i}`;
            const iconKey = SETUP_LANG[`step${i}_icon`] || '•';
            const iconHTML = ICONS[iconKey] || iconKey;
            
            stepsHTML += `
                <div class="stepper-item pending" id="stepper-item-${num}" onclick="setup.goToStep(${num})">
                    <div class="stepper-circle">${iconHTML}</div>
                    <div class="stepper-label">${title}</div>
                </div>
            `;
        }
        
        stepper.innerHTML = `
            <div class="stepper-progress" id="stepper-progress" style="width: 0%"></div>
            ${stepsHTML}
        `;
        this.updateStepper();
    }
    
    updateStepper() {
        const stepNum = Math.min(this.currentStep, this.totalSteps);
        const progress = document.getElementById('stepper-progress');
        if (progress) {
            const ratio = (stepNum - 1) / (this.totalSteps - 1);
            progress.style.width = `calc((100% - 100% / ${this.totalSteps}) * ${ratio})`;
        }
        
        for (let i = 1; i <= this.totalSteps; i++) {
            const item = document.getElementById(`stepper-item-${i}`);
            if (!item) continue;
            
            item.className = 'stepper-item';
            if (i < this.currentStep) {
                item.classList.add('completed');
            } else if (i === this.currentStep) {
                item.classList.add('active');
            } else {
                item.classList.add('pending');
            }
        }
    }
    
    // ==========================================
    // NAVIGATION & DYNAMIC STEP FETCHING
    // ==========================================
    
    async goToStep(step) {
        if (step > this.currentStep && !this.validateStep(this.currentStep)) {
            return;
        }
        
        const container = document.getElementById('steps-container');
        if (!container) return;
        
        container.style.opacity = '0.5';
        
        try {
            const res = await this.ajax('get_step', { step: step });
            if (res.ok && res.html) {
                container.innerHTML = res.html;
                this.currentStep = step;
                this.updateStepper();
                this.saveState();
                
                switch (step) {
                    case 1: this.initWelcome(); break;
                    case 2: this.initSystemCheck(); break;
                    case 3: this.initDatabase(); break;
                    case 4: this.initSettings(); break;
                    case 5: this.initExtensions(); break;
                    case 6: this.initInstall(); break;
                }
            } else {
                this.showToast(res.error || 'Failed to load step');
            }
        } catch (e) {
            console.error('Error fetching step:', e);
            this.showToast('Network error while loading step');
        } finally {
            container.style.opacity = '1';
        }
    }
    
    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.goToStep(this.currentStep + 1);
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }
    
    // ==========================================
    // STEP 1: WELCOME
    // ==========================================
    
    initWelcome() {
        const select = document.getElementById('langinstall-select');
        if (!select) return;
        
        select.value = SETUP_LANG.current_lang;
        
        if (!select.dataset.listenerAttached) {
            select.dataset.listenerAttached = "true";
            select.addEventListener('change', async (e) => {
                const newLang = e.target.value;
                const res = await this.ajax('set_lang', { langinstall: newLang });
                if (res.ok) {
                    window.location.reload();
                }
            });
        }
    }
    
    // ==========================================
    // STEP 2: SYSTEM CHECK
    // ==========================================
    
    initSystemCheck() {
        // System checks are pre-rendered server-side via XTemplate CHECK_ROW block
    }
    
    // ==========================================
    // STEP 3: DATABASE SETTINGS
    // ==========================================
    
    initDatabase() {
        const db = this.formData.db;
        
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val;
        };
        
        setVal('db-host', db.host);
        setVal('db-name', db.name);
        setVal('db-user', db.user);
        setVal('db-pass', db.pass);
        setVal('db-prefix', db.prefix);
        
        const clearCb = document.getElementById('db-clear');
        if (clearCb) clearCb.checked = !!db.clear;
    }
    
    async testDatabase() {
        this.saveDbFields();
        const db = this.formData.db;
        
        const btn = document.getElementById('btn-test-db');
        const resEl = document.getElementById('db-test-result');
        if (!btn || !resEl) return;
        
        if (!db.name) {
            this.shakeField('db-name');
            resEl.innerHTML = `<span class="fail">✗ ${SETUP_LANG.error_field_required}</span>`;
            return;
        }
        
        btn.disabled = true;
        resEl.innerHTML = `<span class="testing">⏳ ${SETUP_LANG.testing}...</span>`;
        
        try {
            const res = await this.ajax('test_db', db);
            if (res.ok) {
                resEl.innerHTML = `<span class="ok">✓ ${res.message}</span>`;
                this.dbTested = true;
            } else {
                resEl.innerHTML = `<span class="fail">✗ ${res.error || 'Connection failed'}</span>`;
                this.dbTested = false;
            }
        } catch (e) {
            resEl.innerHTML = `<span class="fail">✗ Request error</span>`;
            this.dbTested = false;
        } finally {
            btn.disabled = false;
        }
    }
    
    saveDbFields() {
        const host = document.getElementById('db-host')?.value || '';
        const name = document.getElementById('db-name')?.value || '';
        const user = document.getElementById('db-user')?.value || '';
        const pass = document.getElementById('db-pass')?.value || '';
        const prefix = document.getElementById('db-prefix')?.value || '';
        const clear = document.getElementById('db-clear')?.checked ? 1 : 0;
        
        this.formData.db = { host, name, user, pass, prefix, clear };
    }
    
    // ==========================================
    // STEP 4: SITE SETTINGS
    // ==========================================
    
    initSettings() {
        const site = this.formData.site;
        
        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = val;
        };
        
        setVal('admin-name', site.admin_name);
        setVal('admin-pass', site.admin_pass);
        setVal('admin-email', site.admin_email);
        
        const countrySelect = document.getElementById('admin-country');
        if (countrySelect && site.admin_country) {
            countrySelect.value = site.admin_country;
        }
        
        const passInput = document.getElementById('admin-pass');
        if (passInput && !passInput.dataset.listenerAttached) {
            passInput.dataset.listenerAttached = "true";
            passInput.addEventListener('input', (e) => this.checkPasswordStrength(e.target.value));
        }
        if (passInput) {
            this.checkPasswordStrength(passInput.value);
        }
        
        if (site.skin) {
            this.selectSkin(site.skin);
        }
    }
    
    selectSkin(code) {
        this.formData.site.skin = code;
        document.querySelectorAll('.skin-card').forEach(card => {
            if (card.getAttribute('data-skin') === code) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
        this.saveState();
    }
    
    generatePassword() {
        const chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%&*';
        let pass = '';
        const randArr = new Uint32Array(12);
        if (window.crypto && window.crypto.getRandomValues) {
            window.crypto.getRandomValues(randArr);
            for (let i = 0; i < randArr.length; i++) {
                pass += chars.charAt(randArr[i] % chars.length);
            }
        } else {
            for (let i = 0; i < 12; i++) {
                pass += chars.charAt(Math.floor(Math.random() * chars.length));
            }
        }
        
        const input = document.getElementById('admin-pass');
        if (input) {
            input.value = pass;
            input.type = 'text';
            this.checkPasswordStrength(pass);
            this.copyToClipboard(pass);
        }
    }
    
    copyToClipboard(text) {
        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            navigator.clipboard.writeText(text).then(() => {
                this.showToast(SETUP_LANG.password_copied || 'Password copied to clipboard');
            }).catch(err => {
                console.warn('Clipboard write failed', err);
            });
        } else {
            try {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.showToast(SETUP_LANG.password_copied || 'Password copied to clipboard');
            } catch (e) {
                console.warn('Fallback clipboard copy failed', e);
            }
        }
    }
    
    checkPasswordStrength(pass) {
        const container = document.getElementById('password-strength-container');
        if (!container) return;
        
        let score = 0;
        if (!pass) {
            container.className = 'password-strength';
            return;
        }
        
        if (pass.length >= 8) score++;
        if (pass.length >= 12) score++;
        if (/[A-Z]/.test(pass) && /[a-z]/.test(pass)) score++;
        if (/[0-9]/.test(pass)) score++;
        if (/[^A-Za-z0-9]/.test(pass)) score++;
        
        const classes = ['', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong'];
        const scoreClass = classes[Math.min(score, 4)];
        container.className = `password-strength ${scoreClass}`;
    }
    
    saveSettingsFields() {
        const skinCard = document.querySelector('.skin-card.active');
        const skin = skinCard ? skinCard.getAttribute('data-skin') : 'sympfy';
        const lang = document.getElementById('site-lang')?.value || 'ru';
        const admin_name = document.getElementById('admin-name')?.value || 'admin';
        const admin_pass = document.getElementById('admin-pass')?.value || '';
        const admin_email = document.getElementById('admin-email')?.value || '';
        const admin_country = document.getElementById('admin-country')?.value || 'RU';
        
        this.formData.site = { skin, lang, admin_name, admin_pass, admin_email, admin_country };
    }
    
    // ==========================================
    // STEP 5: MODULES AND PLUGINS
    // ==========================================
    
    initExtensions() {
        // Extensions list pre-rendered server-side via XTemplate MODULE_ROW & PLUGIN_ROW blocks
    }
    
    switchExtTab(tab) {
        const modulesTab = document.getElementById('tab-btn-modules');
        const pluginsTab = document.getElementById('tab-btn-plugins');
        const modulesContainer = document.getElementById('ext-container-modules');
        const pluginsContainer = document.getElementById('ext-container-plugins');
        
        if (!modulesTab || !pluginsTab || !modulesContainer || !pluginsContainer) return;
        
        if (tab === 'modules') {
            modulesTab.classList.add('active');
            pluginsTab.classList.remove('active');
            modulesContainer.style.display = 'block';
            pluginsContainer.style.display = 'none';
        } else {
            modulesTab.classList.remove('active');
            pluginsTab.classList.add('active');
            modulesContainer.style.display = 'none';
            pluginsContainer.style.display = 'block';
        }
    }
    
    saveExtensionsSelection() {
        const selectedModules = [];
        document.querySelectorAll('.module-toggle').forEach(el => {
            if (el.checked || el.disabled) {
                selectedModules.push(el.getAttribute('data-code'));
            }
        });
        
        const selectedPlugins = [];
        document.querySelectorAll('.plugin-toggle').forEach(el => {
            if (el.checked) {
                selectedPlugins.push(el.getAttribute('data-code'));
            }
        });
        
        this.formData.extensions = {
            modules: selectedModules,
            plugins: selectedPlugins
        };
    }
    
    // ==========================================
    // STEP 6: INSTALLATION
    // ==========================================
    
    async initInstall() {
        const terminal = document.getElementById('install-terminal');
        if (!terminal) return;
        
        terminal.innerHTML = '<div class="terminal-line"><span class="prompt">&gt;</span> Initializing installation...</div>';
        
        const payload = {
            mysqlhost: this.formData.db.host,
            mysqluser: this.formData.db.user,
            mysqlpassword: this.formData.db.pass,
            mysqldb: this.formData.db.name,
            sqldbprefix: this.formData.db.prefix,
            db_clear_before_import: this.formData.db.clear,
            defaultskin: this.formData.site.skin,
            defaultlang: this.formData.site.lang,
            admin_name: this.formData.site.admin_name,
            admin_pass: this.formData.site.admin_pass,
            admin_email: this.formData.site.admin_email,
            admin_country: this.formData.site.admin_country,
            modules: this.formData.extensions.modules,
            plugins: this.formData.extensions.plugins
        };
        
        try {
            const res = await this.ajax('run_install', payload);
            if (res.ok && res.log) {
                for (let i = 0; i < res.log.length; i++) {
                    const line = res.log[i];
                    await this.delay(200);
                    
                    const lineEl = document.createElement('div');
                    lineEl.className = 'terminal-line';
                    
                    const statusClass = line.ok ? 'ok' : 'fail';
                    const statusText = line.ok ? '✓ OK' : '✗ FAIL';
                    
                    lineEl.innerHTML = `<span class="prompt">&gt;</span> ${line.msg} ... <span class="${statusClass}">${statusText}</span>`;
                    terminal.appendChild(lineEl);
                    terminal.scrollTop = terminal.scrollHeight;
                }
                
                await this.delay(600);
                this.renderFinalSuccess(res.complete_html);
            } else {
                const lineEl = document.createElement('div');
                lineEl.className = 'terminal-line';
                lineEl.innerHTML = `<span class="prompt">&gt;</span> <span class="fail">CRITICAL ERROR: ${res.error || 'Installation aborted'}</span>`;
                terminal.appendChild(lineEl);
            }
        } catch (e) {
            const lineEl = document.createElement('div');
            lineEl.className = 'terminal-line';
            lineEl.innerHTML = `<span class="prompt">&gt;</span> <span class="fail">CRITICAL ERROR: Network request failed. Details: ${e.message}</span>`;
            terminal.appendChild(lineEl);
            console.error(e);
        }
    }
    
    async renderFinalSuccess(html) {
        const container = document.getElementById('steps-container');
        if (container && html) {
            container.innerHTML = html;
        } else {
            try {
                const res = await this.ajax('get_step', { step: 7 });
                if (res.ok && res.html && container) {
                    container.innerHTML = res.html;
                }
            } catch (e) {
                console.warn('Could not fetch step 7 via AJAX:', e);
            }
        }
        
        this.currentStep = 7;
        this.updateStepper();
        sessionStorage.removeItem('setup_step');
        sessionStorage.removeItem('setup_data');
    }
    
    // ==========================================
    // VALIDATION
    // ==========================================
    
    validateStep(step) {
        switch (step) {
            case 1:
                return true;
            case 2:
                return true;
            case 3:
                this.saveDbFields();
                if (!this.formData.db.name) {
                    this.shakeField('db-name');
                    this.showToast(SETUP_LANG.error_field_required);
                    return false;
                }
                return true;
            case 4:
                this.saveSettingsFields();
                const site = this.formData.site;
                let isValid = true;
                
                if (!site.admin_name) {
                    this.shakeField('admin-name');
                    isValid = false;
                }
                if (!site.admin_pass || site.admin_pass.length < 8) {
                    this.shakeField('admin-pass');
                    isValid = false;
                }
                if (!site.admin_email || !site.admin_email.includes('@')) {
                    this.shakeField('admin-email');
                    isValid = false;
                }
                
                if (!isValid) {
                    this.showToast(SETUP_LANG.error_field_required);
                    return false;
                }
                return true;
            case 5:
                this.saveExtensionsSelection();
                return true;
            default:
                return true;
        }
    }
    
    shakeField(id) {
        const el = document.getElementById(id);
        if (!el) return;
        
        el.classList.add('is-invalid');
        el.style.animation = 'none';
        el.offsetHeight; 
        el.style.animation = 'shake 0.4s ease';
        
        if (!document.getElementById('shake-style')) {
            const style = document.createElement('style');
            style.id = 'shake-style';
            style.innerHTML = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-6px); }
                    75% { transform: translateX(6px); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // ==========================================
    // UTILS
    // ==========================================
    
    async ajax(action, data = {}) {
        const payload = new URLSearchParams();
        payload.append('ajax_action', action);
        payload.append('csrf_token', CSRF_TOKEN);
        
        for (const [key, val] of Object.entries(data)) {
            if (Array.isArray(val)) {
                val.forEach(v => payload.append(key + '[]', v));
            } else {
                payload.append(key, val);
            }
        }
        
        const res = await fetch(SETUP_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            },
            body: payload.toString()
        });
        
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            throw new Error(`Server returned HTML/text: ${text.substring(0, 400)}`);
        }
    }
    
    showToast(msg) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = msg;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }
    
    saveState() {
        sessionStorage.setItem('setup_step', this.currentStep);
        sessionStorage.setItem('setup_data', JSON.stringify(this.formData));
    }
    
    restoreState() {
        const step = sessionStorage.getItem('setup_step');
        const data = sessionStorage.getItem('setup_data');
        if (step) {
            this.currentStep = parseInt(step);
        }
        if (data) {
            try {
                this.formData = JSON.parse(data);
            } catch (e) {
                console.error(e);
            }
        }
    }
    
    delay(ms) {
        return new Promise(r => setTimeout(r, ms));
    }
}

// Instantiate setup SPA
let setup;
document.addEventListener('DOMContentLoaded', () => {
    setup = new SeditioSetup();
});
