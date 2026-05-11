<?php

namespace App\Providers;

use App\Models\ServerConfig;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs when the configured app URL uses https
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME);
        if ($scheme === 'https') {
            URL::forceScheme('https');
        }

        // Resolve frontend layout from session (runtime switch) or config default.
        View::composer('frontend.*', function ($view): void {
            static $speechLabels = null;

            $layout = session('frontend_layout', config('frontend.layout', 'frontend.educavo-v2.page'));

            if ($speechLabels === null) {
                $speechDesignation = trim((string) optional(ServerConfig::first())->principalDesignation);
                if ($speechDesignation === '') {
                    $speechDesignation = 'Head of Institute';
                }

                $speechDesignationLower = strtolower($speechDesignation);
                if (str_contains($speechDesignationLower, 'head master') || str_contains($speechDesignationLower, 'headmaster')) {
                    $speechLabels = [
                        'title' => "Head Master's Message",
                        'nav' => "Head Master's Message",
                    ];
                } elseif (str_contains($speechDesignationLower, 'principal')) {
                    $speechLabels = [
                        'title' => "Principal's Message",
                        'nav' => "Principal's Message",
                    ];
                } else {
                    $speechLabels = [
                        'title' => $speechDesignation . ' Message',
                        'nav' => $speechDesignation . ' Message',
                    ];
                }
            }

            $view->with([
                'frontendLayout' => $layout,
                'frontendSpeechTitle' => $speechLabels['title'],
                'frontendSpeechNavLabel' => $speechLabels['nav'],
            ]);
        });
    }
}
