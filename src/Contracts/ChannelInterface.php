<?php

declare(strict_types=1);

namespace Bayarcash\Contracts;

use Bayarcash\Resources\PaymentIntent;
use Bayarcash\Resources\Mandate;

/**
 * Payment Channel Interface
 */
interface ChannelInterface
{
    /**
     * Create a payment intent
     *
     * @param array $data Payment data
     * @return PaymentIntent
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\ValidationException If the payment data is invalid
     */
    public function createPaymentIntent(array $data): PaymentIntent;
    
    /**
     * Create a direct debit mandate
     *
     * @param array $data Mandate data
     * @return Mandate
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\ValidationException If the mandate data is invalid
     */
    public function createDirectDebitMandate(array $data): Mandate;
    
    /**
     * Get channel ID
     *
     * @return int
     */
    public function getChannelId(): int;
    
    /**
     * Get channel name
     *
     * @return string
     */
    public function getChannelName(): string;
}
