<?php

namespace App\Providers;

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

            $viewData = $view->getData();
            $layout = $viewData['frontendLayout']
                ?? session('frontend_layout', config('frontend.layout', 'frontend.educavo-v2.page'));

            if ($speechLabels === null) {
                $speechLabels = [
                    'title' => 'Head of Institute Message',
                    'nav' => 'Head of Institute Message',
                ];
            }

            $view->with([
                'frontendLayout' => $layout,
                'frontendSpeechTitle' => $speechLabels['title'],
                'frontendSpeechNavLabel' => $speechLabels['nav'],
            ]);
        });
    }
}
