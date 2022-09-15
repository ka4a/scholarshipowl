<?php

use \App\Console\Commands\ScholarshipWinnerNotification;
use App\Services\MauticService;

/**
 * Sunrise system configurations.
 */
return [
    'go' => [
        'url' => env('SUNRISE_GO_URL', 'https://go.scholarship.app'),
    ],
    'barn' => [
        'url' => env('BARN_URL', 'https://go.scholarship.app'),
    ],
    'positive_rewards' => [
        'email' => env('POSITIVE_REWARDS_EMAIL')
    ],
    'mautic' => [
        /**
         * Mautic emails ID for sending transactional emails.
         */
        'emails' => [
            ScholarshipWinnerNotification::FIRST_NOTIFICATION       => env('MAUTIC_EMAILS_WINNER_NOTIFICATION1'),
            ScholarshipWinnerNotification::SECOND_NOTIFICATION      => env('MAUTIC_EMAILS_WINNER_NOTIFICATION2'),
            ScholarshipWinnerNotification::DISQUALIFICATION         => env('MAUTIC_EMAILS_WINNER_DISQUALIFICATION'),
            MauticService::EMAIL_NOTIFICATION_APPLIED_EMAIL         => env('MAUTIC_EMAILS_APPLIED_EMAIL'),
            MauticService::EMAIL_NOTIFICATION_SCHOLARSHIP_PUBLISHED => env('MAUTIC_EMAILS_PUBLISHED_NOTIFICATION')
        ],
        'smses' => [
            ScholarshipWinnerNotification::FIRST_NOTIFICATION   => env('MAUTIC_SMS_WINNER_NOTIFICATION1'),
            ScholarshipWinnerNotification::SECOND_NOTIFICATION  => env('MAUTIC_SMS_WINNER_NOTIFICATION2'),
            ScholarshipWinnerNotification::DISQUALIFICATION     => env('MAUTIC_SMS_WINNER_DISQUALIFICATION'),
        ]
    ]
];
