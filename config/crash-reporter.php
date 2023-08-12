<?php

return [
    /*
     * Specifies if crash-reporter is enabled or not
     * If set to false, no error will be reported
     */
    'enabled' => env('CRASH_REPORTER_ENABLED', true),

    /*
     * Determine if the error should be reported by which channels
     * If set to false, no error will be reported through that cannel
     */
    'channels' => [
        'email' => env('CRASH_REPORTER_EMAIL_CHANNEL', true),
        'http' => env('CRASH_REPORTER_HTTP_CHANNEL', false),
    ],

    // Email configurations
    'email' => [
        /*
        * Specify the email address sender name and email address
        * The email address must be a valid email address
        * Required for E-mail channel
        */
        'from' => [
            'address' => env('CRASH_REPORTER_FROM_EMAIL', env('MAIL_FROM_ADDRESS')),
            'name' => env('CRASH_REPORTER_FROM_NAME', env('MAIL_FROM_NAME', 'Laravel Crash Reporter')),
        ],

        /*
        * Specify the email address to send the error report to
        * The email address must be a valid email address
        */
        'to' => env('CRASH_REPORTER_EMAIL_ADDRESS'),
    ],

    // HTTP configurations
    'http' => [
        /*
         * Specify the http method to send the error report
         * The method must be a either GET or POST
         */
        'method' => env('CRASH_REPORTER_HTTP_METHOD', 'POST'),

        /*
        * Specify the http endpoint to send the error report to
        * The endpoint must be a valid url
        * Required for HTTP channel
        */
        'endpoint' => env('CRASH_REPORTER_HTTP_ENDPOINT', null),

        /*
         * HTTP Authentication Token
         * In POST requests a "Authorization" as Berarer token will be set
         * In GET requests a query parameter named "token" will be added to the url
         * If you don't want to use token, set it to null
         */
        'token' => env('CRASH_REPORTER_HTTP_TOKEN', null),
    ],
];