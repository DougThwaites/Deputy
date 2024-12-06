<?php

namespace Deputy\Traits;

use Saloon\Http\Response;
use Saloon\Http\PendingRequest;

trait HasRateCache
{
    public function bootHasRateCache(PendingRequest $pendingRequest): void
    {
        $pendingRequest->middleware()->onResponse(function (Response $response): void {

            // Skip responses that weren't cacheable
            if (!method_exists($response, 'isCached')) return;

            // Skip if the response was not from the cache
            if (!$response->isCached()) return;

            // Get the rate limit store
            $store = $this->rateLimitStore();

            // Decrement limits
            foreach ($this->getLimits() as $limit) {

                // Skip the limiter response
                if ($limit->usesResponse()) continue;

                // Set rate limit store
                $limit->update($store);

                // Decrement the limit
                $limit->hit(-1);
                $limit->save($store);

            }

        });
    }
}
