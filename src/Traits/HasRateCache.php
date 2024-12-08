<?php

namespace Deputy\Traits;

use Illuminate\Support\Carbon;
use Saloon\Http\Response;
use Saloon\Http\PendingRequest;

trait HasRateCache
{
    public function bootHasRateCache(PendingRequest $pendingRequest): void
    {
        $pendingRequest->middleware()->onResponse(function (Response $response): void {

            // Fallback to default handling of 429 responses
            if($response->status() === 429) return;

            // Skip responses that weren't cacheable
            if (!method_exists($response, 'isCached')) return;

            // Skip if the response was not from the cache
            if (!$response->isCached()) return;

            // Get the rate limit store
            $store = $this->rateLimitStore();

            // Decrement limits
            foreach ($this->getLimits() as $limit) {

                // Set rate limit store
                $limit->update($store);

                // Reset too many attempts limit timer or decrement all other limits
                $limit->usesResponse()
                    ? $limit->resetLimit()
                    : $limit->hit(-1);

                $limit->save($store);

            }

        });
    }
}
