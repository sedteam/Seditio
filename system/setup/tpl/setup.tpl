<!-- BEGIN: MAIN -->
<!DOCTYPE html>
<html lang="{PHP.langinstall}">
<head>
    <base href="{PHP.sys.abs_url}" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{PHP.L.setup_title}</title>
    <link rel="stylesheet" href="system/assets/fonts/fonts.css">
    <link rel="stylesheet" href="system/setup/css/setup.css">
</head>
<body>

    <div id="setup-app">
        <!-- Header -->
        <header class="setup-header">
            <img src="system/setup/img/seditio.svg" alt="Seditio" class="setup-logo">
            <span class="setup-version">build {PHP.cfg.version}</span>
        </header>
        
        <!-- Stepper -->
        <nav class="setup-stepper" id="stepper">
            <!-- Stepper elements generated dynamically in JS -->
        </nav>
        
        <!-- Main Step Box Container -->
        <main class="setup-content" id="steps-container">
            {STEP_CONTENT}
        </main>
        
        <!-- Footer -->
        <footer class="setup-footer">
            <p>Seditio &copy; {YEAR} &middot; <a href="https://seditio.org" target="_blank" style="color: var(--text-heading); text-decoration: none; font-weight: 500;">seditio.org</a></p>
        </footer>
    </div>
    
    <!-- Translations for JS -->
    <script>
        const SETUP_LANG = {SETUP_LANG_JSON};
        const SETUP_URL = '{PHP.sys.abs_url}setup';
        const CSRF_TOKEN = '{PHP.csrf_token}';
        const SETUP_CLEAN = {SETUP_CLEAN_JS};
    </script>
    <script src="system/setup/js/setup.js"></script>
</body>
</html>
<!-- END: MAIN -->
