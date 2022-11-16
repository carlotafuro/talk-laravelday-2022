<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class UserProfileAPIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        RateLimiter::for('user_profile_rate', function (Request $request) {

            info('request username: ', ['username' => $request->input('username')]);

            if ( $request->input('username') !== null and trim($request->input('username')) != '' ) {

                $rate_per_minute = 5;
                $rate_by = $request->input('username');

            } else {

                $rate_per_minute = 60;
                $rate_by = optional($request->user())->id ?: $request->ip();
            }

            return Limit::perMinute($rate_per_minute)
            ->by($rate_by)
            ->response(function (Request $request, array $headers) {
                return response(
                    json_encode([
                        'result' => false,
                        'error' => 'too many login attempts', // 'Too many login attempts. Please try again in :seconds seconds.'
                        'retry_after' => $headers['Retry-After'],
                        'user_data' => (object) []
                    ]),
                    429,
                    $headers
                );
            });
        });
    }
}
