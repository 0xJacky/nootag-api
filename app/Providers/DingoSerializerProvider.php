<?php

namespace App\Providers;

use League\Fractal\Manager;
use App\Serializers\NoDataArraySerializer;
use Illuminate\Support\ServiceProvider;

class DingoSerializerProvider extends ServiceProvider
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
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new Manager();
            $fractal->setSerializer(new NoDataArraySerializer);
            return new \Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });
    }
}
