<?php

namespace App\Http\Controllers\PaymentProviders;

use App\Services\PaymentProviders\WebhookExecutorInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppleController
{
    public function __construct(protected WebhookExecutorInterface $webhookExecutor) {}
    /**
     * @param Request $request
     * @return Response
     */
    public function handleHook(Request $request): Response
    {
        if ($this->webhookExecutor->handleHook($request)) {
            return new Response(['success' => true, 'error' => '']);
        } else {
            return new Response(['success' => false, 'error' => 'Something went wrong'], Response::HTTP_BAD_REQUEST);
        }
    }
}
