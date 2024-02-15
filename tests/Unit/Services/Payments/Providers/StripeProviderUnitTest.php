<?php

namespace Tests\Unit\Services\Payments\Providers;

use App\Services\DataModels\PaymentDataItemModel;
use App\Services\DataModels\PaymentDataModel;
use App\Services\Payments\Providers\StripePaymentProvider;
use Brick\Money\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\LaravelData\DataCollection;
use Stripe\StripeClient;
use Tests\TestCase;

class StripeProviderUnitTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Very simplified setup for testing stripe client
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $sessionsMock = \Mockery::mock();
        $sessionsMock->shouldReceive('create')
            ->andReturn((object) ['id' => 'stripe_test_id', 'url' => 'stripe_test_url']);

        $checkoutMock = \Mockery::mock();
        $checkoutMock->sessions = $sessionsMock;

        $stripeMock = \Mockery::mock(StripeClient::class);

        $stripeMock->shouldReceive('getService')->andReturn($checkoutMock);

        $stripeMock->shouldReceive('checkout')
            ->andReturn($checkoutMock);

        $this->app->instance(StripeClient::class, $stripeMock);

        $this->beforeApplicationDestroyed(function () use ($stripeMock) {
            \Mockery::close();
            $this->app->instance(StripeClient::class, new StripeClient('test_key'));
        });
    }

    public function testCreatePaymentSession()
    {
        $provider = new StripePaymentProvider('session_secret', 'session_test_id');

        $item = new PaymentDataItemModel('test_item',
            'test_description',
            1,
            Money::of(100, "USD"),
        );

        $dataModel = new PaymentDataModel('test payment',
            'test_email',
            new DataCollection(PaymentDataItemModel::class, [$item]),
            'USD',
        );

        $session = $provider->createPaymentSession($dataModel);
        $this->assertNotNull($session);
        $this->assertEquals('stripe_test_id', $session->getSessionId());
        $this->assertEquals('stripe_test_url', $session->getPaymentUrl());
        $this->assertEquals('session_test_id', $session->getPaymentID());
        $this->assertEquals('session_secret', $session->getPaymentSecret());
    }
}
