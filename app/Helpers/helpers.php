<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Lê uma configuração da loja: setting('store_name'), setting('store_open').
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($key, $default);
    }
}
