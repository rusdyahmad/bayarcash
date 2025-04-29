<?php

declare(strict_types=1);

namespace Bayarcash;

use Bayarcash\Channels\ChannelFactory;
use Bayarcash\Contracts\ChannelInterface;
use Bayarcash\Contracts\ConfigurationInterface;
use Bayarcash\Exceptions\InvalidChannelException;
use Bayarcash\Exceptions\InvalidConfigurationException;
use Bayarcash\Exceptions\ValidationException;
use Bayarcash\Http\ApiClient;
use Bayarcash\Resources\Bank;
use Bayarcash\Resources\DuitNowBank;
use Bayarcash\Resources\Mandate;
use Bayarcash\Resources\MandateTransaction;
use Bayarcash\Resources\PaymentIntent;
use Bayarcash\Resources\Portal;
use Bayarcash\Resources\PortalCollection;
use Bayarcash\Resources\Transaction;
use Bayarcash\Support\Configuration;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Bayarcash Payment Gateway Client
 */
class Bayarcash
{
    /**
     * Payment channel constants
     */
    public const CHANNEL_FPX = 1;
    public const CHANNEL_FPX_DIRECT_DEBIT = 5;
    public const CHANNEL_FPX_LINE_OF_CREDIT = 4;
    public const CHANNEL_DUITNOW_DOBW = 6;
    public const CHANNEL_DUITNOW_QR = 7;
    public const CHANNEL_SPAYLATER = 8;
    public const CHANNEL_BOOST_PAYFLEX = 9;
    public const CHANNEL_QRISOB = 10;
    public const CHANNEL_QRISWALLET = 11;
    public const CHANNEL_NETS = 12;

    /**
     * API client instance
     */
    private ApiClient $apiClient;

    /**
     * Configuration
     */
    private ConfigurationInterface $config;

    /**
     * Channel factory
     */
    private ChannelFactory $channelFactory;

    /**
     * Logger instance
     */
    private LoggerInterface $logger;

    /**
     * Create a new Bayarcash client instance
     *
     * @param string $pat Personal Access Token (PAT)
     * @param string $apiSecretKey API secret key for checksum generation
     * @param string $portalKey Portal key
     * @param array $options Additional options
     * @param LoggerInterface|null $logger Logger instance
     * 
     * @throws InvalidConfigurationException If the configuration is invalid
     */
    public function __construct(
        string $pat,
        string $apiSecretKey,
        string $portalKey,
        array $options = [],
        ?LoggerInterface $logger = null
    ) {
        $this->logger = $logger ?? new NullLogger();
        
        // Create configuration
        $this->config = new Configuration(
            pat: $pat,
            apiSecretKey: $apiSecretKey,
            portalKey: $portalKey,
            options: $options
        );
        
        // Initialize API client
        $this->apiClient = new ApiClient($this->config, $this->logger);
        
        // Initialize channel factory
        $this->channelFactory = new ChannelFactory($this->apiClient, $this->config);
    }

    /**
     * Create a payment intent
     *
     * @param array $data Payment data
     * @return PaymentIntent
     * 
     * @throws InvalidChannelException If the payment channel is invalid
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\ValidationException If the payment data is invalid
     */
    public function createPaymentIntent(array $data): PaymentIntent
    {
        // Validate required fields
        $requiredFields = ['payment_channel', 'order_number', 'amount', 'payer_name', 'payer_email'];
        $this->validateRequiredFields($data, $requiredFields);
        
        // Get the appropriate channel handler
        $channel = $this->getChannel($data['payment_channel']);
        
        // Create payment intent using the channel
        return $channel->createPaymentIntent($data);
    }

    /**
     * Get transaction details
     *
     * @param string $transactionId Transaction ID
     * @return Transaction
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\NotFoundException If the transaction is not found
     */
    public function getTransaction(string $transactionId): Transaction
    {
        return $this->apiClient->getTransaction($transactionId);
    }

    /**
     * Get transaction by order number
     *
     * @param string $orderNumber Order number
     * @return Transaction|null
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function getTransactionByOrderNumber(string $orderNumber): ?Transaction
    {
        return $this->apiClient->getTransactionByOrderNumber($orderNumber);
    }

    /**
     * Verify callback data
     *
     * @param array $callbackData Callback data
     * @return bool
     */
    public function verifyCallback(array $callbackData): bool
    {
        if (!isset($callbackData['checksum'])) {
            return false;
        }

        $receivedChecksum = $callbackData['checksum'];
        
        // Remove checksum from callback data for verification
        $verificationData = $callbackData;
        unset($verificationData['checksum']);
        
        // Generate checksum for verification
        $calculatedChecksum = $this->generateChecksum($verificationData);
        
        // Compare checksums
        return hash_equals($receivedChecksum, $calculatedChecksum);
    }

    /**
     * Generate checksum for data
     *
     * @param array $data Data to generate checksum for
     * @return string
     */
    public function generateChecksum(array $data): string
    {
        // Sort data by key
        $sortedData = $data;
        ksort($sortedData);
        
        // Convert to string
        $dataString = implode('|', $sortedData);
        
        // Generate HMAC SHA256 checksum
        return hash_hmac('sha256', $dataString, $this->config->getApiSecretKey());
    }

    /**
     * Get a payment channel handler
     *
     * @param int $channelId Channel ID
     * @return ChannelInterface
     * 
     * @throws InvalidChannelException If the channel is not supported
     */
    public function getChannel(int $channelId): ChannelInterface
    {
        return $this->channelFactory->create($channelId);
    }

    /**
     * Get the API client
     *
     * @return ApiClient
     */
    public function getApiClient(): ApiClient
    {
        return $this->apiClient;
    }

    /**
     * Get the configuration
     *
     * @return ConfigurationInterface
     */
    public function getConfig(): ConfigurationInterface
    {
        return $this->config;
    }

    /**
     * Set sandbox mode
     *
     * @param bool $sandbox Whether to use sandbox mode
     * @return self
     */
    public function setSandbox(bool $sandbox = true): self
    {
        $this->config->setSandbox($sandbox);
        $this->apiClient->reinitialize();
        return $this;
    }

    /**
     * Set API version
     *
     * @param string $version API version
     * @return self
     */
    public function setApiVersion(string $version): self
    {
        $this->config->setApiVersion($version);
        $this->apiClient->reinitialize();
        return $this;
    }

    /**
     * Set debug mode
     *
     * @param bool $debug Whether to enable debug mode
     * @return self
     */
    public function setDebug(bool $debug = true): self
    {
        $this->config->setDebug($debug);
        return $this;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger Logger instance
     * @return self
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        $this->apiClient->setLogger($logger);
        return $this;
    }

    /**
     * Validate required fields
     *
     * @param array $data Data to validate
     * @param array $requiredFields Required fields
     * @return void
     * 
     * @throws \Bayarcash\Exceptions\ValidationException If required fields are missing
     */
    private function validateRequiredFields(array $data, array $requiredFields): void
    {
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            throw new \Bayarcash\Exceptions\ValidationException(
                'Missing required fields: ' . implode(', ', $missingFields)
            );
        }
    }

    /**
     * Get list of banks
     *
     * @return Bank[]
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function getBanks(): array
    {
        return $this->apiClient->getBanks();
    }
    
    /**
     * Get list of DuitNow DOBW banks
     *
     * @return DuitNowBank[]
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function getDuitNowDobwBanks(): array
    {
        return $this->apiClient->getDuitNowDobwBanks();
    }

    /**
     * Get list of portals
     *
     * @param int $page Page number for pagination
     * @return PortalCollection
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function getPortals(int $page = 1): PortalCollection
    {
        return $this->apiClient->getPortals($page);
    }

    /**
     * Create a new portal
     *
     * @param string $portalName The name of the portal
     * @param string|null $url The URL of the portal (optional)
     * @param string|null $transactionNotificationEmail Email for transaction notifications (optional)
     * @param string|null $customPaymentButtonText Custom text for payment button (optional)
     * @param array $paymentChannelIds Array of payment channel IDs to enable (optional)
     * @return Portal
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function createPortal(
        string $portalName,
        ?string $url = null,
        ?string $transactionNotificationEmail = null,
        ?string $customPaymentButtonText = null,
        array $paymentChannelIds = []
    ): Portal {
        return $this->apiClient->createPortal(
            $portalName,
            $url,
            $transactionNotificationEmail,
            $customPaymentButtonText,
            $paymentChannelIds
        );
    }

    /**
     * Get mandate details
     *
     * @param string $mandateId The mandate ID
     * @return Mandate
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\NotFoundException If the mandate is not found
     */
    public function getMandate(string $mandateId): Mandate
    {
        return $this->apiClient->getMandate($mandateId);
    }

    /**
     * Update mandate details
     *
     * @param string $mandateId The mandate ID
     * @param array $data The mandate data to update
     * @return Mandate
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\NotFoundException If the mandate is not found
     */
    public function updateMandate(string $mandateId, array $data): Mandate
    {
        return $this->apiClient->updateMandate($mandateId, $data);
    }

    /**
     * Terminate a mandate
     *
     * @param string $mandateId The mandate ID
     * @param array $data The termination data
     * @return Mandate
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\NotFoundException If the mandate is not found
     */
    public function terminateMandate(string $mandateId, array $data): Mandate
    {
        return $this->apiClient->terminateMandate($mandateId, $data);
    }

    /**
     * Get mandate transaction details
     *
     * @param string $transactionId The transaction ID
     * @return MandateTransaction
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\NotFoundException If the transaction is not found
     */
    public function getMandateTransaction(string $transactionId): MandateTransaction
    {
        return $this->apiClient->getMandateTransaction($transactionId);
    }

    /**
     * Create a direct debit mandate
     *
     * @param array $data Mandate data
     * @return \Bayarcash\Resources\Mandate
     * 
     * @throws InvalidChannelException If the payment channel is invalid
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     * @throws \Bayarcash\Exceptions\ValidationException If the mandate data is invalid
     */
    public function createDirectDebitMandate(array $data): \Bayarcash\Resources\Mandate
    {
        // Validate required fields
        $requiredFields = [
            'payment_channel', 'portal_key', 'payer_name', 'payer_id_type', 'payer_id',
            'payer_email', 'order_number', 'amount', 'application_type', 'application_reason',
            'frequency_mode', 'effective_date', 'expiry_date'
        ];
        $this->validateRequiredFields($data, $requiredFields);
        
        // Get the appropriate channel handler (FPX Direct Debit)
        $channelId = $data['payment_channel'] ?? self::CHANNEL_FPX_DIRECT_DEBIT;
        $channel = $this->getChannel($channelId);
        
        // Create direct debit mandate using the channel
        // The channel will throw an InvalidChannelException if it doesn't support direct debit
        return $channel->createDirectDebitMandate($data);
    }
}
