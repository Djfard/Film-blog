document.addEventListener('DOMContentLoaded', function () {
    var theme = 'light';
    var cookie = document.cookie.split('; ').find(row => row.startsWith('theme'));
    if (cookie) {
        theme = cookie.split('=')[1];
    }
    if (theme == 'light') {
        document.documentElement.style.setProperty('--background-color', 'white');
    } else {
        document.documentElement.style.setProperty('--background-color', '#EFEFEF');
    }
});