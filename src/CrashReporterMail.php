<?php

namespace Penobit\CrashReporter;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CrashReporterMail extends Mailable {
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $message = 'Unexpected Error',
        public string $file = '',
        public string $line = '',
        public string $trace = '',
        public string $url = '',
        public string $body = '',
        public string $ip = '',
        public string $method = '',
        public string $userAgent = '',
        public ?string $referer = null,
        public string $user = '',
    ) {
    }

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
                'userAgent' => $this->userAgent,
                'referer' => $this->referer,
                'user' => $this->user,
                'browser' => $this->getBrowser(),
                'browser_logo' => $this->getBrowserLogo(),
                'os' => $this->getOS(),
                'os_logo' => $this->getOSLogo(),
            ])
        ;
    }

    /**
     * Get the browser name.
     */
    public function getBrowser(): string {
        $ua = $this->userAgent;

        $browsers = [
            'Opera' => 'Opera',
            'OPR' => 'Opera',
            'Edg' => 'Edge',
            'Brave' => 'Brave',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
            'MSIE' => 'Internet Explorer',
        ];

        foreach ($browsers as $key => $browser) {
            if (strpos($ua, $key) !== false) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    /**
     * Get the browser logo.
     */
    public function getBrowserLogo(): string {
        $browser = $this->getBrowser();

        $logos = [
            'firefox' => __DIR__.'/../resources/images/browsers/firefox.svg',
            'chrome' => __DIR__.'/../resources/images/browsers/chrome.svg',
            'edge' => __DIR__.'/../resources/images/browsers/edge.svg',
            'opera' => __DIR__.'/../resources/images/browsers/opera.svg',
            'brave' => __DIR__.'/../resources/images/browsers/brave.svg',
            'safari' => __DIR__.'/../resources/images/browsers/safari.svg',
            'internet explorer' => __DIR__.'/../resources/images/browsers/internet-explorer.svg',
        ];

        foreach ($logos as $key => $logo) {
            if (strpos(strtolower($browser), $key) !== false) {
                // base 64 encoded data of image
                $base64 = base64_encode(file_get_contents($logo));

                return 'data:image/svg+xml;base64,'.$base64;
            }
        }
    }

    /**
     * Get the operating system name.
     */
    public function getOS(): ?string {
        $oses = [
            'Windows 10' => 'Windows NT 10.0+',
            'Windows 8.1' => 'Windows NT 6.3+',
            'Windows 8' => 'Windows NT 6.2+',
            'Windows 7' => 'Windows NT 6.1+',
            'Windows Vista' => 'Windows NT 6.0+',
            'Windows Server 2003' => 'Windows NT 5.2+',
            'Windows XP' => 'Windows NT 5.1+',
            'Windows 2000' => 'Windows NT 5.0+',
            'Windows ME' => 'Windows ME',
            'Windows 98' => 'Windows 98+',
            'Windows 95' => 'Windows 95+',
            'Windows NT 4.0' => 'Windows NT 4.0+',
            'Windows CE' => 'Windows CE',
            'Windows 3.11' => 'Windows 3.11+',
            'Windows Phone 7.0' => 'Windows Phone OS 7.0+',
            'Windows Phone 7.5' => 'Windows Phone OS 7.5+',
            'Windows Phone 8.0' => 'Windows Phone 8.0+',
            'Windows Phone 8.1' => 'Windows Phone 8.1+',
            'Windows Phone 10.0' => 'Windows Phone 10.0+',
            'Windows' => 'Windows',
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'iPod' => 'iPod',
            'OS X 10.15' => 'Mac OS X 10.15+',
            'OS X 10.14' => 'Mac OS X 10.14+',
            'OS X 10.13' => 'Mac OS X 10.13+',
            'OS X 10.12' => 'Mac OS X 10.12+',
            'OS X 10.11' => 'Mac OS X 10.11+',
            'OS X 10.10' => 'Mac OS X 10.10+',
            'OS X 10.9' => 'Mac OS X 10.9+',
            'OS X 10.8' => 'Mac OS X 10.8+',
            'OS X 10.7' => 'Mac OS X 10.7+',
            'OS X 10.6' => 'Mac OS X 10.6+',
            'OS X 10.5' => 'Mac OS X 10.5+',
            'OS X 10.4' => 'Mac OS X 10.4+',
            'OS X 10.3' => 'Mac OS X 10.3+',
            'OS X 10.2' => 'Mac OS X 10.2+',
            'OS X 10.1' => 'Mac OS X 10.1+',
            'OS X 10.0' => 'Mac OS X 10.0+',
            'OS X' => 'Mac OS X',
            'Linux' => 'Linux',
            'Linux' => 'X11',
            'Ubuntu' => 'Ubuntu',
            'Android' => 'Android',
            'BlackBerry' => 'BlackBerry',
            'FreeBSD' => 'FreeBSD',
            'OpenBSD' => 'OpenBSD',
            'NetBSD' => 'NetBSD',
            'iOS' => 'iOS',
            'OS/2' => 'OS/2',
            'Unix' => 'Unix',
            'Sun OS' => 'Sun OS',
            'Solaris' => 'Solaris',
            'Android' => 'Android',
            'Open BSD' => 'OpenBSD',
            'Sun OS' => 'SunOS',
            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS/2' => 'OS/2',
            'Search Bot' => 'nuhk',
            'Google Search Bot' => 'Googlebot',
            'Yammy Search Bot' => 'Yammybot',
            'Openbot Search Bot' => 'Openbot',
            'Search Bot' => 'Slurp/cat',
            'MSN Search Bot' => 'msnbot',
            'Archiver Search Bot' => 'ia_archiver',
        ];

        foreach ($oses as $key => $os) {
            if (preg_match('~'.$os.'~i', $this->userAgent)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Get Os Logo.
     */
    public function getOsLogo(): ?string {
        $os = $this->getOS();

        $logos = [
            'windows' => __DIR__.'/../resources/images/os/windows.svg',
            'linux' => __DIR__.'/../resources/images/os/linux.svg',
            'mac' => __DIR__.'/../resources/images/os/osx.svg',
            'ios' => __DIR__.'/../resources/images/os/ios.svg',
            'android' => __DIR__.'/../resources/images/os/android.svg',
        ];

        foreach ($logos as $key => $logo) {
            if (strpos(strtolower($os), $key) !== false) {
                // base 64 encoded data of image
                $base64 = base64_encode(file_get_contents($logo));

                return 'data:image/svg+xml;base64,'.$base64;
            }
        }

        return null;
    }
}