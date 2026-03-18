document.addEventListener('DOMContentLoaded', () => {

    // Creates sidebar instance
    const sidebar = sedadminjs.sedSidebar('#sidebar', '.nav-trigger', 'right', '240px', 300);

    // Creates scroll instance
    const scroll = sedadminjs.sedScroll('#sidebar-wrapper', {
        height: '100%',
        color: '#cccccc',
        allowPageScroll: true
    });

    // Initializes menu behaviors
    sedadminjs.initMenu();

    // Initializes content behaviors
    sedadminjs.initContent();

    const selectors = [
        '#addmenus input[name="mtitle"]',
        '#updatemenus input[name="mtitle"]'
    ];

    selectors.forEach((sel) => {
        const inputs = document.querySelectorAll(sel);
        inputs.forEach((input) => {
            if (input && !Autocomplete.getInstance(input)) {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const container = input.closest('#addmenus, #updatemenus');
                const hintInput = container ? container.querySelector('input[data-autocomplete-hint]') : null;

                new Autocomplete(input, {
                    serviceUrl: '/ajax/?m=pages',
                    formatResult(suggestion, currentValue) {
                        const text = suggestion.title || suggestion.value;
                        if (!currentValue) return text;
                        const pattern = `(${Autocomplete.utils.escapeRegExChars(currentValue)})`;
                        return text
                            .replace(new RegExp(pattern, 'gi'), '<strong>$1</strong>')
                            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')
                            .replace(/&lt;(\/?strong)&gt;/g, '<$1>');
                    },
                    onHint: hintInput ? (hint) => {
                        hintInput.value = hint || '';
                    } : undefined,
                    onSelect: (suggestion) => {
                        const cont = input.closest('#addmenus, #updatemenus');
                        input.value = suggestion.title || suggestion.value;
                        if (hintInput) hintInput.value = '';
                        if (cont) {
                            const murl = cont.querySelector('input[name="murl"]');
                            if (murl) murl.value = suggestion.url || '';
                        }
                    },
                    ajaxSettings: {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Seditio-Csrf': csrfMeta ? csrfMeta.content : ''
                        },
                        credentials: 'same-origin'
                    }
                });
            }
        });
    });

});
