{{-- Apply the stored appearance (localStorage > cookie > system) before first paint to avoid a flash. --}}
<script>
    (function () {
        var appearance = null;

        try {
            appearance = localStorage.getItem('appearance');
        } catch (e) {}

        if (appearance !== 'light' && appearance !== 'dark' && appearance !== 'system') {
            var match = document.cookie.match(/(?:^|;\s*)appearance=([^;]+)/);
            appearance = match ? decodeURIComponent(match[1]) : null;
        }

        if (appearance !== 'light' && appearance !== 'dark' && appearance !== 'system') {
            appearance = 'system';
        }

        var root = document.documentElement;
        var media = window.matchMedia('(prefers-color-scheme: dark)');

        function apply(mode) {
            var isDark = mode === 'system' ? media.matches : mode === 'dark';

            root.classList.toggle('dark', isDark);
            root.style.colorScheme = isDark ? 'dark' : 'light';
            root.dataset.appearance = mode;
        }

        window.ElevationTheme = {
            get: function () {
                return appearance;
            },
            set: function (mode) {
                appearance = mode;

                try {
                    localStorage.setItem('appearance', mode);
                } catch (e) {}

                document.cookie = 'appearance=' + encodeURIComponent(mode) + '; path=/; max-age=31536000; SameSite=Lax';

                apply(mode);
            },
        };

        apply(appearance);

        media.addEventListener('change', function () {
            if (appearance === 'system') {
                apply('system');
            }
        });
    })();
</script>

<style>
    html {
        background-color: hsl(30 10% 98%);
    }

    html.dark {
        background-color: hsl(30 5% 6%);
    }
</style>
