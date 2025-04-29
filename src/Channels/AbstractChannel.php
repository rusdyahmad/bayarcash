<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

use Bayarcash\Contracts\ChannelInterface;
use Bayarcash\Contracts\ConfigurationInterface;
use Bayarcash\Http\ApiClient;
use Bayarcash\Resources\PaymentIntent;
use Bayarcash\Resources\Mandate;

/**
 * Abstract Channel Implementation
 */
abstract class AbstractChannel implements ChannelInterface
{
    /**
     * API client
     */
    protected ApiClient $apiClient;
    
    /**
     * Configuration
     */
    protected ConfigurationInterface $config;
    
    /**
     * Channel ID
     */
    protected int $channelId;
    
    /**
     * Channel name
     */
    protected string $channelName;
    
    /**
     * Create a new channel instance
     *
     * @param ApiClient $apiClient
     * @param ConfigurationInterface $config
     */
    public function __construct(ApiClient $apiClient, ConfigurationInterface $config)
    {
        $this->apiClient = $apiClient;
        $this->config = $config;
    }
    
    /**
     * Create a payment intent
     *
     * @param array $data Payment data
     * @return PaymentIntent
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\ValidationException If the payment data is invalid
     */
    public function createPaymentIntent(array $data): PaymentIntent
    {
        // Ensure payment channel is set
        $data['payment_channel'] = $this->getChannelId();
        
        // Add additional data if needed
        $data = $this->preparePaymentData($data);
        
        // Create payment intent
        return $this->apiClient->createPaymentIntent($data);
    }
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        // Set default currency if not provided
        if (!isset($data['currency'])) {
            $data['currency'] = 'MYR';
        }
        
        // Set return URL if not provided
        if (!isset($data['return_url']) && $this->config->getOption('return_url')) {
            $data['return_url'] = $this->config->getOption('return_url');
        }
        
        // Set callback URL if not provided
        if (!isset($data['callback_url']) && $this->config->getOption('callback_url')) {
            $data['callback_url'] = $this->config->getOption('callback_url');
        }
        
        return $data;
    }
    
    /**
     * Get channel ID
     *
     * @return int
     */
    public function getChannelId(): int
    {
        return $this->channelId;
    }
    
    /**
     * Get channel name
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }
    
    /**
     * Create a direct debit mandate
     *
     * @param array $data Mandate data
     * @return \Bayarcash\Resources\Mandate
     * 
     * @throws \Bayarcash\Exceptions\InvalidChannelException This channel does not support direct debit
     */
    public function createDirectDebitMandate(array $data): \Bayarcash\Resources\Mandate
    {
        throw new \Bayarcash\Exceptions\InvalidChannelException(
            "Channel {$this->channelId} ({$this->channelName}) does not support direct debit mandates"
        );
    }
}
