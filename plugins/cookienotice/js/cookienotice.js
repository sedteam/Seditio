/**
 * Cookie Notice Plugin JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    var cookieNotice = document.getElementById('cookieNotice');
    if (!cookieNotice) {
        return;
    }

    // Check if the user has already accepted cookies
    if (localStorage.getItem('cookiesAccepted') === 'true') {
        cookieNotice.classList.add('hidden');
    } else {
        cookieNotice.classList.remove('hidden');
    }
});

/**
 * Handle cookie acceptance
 */
function acceptCookies() {
    localStorage.setItem('cookiesAccepted', 'true');
    var cookieNotice = document.getElementById('cookieNotice');
    if (cookieNotice) {
        cookieNotice.classList.add('hidden');
    }
}

/**
 * Handle cookie close/decline
 */
function closeCookies() {
    var cookieNotice = document.getElementById('cookieNotice');
    if (cookieNotice) {
        cookieNotice.classList.add('hidden');
    }
}
