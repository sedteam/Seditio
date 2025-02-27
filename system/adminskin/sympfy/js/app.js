document.addEventListener('DOMContentLoaded', function() {

    // Creates sidebar instance
    var sidebar = sedadminjs.sedSidebar('#sidebar', '.nav-trigger', 'right', '240px', 300);

    // Creates scroll instance
    var scroll = sedadminjs.sedScroll('#sidebar-wrapper', {
        height: '100%',
        color: '#cccccc',
        allowPageScroll: true
    });

    // Initializes menu behaviors
    sedadminjs.initMenu();

    // Initializes content behaviors
    sedadminjs.initContent();

    var inputs = document.querySelectorAll('#addmenus input[name="mtitle"], #updatemenus input[name="mtitle"]');
    inputs.forEach(function(input) {
        input.sedAutoComplete({
            serviceUrl: '/ajax/?m=pages',
            minChars: 1,
            dataType: 'json',
            noCache: false,
            onSelect: function(suggestion) {
                var mtitleInputs = document.querySelectorAll('#addmenus input[name="mtitle"], #updatemenus input[name="mtitle"]');
                var murlInputs = document.querySelectorAll('#addmenus input[name="murl"], #updatemenus input[name="murl"]');
                mtitleInputs.forEach(function(mtitleInput) {
                    mtitleInput.value = suggestion.title;
                });
                murlInputs.forEach(function(murlInput) {
                    murlInput.value = suggestion.url;
                });
            },
            formatResult: function(suggestion, currentValue) {
                var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
                var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
                return '<div class="autocomplete-title">' +
                    suggestion.title.replace(new RegExp(pattern, 'gi'), '<strong>$1</strong>') +
                    '</div><div class="autocomplete-url">' + suggestion.url + '</div>';
            }
        });
    });

});