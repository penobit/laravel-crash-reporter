<?php

namespace Penobit\CrashReporter;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class CrashReporter {
    public static function handle(\Throwable $exception) {
        /** @var \Illuminate\Http\Request $request */
        $request = request();

        $to = config('crash-reporter.email.to', null);
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        $url = $request->url();
        $body = json_encode($request->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $ip = $request->ip();
        $method = $request->method();
        $userAgent = $request->userAgent();

        if (config('crash-reporter.channels.http', false)) {
            static::sendHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent);
        }

        if (config('crash-reporter.channels.email', false) && !empty($to)) {
            static::sendEmail($to, $message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent);
        }
    }

    protected static function sendEmail(string $to, string $message, string $file, string $line, string $trace, string $url, string $body, string $ip, string $method, string $userAgent) {
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
        );

        return Mail::to($to)->send($mail);
    }

    protected static function sendHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent) {
        $method = config('crash-reporter.http.method', 'POST');
        $method = strtoupper($method);

        if ('POST' === $method) {
            return static::postHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent);
        }
        if ('GET' === $method) {
            return static::getHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent);
        }
    }

    protected static function postHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent) {
        if (!($url = config('crash-reporter.http.endpoint', null))) {
            // throw new \Exception('HTTP endpoint is not set, please set it in config/crash-reporter.php');
            return false;
        }

        $data = [
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
            'url' => $url,
            'body' => $body,
            'ip' => $ip,
            'method' => $method,
            'user_agent' => $userAgent,
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        if ($apiKey = config('crash-reporter.http.auth_token', null)) {
            $headers['Authorization'] = 'Bearer '.$apiKey;
        }

        return Http::withHeaders($headers)->post($url, $data);
    }

    protected static function getHttp($message, $file, $line, $trace, $url, $body, $ip, $method, $userAgent) {
        if (!($url = config('crash-reporter.http.endpoint', null))) {
            // throw new \Exception('HTTP endpoint is not set, please set it in config/crash-reporter.php');
            return false;
        }

        $data = [
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
            'url' => $url,
            'body' => $body,
            'ip' => $ip,
            'method' => $method,
            'user_agent' => $userAgent,
        ];

        if ($apiKey = config('crash-reporter.http.auth_token', null)) {
            $data['token'] = $apiKey;
        }

        return Http::get($url, $data);
    }
}
