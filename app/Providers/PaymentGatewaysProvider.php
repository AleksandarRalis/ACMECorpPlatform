<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\PaymentGatewayInterface;
use App\PaymentGateways\DummyPaymentGateway;

class PaymentGatewaysProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentGatewayInterface::class, DummyPaymentGateway::class);
    }
}