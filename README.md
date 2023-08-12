# Penobit's Laravel Crash Reporter

Laravel crash reporter library will notify you about any uncaught exceptions in your laravel application by Sending you an email or making a POST/GET HTTP request to an endpoint


### Installation

You can install Laravel Crash Reporter package simply using the Composer
Just execute following command:

```bash
php composer require penobit/crash-reporter
```

after compoer downloaded and installed the package you should publish it's config file, To do that run following cmd

```bash
php artisan vendor:publish --tag config
```

This cmd will publish a new file `crash-reporter.php` to your `app/config` directory

----

### Configuration

Laravel crash reporter is completely configurable.
it's configurable using `.env` file or directly from `crash-reporter.php` file.
The `.env` file is our recommended way yo configure the crash reporter

|Configuration|Environment File <div>`.env`</div>|Config file <div>`crash-reporter.php`</div>|Default Value|
|---|---|---|---|
|Is Crash Reporter Enabled|CRASH_REPORTER_ENABLED|`enabled`|`false`|
|Send Exceptions Over Email Channel|CRASH_REPORTER_EMAIL_CHANNEL|`channels.email`|`true`|
|Crash Sender EMail Address|CRASH_REPORTER_FROM_EMAIL|`email.from.address`|`MAIL_FROM_ADDRESS` in `.env`|
|Crash Sender Email's Sender Name|CRASH_REPORTER_FROM_NAME|`email.from.address`|`MAIL_FROM_NAME` in `.env` if available `"Laravel Crash Reporter"` otherwise|
|Send crash reports to<div><sub>Supports multiple addresses seperated by `;`</sub></div>|CRASH_REPORTER_EMAIL_ADDRESS|`email.to`|`null`|
|Send Exceptions Over HTTP Channel|CRASH_REPORTER_HTTP_CHANNEL|`channels.http`|`false`|
|Http request method|CRASH_REPORTER_HTTP_METHOD|`http.method`|`"POST"`|
|Send HTTP request to|CRASH_REPORTER_HTTP_ENDPOINT|`http.endpoint`|`null`|
|HTTP Request Token *<sup>1</sup>|CRASH_REPORTER_HTTP_TOKEN|`http.token`|`null`|

*<sup>1</sup> Please Note!
The token will be sent in two different ways based on HTTP request method:
- GET: the token will be added to the url's query string like: `api.penobit.com/report/crash?token=MY_TOKEN_FROM_ENV_FILE`
- POST: the token will be sent as an authorization bearer header: `Authorization: Bearer MY_TOKEN_FROM_ENV_FILE`

----

### Customizations

You can customize the email template by creating a new template in your `views` directory.
just create your custom email template template in this path:
`/resources/views/vendor/penobit/crash-reporter/crash-reporter-mail.blade.php`
and the crash reporter uses that instead of the default email template.
You can also use these `$data` variable that is an object containing the exceptions details:
- `$data->message`: The Exception message
- `$data->file`: The File where exception was thrown
- `$data->line`: The Line where exception was thrown
- `$data->trace`: The Exception trace
- `$data->url`: The URL where exception was thrown
- `$data->body`: The Request body
- `$data->ip`: The IP address of the user
- `$data->method`: The Request method
- `$data->userAgent`: The Users's User agent
