<?php

return [
    'telegram' => [
        'bot' => env('TELEGRAM_BOT_NAME'),
        'client_id' => null,
        'client_secret' => env('TELEGRAM_TOKEN'),
        'redirect' => env('TELEGRAM_REDIRECT_URI'),
    ],
    'telegram-bot-api' => [
        'token' => env('TELEGRAM_TOKEN')
    ],
];