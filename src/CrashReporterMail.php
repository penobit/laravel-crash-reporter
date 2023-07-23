<?php

namespace Penobit\CrashReporter;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrashReporterMail extends Mailable {
    use Queueable;
    use SerializesModels;

    public $message;
    public $file;
    public $line;
    public $trace;
    public $url;
    public $body;
    public $ip;
    public $method;
    public $agent;

    /**
     * Create a new message instance.
     */
    public function __construct(string $message, string $file, string $line, string $trace, string $url, string $body, string $ip, string $method, string $agent) {
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
        $this->trace = $trace;
        $this->url = $url;
        $this->body = $body;
        $this->ip = $ip;
        $this->method = $method;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this
            ->subject(sprintf('Crash Report - %s', $this->message))
            ->from(config('crash-reporter.from.address'), config('crash-reporter.from.name'))
            ->view('crash-reporter::crash-reporter-mail')
            ->with('data', (object) [
                'message' => $this->message,
                'file' => $this->file,
                'line' => $this->line,
                'trace' => $this->trace,
                'url' => $this->url,
                'body' => $this->body,
                'ip' => $this->ip,
                'method' => $this->method,
                'agent' => $this->agent,
            ])
        ;
    }
}