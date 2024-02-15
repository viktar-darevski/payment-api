<?php

namespace App\Services\Transaction;

use App\Contracts\Repositories\ITransactionRepository;
use App\Contracts\Services\IPaymentsService;
use App\Contracts\Services\ITransactionService;
use App\DTO\Payment\CreatePaymentDTO;
use App\DTO\Payment\PaymentCallbackDTO;
use App\Models\User;
use App\Services\Payments\Providers\PaymentCallback;
use App\Services\Payments\Providers\PaymentSession;
use App\Services\Transaction\States\CompletedState;
use App\Services\Transaction\States\NewState;
use Illuminate\Support\Facades\Hash;

class TransactionService implements ITransactionService
{
    protected ITransactionRepository $transactionRepository;

    protected IPaymentsService $paymentService;


    public function __construct(ITransactionRepository $transactionRepository, IPaymentsService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->transactionRepository = $transactionRepository;
    }

    public function createPayment(CreatePaymentDTO $data, User $user) : PaymentSession
    {
        $transaction = $this->transactionRepository->create($data, $user);
        $transaction->changeState(NewState::class);
        $dataModel = $data->makeDataModel();
        $paymentSession = $this->paymentService->createPayment($dataModel, $data->getProvider());

        $transaction->provider_secret_code = $paymentSession->getPaymentSecret();
        $transaction->provider_session_id = $paymentSession->getPaymentID();
        $transaction->save();
        $transaction->processState();

        return $paymentSession;
    }


    /**
     * Get the url to redirect to after payment
     * @param PaymentCallbackDTO $data
     * @return PaymentCallback
     * @throws \Exception
     */
    public function processedPayment(PaymentCallbackDTO $data) : PaymentCallback {
        $transaction = $this->transactionRepository->findBySessionID($data->getSessionID());

        if (!Hash::check($data->getSessionSecret(), $transaction->provider_secret_code)) {
            throw new \Exception('Invalid session secret');
        }

        $transaction->processState();

        return new PaymentCallback($transaction->callback_url, $transaction->session_code, 'success' );
    }
}
