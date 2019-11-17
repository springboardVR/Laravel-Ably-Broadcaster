<?php

namespace SpringboardVR\LaravelAblyBroadcaster;

use Ably\AblyRest;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Arr;

class LaravelAblyBroadcasterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Broadcast::extend('ably', function () {
            return new AblyBroadcaster(
                new AblyRest(config('broadcasting.connections.ably.key'))
            );
        });
    }

}
