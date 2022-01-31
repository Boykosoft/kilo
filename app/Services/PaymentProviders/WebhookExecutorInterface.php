<?php

namespace App\Services\PaymentProviders;

use App\Models\SubscriptionUpdateRequest;
use Illuminate\Http\Request;

interface WebhookExecutorInterface
{
    public function handleHook(Request $request): bool; // bool on return is simplify, use object in case of complex response logic
    public function checkSignature(Request $request): bool;
    public function validateRequest(Request $request): bool;
    public function logRequest(Request $request): bool;
    public function extractData(Request $request): ?SubscriptionUpdateRequest;
    public function postprocessHook(Request $request): bool;

}
