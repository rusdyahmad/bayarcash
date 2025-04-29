<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

use Bayarcash\Contracts\ChannelInterface;
use Bayarcash\Contracts\ConfigurationInterface;
use Bayarcash\Exceptions\InvalidChannelException;
use Bayarcash\Http\ApiClient;

/**
 * Channel Factory
 */
class ChannelFactory
{
    /**
     * API client
     */
    private ApiClient $apiClient;
    
    /**
     * Configuration
     */
    private ConfigurationInterface $config;
    
    /**
     * Create a new channel factory
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
     * Create a channel instance
     *
     * @param int $channelId
     * @return ChannelInterface
     * 
     * @throws InvalidChannelException If the channel is not supported
     */
    public function create(int $channelId): ChannelInterface
    {
        return match ($channelId) {
            1 => new FpxChannel($this->apiClient, $this->config),
            5 => new FpxDirectDebitChannel($this->apiClient, $this->config),
            4 => new FpxLineOfCreditChannel($this->apiClient, $this->config),
            6 => new DuitnowDobwChannel($this->apiClient, $this->config),
            7 => new DuitnowQrChannel($this->apiClient, $this->config),
            8 => new SPayLaterChannel($this->apiClient, $this->config),
            9 => new BoostPayflexChannel($this->apiClient, $this->config),
            10 => new QrisObChannel($this->apiClient, $this->config),
            11 => new QrisWalletChannel($this->apiClient, $this->config),
            12 => new NetsChannel($this->apiClient, $this->config),
            default => throw new InvalidChannelException("Channel ID {$channelId} is not supported"),
        };
    }
}
