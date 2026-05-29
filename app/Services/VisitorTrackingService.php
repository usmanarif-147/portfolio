<?php

namespace App\Services;

use App\Models\ResumeDownloadLog;
use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class VisitorTrackingService
{
    /**
     * First-party cookie that identifies a unique browser. Uniqueness is keyed
     * on this token (not IP) so different people behind the same WiFi/IP each
     * count once, while the same browser refreshing never re-counts.
     */
    private const VISITOR_COOKIE = 'pf_vid';

    private const COOKIE_MINUTES = 60 * 24 * 365; // ~1 year

    /**
     * Log a public-portfolio page visit. Owner sessions and bots are skipped.
     * A repeat visit from the same browser only bumps the visit counter.
     * Wrapped in a top-level try/catch so tracking can never break the page.
     */
    public function logVisit(Request $request): void
    {
        try {
            if ($this->shouldSkip($request)) {
                return;
            }

            $token = $this->visitorToken($request);
            $existing = VisitorLog::query()->where('visitor_token', $token)->first();

            if ($existing) {
                $existing->forceFill([
                    'visit_count' => $existing->visit_count + 1,
                    'last_seen_at' => now(),
                ])->save();

                return;
            }

            $ip = (string) ($request->ip() ?? '');
            $agent = $this->parseAgent($request);
            $geo = $this->lookupGeo($ip);

            VisitorLog::query()->create([
                'visitor_token' => $token,
                'ip_hash' => $this->hashIp($ip),
                'country' => $geo['country'],
                'country_code' => $geo['country_code'],
                'city' => $geo['city'],
                'browser' => $agent['browser'],
                'os' => $agent['os'],
                'device_type' => $agent['device_type'],
                'referrer' => $this->truncate((string) $request->headers->get('referer', ''), 500),
                'path' => $this->truncate((string) $request->path(), 255),
                'visit_count' => 1,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Log a public-resume PDF download. Owner sessions and bots are skipped.
     * A repeat download from the same browser only bumps the download counter.
     * Wrapped in a top-level try/catch so tracking can never break the download.
     */
    public function logResumeDownload(Request $request, ?string $template = null): void
    {
        try {
            if ($this->shouldSkip($request)) {
                return;
            }

            $token = $this->visitorToken($request);
            $existing = ResumeDownloadLog::query()->where('visitor_token', $token)->first();

            if ($existing) {
                $existing->forceFill([
                    'download_count' => $existing->download_count + 1,
                    'last_downloaded_at' => now(),
                ])->save();

                return;
            }

            $ip = (string) ($request->ip() ?? '');
            $agent = $this->parseAgent($request);
            $geo = $this->lookupGeo($ip);

            ResumeDownloadLog::query()->create([
                'visitor_token' => $token,
                'ip_hash' => $this->hashIp($ip),
                'country' => $geo['country'],
                'country_code' => $geo['country_code'],
                'city' => $geo['city'],
                'browser' => $agent['browser'],
                'os' => $agent['os'],
                'device_type' => $agent['device_type'],
                'template' => $template !== null ? $this->truncate($template, 50) : null,
                'referrer' => $this->truncate((string) $request->headers->get('referer', ''), 500),
                'path' => $this->truncate((string) $request->path(), 255),
                'download_count' => 1,
                'first_downloaded_at' => now(),
                'last_downloaded_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Resolve the per-browser visitor token from the cookie, minting and queueing
     * a new one on first contact. Laravel's AddQueuedCookiesToResponse middleware
     * attaches the queued cookie to the outgoing response (HTML page or PDF alike).
     */
    private function visitorToken(Request $request): string
    {
        $token = $request->cookie(self::VISITOR_COOKIE);

        if (is_string($token) && $token !== '') {
            return $token;
        }

        $token = (string) Str::uuid();
        Cookie::queue(self::VISITOR_COOKIE, $token, self::COOKIE_MINUTES);

        return $token;
    }

    /**
     * Skip tracking when the authenticated owner is browsing or a bot/crawler
     * is detected via User-Agent.
     */
    private function shouldSkip(Request $request): bool
    {
        if (auth()->check()) {
            return true;
        }

        $agent = new Agent;
        $agent->setUserAgent((string) $request->userAgent());

        return $agent->isRobot();
    }

    /**
     * Hash IP with the app key so raw IPs are never persisted but the same
     * visitor still collapses into a single distinct unique-visitor row.
     */
    private function hashIp(string $ip): string
    {
        return hash('sha256', $ip.config('app.key'));
    }

    /**
     * Best-effort geo lookup. Any failure (timeouts, rate limits, unknown IP)
     * resolves to a fully nulled shape so logging always succeeds.
     */
    private function lookupGeo(string $ip): array
    {
        try {
            $loc = geoip($ip);

            return [
                'country' => $loc->country ?? null,
                'country_code' => $loc->iso_code ?? null,
                'city' => $loc->city ?? null,
            ];
        } catch (\Throwable $e) {
            return [
                'country' => null,
                'country_code' => null,
                'city' => null,
            ];
        }
    }

    /**
     * Parse the request User-Agent into browser / OS / coarse device type.
     */
    private function parseAgent(Request $request): array
    {
        $agent = new Agent;
        $agent->setUserAgent((string) $request->userAgent());

        if ($agent->isMobile()) {
            $deviceType = 'mobile';
        } elseif ($agent->isTablet()) {
            $deviceType = 'tablet';
        } else {
            $deviceType = 'desktop';
        }

        $browser = $agent->browser();
        $os = $agent->platform();

        return [
            'browser' => $browser !== false && $browser !== '' ? (string) $browser : null,
            'os' => $os !== false && $os !== '' ? (string) $os : null,
            'device_type' => $deviceType,
        ];
    }

    /**
     * Trim a string to a max character count to fit fixed-width columns.
     */
    private function truncate(string $value, int $length): ?string
    {
        if ($value === '') {
            return null;
        }

        return mb_substr($value, 0, $length);
    }
}
