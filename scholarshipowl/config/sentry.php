<?php

return array(
    'dsn'         => env('SENTRY_DSN'),
    'release'     => revision(),
    'environment' => env('SENTRY_ENV', 'dev'),
);
