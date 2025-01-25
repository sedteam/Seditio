(function() {
        var t;

        t = function() {

                var sidebarLeft = new Sidebar('#sidebar', '.nav-trigger', 'left', '240px', 250);
                sidebarLeft.init();

                $('#sidebar-wrapper').slimscroll({
                    height: '100%',
                    color: '#cccccc'
                });

                $(".dropdown-btn").click( // When the h3 is clicked...
                    function() {
                        $(this).parent().find("ul").toggleClass("show"); // Toggle the tabs
                    }
                );

                $(document).on('click', function(e) {
                    if (!$(e.target).closest(".dropdown-menu").length) {
                        $(this).find("ul").removeClass("show"); // Toggle the tabs
                    }
                    e.stopPropagation();
                });

                //Sidebar Accordion Menu:

                $("#main-nav li ul").hide(); // Hide all sub menus
                $("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu

                $("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
                    function() {
                        $(this).toggleClass('current');
                        $(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
                        $(this).next().slideToggle("normal"); // Slide down the clicked sub menu
                        return false;
                    }
                );

                $("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
                    function() {
                        window.location.href = (this.href); // Just open the link instead of a sub menu
                        return false;
                    }
                );

                // Sidebar Accordion Menu Hover Effect:

                $("#main-nav li .nav-top-item").hover(
                    function() {
                        $(this).stop().animate({ paddingRight: "25px" }, 200);
                    },
                    function() {
                        $(this).stop().animate({ paddingRight: "15px" });
                    }
                );

                //Minimize Content Box

                $(".content-box-header h3").css({ "cursor": "s-resize" }); // Give the h3 in Content Box Header a different cursor
                $(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
                $(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"

                $(".content-box-header h3").click( // When the h3 is clicked...
                    function() {
                        $(this).parent().next().toggle(); // Toggle the Content Box
                        $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
                        $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
                    }
                );

                //Close button:

                $(".close, .alert-close").click(
                    function() {
                        $(this).parent().fadeTo(400, 0, function() { // Links with the class "close" will close parent
                            $(this).slideUp(400);
                        });
                        return false;
                    }
                );

                // Alternating table rows:

                $('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

                // Check all checkboxes when the one in a table head is checked:

                $('.check-all').click(
                    function() {
                        $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
                    }
                );

                // Autocomlete for Menu manager

                $("#addmenus input[name='mtitle'], #updatemenus input[name='mtitle']").autocomplete({
                    serviceUrl: '/ajax/?m=pages',
                    minChars: 1,
                    dataType: 'json',
                    noCache: false,
                    onSelect: function(suggestion) {
                        $("#addmenus input[name='mtitle'], #updatemenus input[name='mtitle']").val(suggestion.title);
                        $("#addmenus input[name='murl'], #updatemenus input[name='murl']").val(suggestion.url);
                    },
                    formatResult: function(suggestions, currentValue) {
                        console.log(suggestions);
                        var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
                        var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
                        return '<div class="autocomplete-title">' + suggestions.title.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>') + '</div><div class="autocomplete-url">' + suggestions.url + '</div>';
                    }
                });

            },
            $(document).ready(t)
    }
    .call(this));