<?php

namespace Genesis\Contracts\Support;

interface ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void;
}
