<div class="content">
    <div class="title">Something went wrong.</div>
    @unless(empty($sentryID))
    @include('common.sentry')
    <script>
        if (typeof Raven !== 'undefined') {
            Raven.showReportDialog({
                eventId: '{!! $sentryID !!}'
            });
        }
    </script>
    @endunless
</div>
