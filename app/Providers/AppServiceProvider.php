<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Symfony\Component\Console\Output\ConsoleOutput;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use BeyondCode\LaravelWebSockets\Server\Logger\WebsocketsLogger;
use App\WebSockets\CustomWebSocketHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // https://github.com/beyondcode/laravel-websockets/issues/21
        /*app()->singleton(WebsocketsLogger::class, function () {
            return (new WebsocketsLogger(new ConsoleOutput()))->enable(true);
        });*/

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
