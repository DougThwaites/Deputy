# Deputy
Deputy is a [Saloon](saloon) plugin to help with the combination of the official Saloon [Caching](https://docs.saloon.dev/installable-plugins/caching-responses) and [Rate Limit](https://docs.saloon.dev/installable-plugins/handling-rate-limits) plugins.

Both the official Caching and Rate Limit plugins on there own work beautifully and as expected however when combined responses that have been cached will also increment the rate limiter meaning you could only be hitting the cache and throwing a rate limit exception.

This plugin does not override in anyway either of these plugins it works by registering a response middleware that if the request was a cached response it will then decrement the rate limiter by one negating the affects of adding cached responses to the rate limiter.

#### Installation
    composer require douglasthwaites/deputy

#### Usage
To use this package add the traits and methods for Rate Limiting and Caching as per the official Saloon docs and then also add the HasRateCache trait to your connector or request.

    <?php  
      
    namespace App\Http\Integrations\CoolIntegration;  

    use Saloon\Http\Connector;  
    use Deputy\Traits\HasRateCache;
    use Saloon\CachePlugin\Traits\HasCaching;
    use Saloon\RateLimitPlugin\Traits\HasRateLimits;  
      
    class CoolApiConnector extends Connector  
    {  
        use HasRateLimits;
        use HasCaching;    
        use HasRateCache;
    }

That's it 🤠

Thanks to [Sam Carré](https://github.com/Sammyjo20) and everyone who has [contributed](https://github.com/saloonphp/saloon/graphs/contributors) to [Saloon](https://github.com/saloonphp/saloon) ❤️
