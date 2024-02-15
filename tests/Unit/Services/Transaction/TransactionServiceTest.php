<?php

namespace Tests\Unit\Services\Transaction;

use App\Contracts\Repositories\ITransactionRepository;
use App\Contracts\Services\IPaymentsService;
use App\DTO\Payment\CreatePaymentDTO;
use App\DTO\Payment\PaymentItemDTO;
use App\Models\User;
use App\Services\Payments\Providers\PaymentSession;
use App\Services\Transaction\TransactionService;
use Brick\Money\Money;
use Mockery;
use Spatie\LaravelData\DataCollection;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    // Example of a unit test for Service, not fully completed, just showing architecture of correct test
    public function testCreatePayment()
    {
        // Arrange
        $transactionRepository = Mockery::mock(ITransactionRepository::class);
        $paymentService = Mockery::mock(IPaymentsService::class);
        $user = new User();
        $paymentItem = new PaymentItemDTO('name',   Money::of(100, "USD"), 'description', 1);
        $items = new DataCollection(PaymentItemDTO::class, [$paymentItem]);
        $data = new CreatePaymentDTO('uuid', 'title', 'currency', 'callback_url', 'session_code', 'provider', 'customer_email', $items);

        return true;
        // All this dependencies should be mocked
        $transactionRepository->shouldReceive('create')->andReturn();
        $transactionRepository->shouldReceive('changeState')->andReturnSelf();
        $transactionRepository->shouldReceive('save')->andReturnSelf();
        $transactionRepository->shouldReceive('processState')->andReturnSelf();

        $paymentService->shouldReceive('createPayment')->andReturn(new PaymentSession('id', 'url', 'provider'));

        $service = new TransactionService($transactionRepository, $paymentService);

        // Act
        $result = $service->createPayment($data, $user);

        // Assert
        $this->assertInstanceOf(PaymentSession::class, $result);
    }



}
