<?php

namespace Penobit\CrashReporter;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CrashReporter {
    /**
     * Handle thrown exception.
     */
    public static function handle(\Throwable $exception): void {
        if (config('crash-reporter.enabled', false) === false) {
            return;
        }

        /** @var \Illuminate\Http\Request $request */
        $request = request();

        $to = config('crash-reporter.email.to', null);
        $to = \is_string($to) ? explode(';', $to) : $to;
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        if (empty($to)) {
            info('penobit/laravel-crash-reporter: $to is empty', [
                'to' => $to,
            ]);

            return;
        }

        if (!\is_array($to)) {
            info('penobit/laravel-crash-reporter: $to is not an array of recipients', [
                'to' => $to,
            ]);

            return;
        }

        foreach ($to as $mail) {
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                info('penobit/laravel-crash-reporter: $to is not an array of valid email addresses', [
                    'to' => $to,
                ]);

                return;
            }
        }

        $url = $request->url();
        $body = json_encode($request->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $ip = $request->ip();
        $method = $request->method();
        $userAgent = $request->userAgent();
        $referer = $request->headers->get('referer');
        $user = $request->user()->toJson() ?: 'Guest';

        if (config('crash-reporter.channels.http', false)) {
            try {
                static::sendHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent, $referer, $user);
            } catch (\Throwable $e) {
                info('penobit/laravel-crash-reporter: Failed to send http request', [
                    'exception' => $e,
                ]);
            }
        }

        if (config('crash-reporter.channels.email', false) && !empty($to)) {
            try {
                static::sendEmail($to, $message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent, $referer, $user);
            } catch (\Throwable $e) {
                info('penobit/laravel-crash-reporter: Failed to send email', [
                    'to' => $to,
                    'exception' => $e,
                ]);
            }
        }
    }

    /**
     * Send exception details using email.
     *
     * @param array<string>|string $to Email address or array of email addresses
     * @param string $message Exception message
     * @param string $file File where exception was thrown
     * @param string $line Line where exception was thrown
     * @param string $trace Exception trace
     * @param string $url URL where exception was thrown
     * @param string $body Request body
     * @param string $ip IP address of the user
     * @param string $method Request method
     * @param string $userAgent User agent
     */
    protected static function sendEmail(
        string|array $to,
        string $message,
        string $file,
        string $line,
        string $trace,
        string $url,
        string $body,
        string $ip,
        string $method,
        string $userAgent,
        ?string $referer,
        string $user,
    ) {
        $mail = new CrashReporterMail(
            $message,
            $file,
            $line,
            $trace,
            $url,
            $body,
            $ip,
            $method,
            $userAgent,
            $referer,
            $user,
        );

        return Mail::to($to)->send($mail);
    }

    /**
     * Send exception details over HTTP.
     */
    protected static function sendHttp(string $message, string $file, string $line, string $trace, string $url, string $body, string $ip, string $method, string $userAgent, ?string $referer, string $user) {
        $method = config('crash-reporter.http.method', 'POST');
        $method = strtoupper($method);

        if ('POST' === $method) {
            return static::postHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent, $referer, $user);
        }

        return static::getHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent, $referer, $user);
    }

    /**
     * Send exception details over HTTP using POST method.
     */
    protected static function postHttp(string $message, string $file, string $line, string $trace, string $url, string $body, string $ip, string $method, string $userAgent, ?string $referer, string $user) {
        if (!($url = config('crash-reporter.http.endpoint', null))) {
            // throw new \Exception('HTTP endpoint is not set, please set it in config/crash-reporter.php');
            return false;
        }

        $data = compact('message', 'file', 'line', 'trace', 'url', 'body', 'ip', 'method', 'userAgent', 'referer', 'user');

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($apiKey = config('crash-reporter.http.auth_token', null)) {
            $headers['Authorization'] = 'Bearer '.$apiKey;
        }

        return Http::withHeaders($headers)->post($url, $data);
    }

    /**
     * Send exception details over HTTP using GET method.
     */
    protected static function getHttp(string $message, string $file, string $line, string $trace, string $url, string $body, string $ip, string $method, string $userAgent, ?string $referer, string $user) {
        if (!($url = config('crash-reporter.http.endpoint', null))) {
            // throw new \Exception('HTTP endpoint is not set, please set it in config/crash-reporter.php');
            return false;
        }

        $data = compact('message', 'file', 'line', 'trace', 'url', 'body', 'ip', 'method', 'userAgent', 'referer', 'user');

        if ($apiKey = config('crash-reporter.http.auth_token', null)) {
            $data['token'] = $apiKey;
        }

        return Http::get($url, $data);
    }
}
