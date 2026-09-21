<meta name="google" content="notranslate">
<script>
    // Retire only state written by the former website translation widget.
    (function () {
        try { localStorage.removeItem('site_language_pref'); } catch (error) {}
        try { sessionStorage.removeItem('site_language_pref'); } catch (error) {}
        if (document.cookie.split(';').some(function (cookie) {
            return cookie.trim().indexOf('googtrans=') === 0;
        })) {
            const expired = 'googtrans=; Max-Age=0; path=/';
            document.cookie = expired;
            document.cookie = expired + '; domain=' + window.location.hostname;
        }
    })();
</script>
