<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Events\Saml2LogoutEvent;

class LaravelSSOEventServiceProvider extends ServiceProvider
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
        Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function (Saml2LoginEvent $event) {

            $messageId = $event->getSaml2Auth()->getLastMessageId();
            // Add your own code preventing reuse of a $messageId to stop replay attacks

            $user = $event->getSaml2User();

            $userData = [
                'id' => $user->getUserId(),
                'attributes' => $user->getAttributes(),
                'assertion' => $user->getRawSamlAssertion()
            ];

            info('Saml2LoginEvent: ', ['email' => $userData['attributes']['email'][0]]);

            //
            // find user by email or attribute
            //
            $laravelUser = User::where('email', $userData['attributes']['email'][0])->first();

            if ($laravelUser) {

                Auth::login($laravelUser);

                // $request->session()->regenerate();
                // return redirect()->intended('dashboard');
            }
        });

        Event::listen('Aacotroneo\Saml2\Events\Saml2LogoutEvent', function (Saml2LogoutEvent $event) {

            Auth::logout();
            Session::save();

            info('Saml2LogoutEvent: ');

            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
        });
    }
}
