<?php

namespace App\Providers;

use App\Http\Controllers\PostbackController;
use App\Http\Controllers\Statistics\LinkHitsController;
use App\Http\Controllers\Statistics\LinkTypeHitsController;
use App\Http\Controllers\User\GetUserJourneyController;

use App\Http\Controllers\User\GetUsersSimilarJourneyController;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;


class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected Config $config;

    public function map(): void {
        $this->group(["prefix" => "/api"], function () {
            $this->group(["prefix" => "/statistics"], function () {
                $this->get("/link-hits", LinkHitsController::class);
                $this->get("/link-type-hits", LinkTypeHitsController::class);
            });

            $this->group(["prefix" => "/user"], function() {
                $this->group(["prefix" => "/journey"], function () {
                    $this->get("/{id}", GetUserJourneyController::class);
                    $this->get("/similar/{id}", GetUsersSimilarJourneyController::class);
                });
                
                $this->post("/postback", PostbackController::class);
            });
        });
    }

    public function boot(): void
    {
        $this->config = $this->app->get(Config::class);

        parent::boot();
    }
}
