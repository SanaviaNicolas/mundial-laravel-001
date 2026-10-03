<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $rules = app()->isProduction()
            ? ['Disallow:', '', 'Sitemap: '.url('/sitemap.xml')]
            : ['Disallow: /'];

        return response(implode("\n", ['User-agent: *', ...$rules])."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
