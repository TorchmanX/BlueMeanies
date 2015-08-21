<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param ResponseFactory $factory
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('error', function ($code, $msg) use ($factory) {
            return $factory->json(array(
                'success' => false,
                'code' => $code,
                'msg' => $msg
            ));
        });

        $factory->macro('success', function ($data = null) use ($factory) {
            if ($data === null){
                return $factory->json(array(
                    'success' => true
                ));
            }
            return $factory->json(array(
                'success' => true,
                'data' => $data
            ));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
