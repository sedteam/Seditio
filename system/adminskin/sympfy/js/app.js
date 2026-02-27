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

    const inputs = document.querySelectorAll('#addmenus input[name="mtitle"], #updatemenus input[name="mtitle"]');
    inputs.forEach((input) => {
        input.sedAutoComplete({
            serviceUrl: '/ajax/?m=pages',
            minChars: 1,
            dataType: 'json',
            noCache: false,
            onSelect(suggestion) {
                const mtitleInputs = document.querySelectorAll('#addmenus input[name="mtitle"], #updatemenus input[name="mtitle"]');
                const murlInputs = document.querySelectorAll('#addmenus input[name="murl"], #updatemenus input[name="murl"]');
                mtitleInputs.forEach((mtitleInput) => {
                    mtitleInput.value = suggestion.title;
                });
                murlInputs.forEach((murlInput) => {
                    murlInput.value = suggestion.url;
                });
            },
            formatResult(suggestion, currentValue) {
                const reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
                const pattern = `(${currentValue.replace(reEscape, '\\$1')})`;
                return `<div class="autocomplete-title">${
                    suggestion.title.replace(new RegExp(pattern, 'gi'), '<strong>$1</strong>')
                }</div><div class="autocomplete-url">${suggestion.url}</div>`;
            }
        });
    });

});