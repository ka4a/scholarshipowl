@if (App::environment(['production', 'staging']))
    <script src="https://browser.sentry-cdn.com/4.3.0/bundle.min.js" crossorigin="anonymous"></script>
    <script>
        Sentry.init({
            dsn: 'https://0a0ecb1672ac450bad9043bfade8396a@sentry.io/1324579',
            environment: '{{ App::environment()  }}',
        });
    </script>
@endif
