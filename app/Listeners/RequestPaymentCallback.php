<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RequestPaymentCallback
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentProcessed $event): void
    {
        // Sending callback request just a stub for now
        // $client = new Client();
        // $response = $client->request('GET',  $event->getCallback()->generateLink());
    }
}
