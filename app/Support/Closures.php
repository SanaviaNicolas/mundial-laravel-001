<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Extraordinary closures (holidays, ...). They come from config('site.closures') with the shape
 * planned for the admin Settings page: ['dal' => 'Y-m-d', 'al' => 'Y-m-d', 'motivo' => ['it' => ..., 'en' => ...]].
 */
class Closures
{
    /** An upcoming closure is announced this many days before it starts. */
    private const NOTICE_DAYS = 30;

    /** Text announcing the closure in progress or the next one, null when there is nothing to say. */
    public static function notice(): ?string
    {
        $now = now();

        foreach (collect(config('site.closures'))->sortBy('dal') as $closure) {
            $from = Carbon::parse($closure['dal'])->startOfDay();
            $to = Carbon::parse($closure['al'])->endOfDay();

            if ($now->gt($to) || $now->lt($from->copy()->subDays(self::NOTICE_DAYS))) {
                continue;
            }

            $notice = $now->gte($from)
                ? __('site.closures.ongoing', ['to' => $to->translatedFormat('j F'), 'reopen' => $to->copy()->addDay()->translatedFormat('j F')])
                : __('site.closures.upcoming', ['from' => $from->translatedFormat($from->isSameMonth($to) ? 'j' : 'j F'), 'to' => $to->translatedFormat('j F')]);
            $reason = $closure['motivo'][app()->getLocale()] ?? $closure['motivo']['it'] ?? null;

            return $reason ? $reason.': '.lcfirst($notice) : $notice;
        }

        return null;
    }
}
