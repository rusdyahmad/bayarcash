<?php

declare(strict_types=1);

namespace Bayarcash\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Bayarcash\Resources\PaymentIntent createPaymentIntent(array $data)
 * @method static \Bayarcash\Resources\Transaction getTransaction(string $transactionId)
 * @method static \Bayarcash\Resources\Transaction|null getTransactionByOrderNumber(string $orderNumber)
 * @method static bool verifyCallback(array $callbackData)
 * @method static string generateChecksum(array $data)
 * @method static \Bayarcash\Contracts\ChannelInterface getChannel(int $channelId)
 * @method static \Bayarcash\Http\ApiClient getApiClient()
 * @method static \Bayarcash\Contracts\ConfigurationInterface getConfig()
 * @method static \Bayarcash\Bayarcash setSandbox(bool $sandbox = true)
 * @method static \Bayarcash\Bayarcash setApiVersion(string $version)
 * @method static \Bayarcash\Bayarcash setDebug(bool $debug = true)
 * @method static \Bayarcash\Bayarcash setLogger(\Psr\Log\LoggerInterface $logger)
 * 
 * @see \Bayarcash\Bayarcash
 */
class Bayarcash extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'bayarcash';
    }
}
