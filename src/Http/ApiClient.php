<?php

declare(strict_types=1);

namespace Bayarcash\Http;

use Bayarcash\Contracts\ConfigurationInterface;
use Bayarcash\Exceptions\ApiException;
use Bayarcash\Exceptions\NotFoundException;
use Bayarcash\Resources\Bank;
use Bayarcash\Resources\DuitNowBank;
use Bayarcash\Resources\Mandate;
use Bayarcash\Resources\MandateTransaction;
use Bayarcash\Resources\PaymentIntent;
use Bayarcash\Resources\Portal;
use Bayarcash\Resources\PortalCollection;
use Bayarcash\Resources\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * API Client for Bayarcash
 */
class ApiClient
{
    /**
     * Guzzle HTTP client
     */
    private Client $client;
    
    /**
     * Configuration
     */
    private ConfigurationInterface $config;
    
    /**
     * Logger instance
     */
    private LoggerInterface $logger;
    
    /**
     * Create a new API client
     *
     * @param ConfigurationInterface $config
     * @param LoggerInterface|null $logger
     */
    public function __construct(ConfigurationInterface $config, ?LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->logger = $logger ?? new NullLogger();
        $this->initialize();
    }
    
    /**
     * Initialize the HTTP client
     *
     * @return void
     */
    private function initialize(): void
    {
        $options = [
            'base_uri' => $this->config->getBaseUri(),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->config->getPat(),
            ],
            'http_errors' => false,
        ];
        
        if ($this->config->isDebug()) {
            $options['debug'] = true;
        }
        
        $this->client = new Client($options);
    }
    
    /**
     * Reinitialize the client (after config changes)
     *
     * @return void
     */
    public function reinitialize(): void
    {
        $this->initialize();
    }
    
    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     * @return self
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }
    
    /**
     * Make a request to the API
     *
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array $options Request options
     * @return array
     * 
     * @throws ApiException If the API returns an error
     * @throws NotFoundException If the resource is not found
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        $this->logger->debug('Making API request', [
            'method' => $method,
            'endpoint' => $endpoint,
            'options' => $options,
        ]);
        
        try {
            $response = $this->client->request($method, $endpoint, $options);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            
            if ($statusCode >= 400) {
                $this->handleErrorResponse($statusCode, $data);
            }
            
            return $data;
        } catch (ClientException $e) {
            $this->logger->error('Client error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new NotFoundException('Resource not found', 404, $e);
            }
            
            throw new ApiException(
                $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $e
            );
        } catch (ServerException $e) {
            $this->logger->error('Server error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            
            throw new ApiException(
                'Server error: ' . $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $e
            );
        } catch (GuzzleException $e) {
            $this->logger->error('Request error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            
            throw new ApiException(
                'Request error: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    
    /**
     * Handle error response
     *
     * @param int $statusCode
     * @param array|null $data
     * @return void
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    private function handleErrorResponse(int $statusCode, ?array $data): void
    {
        $message = $data['message'] ?? 'Unknown error';
        
        if ($statusCode === 404) {
            throw new NotFoundException($message, $statusCode);
        }
        
        throw new ApiException($message, $statusCode);
    }
    
    /**
     * Get transaction details
     *
     * @param string $transactionId
     * @return Transaction
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    public function getTransaction(string $transactionId): Transaction
    {
        $response = $this->request('GET', "transactions/{$transactionId}");
        return new Transaction($response['data'] ?? []);
    }
    
    /**
     * Get transaction by order number
     *
     * @param string $orderNumber
     * @return Transaction|null
     * 
     * @throws ApiException
     */
    public function getTransactionByOrderNumber(string $orderNumber): ?Transaction
    {
        try {
            $response = $this->request('GET', "transactions/order/{$orderNumber}");
            return new Transaction($response['data'] ?? []);
        } catch (NotFoundException $e) {
            return null;
        }
    }
    
    /**
     * Create payment intent
     *
     * @param array $data
     * @return PaymentIntent
     * 
     * @throws ApiException
     */
    public function createPaymentIntent(array $data): PaymentIntent
    {
        $response = $this->request('POST', 'payment-intents', [
            'json' => $data,
        ]);
        
        // The API response doesn't have a 'data' key, it returns the payment intent directly
        return new PaymentIntent($response ?? []);
    }
    
    /**
     * Get list of banks
     *
     * @return Bank[]
     * 
     * @throws ApiException
     */
    public function getBanks(): array
    {
        $response = $this->request('GET', 'banks');
        
        $banks = [];
        foreach ($response as $bankData) {
            $banks[] = new Bank($bankData);
        }
        
        return $banks;
    }
    
    /**
     * Get list of DuitNow DOBW banks
     *
     * @return DuitNowBank[]
     * 
     * @throws ApiException
     */
    public function getDuitNowDobwBanks(): array
    {
        $response = $this->request('GET', 'duitnow/dobw/banks');
        
        $banks = [];
        foreach ($response as $bankData) {
            $banks[] = new DuitNowBank($bankData);
        }
        
        return $banks;
    }
    
    /**
     * Get list of portals
     *
     * @param int $page Page number for pagination
     * @return PortalCollection
     * 
     * @throws ApiException
     */
    public function getPortals(int $page = 1): PortalCollection
    {
        $response = $this->request('GET', 'portals', [
            'query' => [
                'page' => $page,
            ],
        ]);
        
        return new PortalCollection($response);
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
     * @throws ApiException
     */
    public function createPortal(
        string $portalName,
        ?string $url = null,
        ?string $transactionNotificationEmail = null,
        ?string $customPaymentButtonText = null,
        array $paymentChannelIds = []
    ): Portal {
        $data = [
            'portal_name' => $portalName,
        ];
        
        if ($url !== null) {
            $data['url'] = $url;
        }
        
        if ($transactionNotificationEmail !== null) {
            $data['transaction_notification_email'] = $transactionNotificationEmail;
        }
        
        if ($customPaymentButtonText !== null) {
            $data['custom_payment_button_text'] = $customPaymentButtonText;
        }
        
        if (!empty($paymentChannelIds)) {
            $data['payment_channel_ids'] = $paymentChannelIds;
        }
        
        $response = $this->request('POST', 'portals', [
            'json' => $data,
        ]);
        
        // The API response structure might be different for creation
        // Assuming it returns the created portal directly or within a data property
        $portalData = $response['data'] ?? $response;
        
        return new Portal($portalData);
    }
    
    /**
     * Get mandate details
     *
     * @param string $mandateId The mandate ID
     * @return Mandate
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    public function getMandate(string $mandateId): Mandate
    {
        $response = $this->request('GET', "mandates/{$mandateId}");
        
        return new Mandate($response);
    }
    
    /**
     * Update mandate details
     *
     * @param string $mandateId The mandate ID
     * @param array $data The mandate data to update
     * @return Mandate
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    public function updateMandate(string $mandateId, array $data): Mandate
    {
        $response = $this->request('PUT', "mandates/{$mandateId}", [
            'json' => $data,
        ]);
        
        return new Mandate($response);
    }
    
    /**
     * Terminate a mandate
     *
     * @param string $mandateId The mandate ID
     * @param array $data The termination data
     * @return Mandate
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    public function terminateMandate(string $mandateId, array $data): Mandate
    {
        $response = $this->request('DELETE', "mandates/{$mandateId}", [
            'json' => $data,
        ]);
        
        return new Mandate($response);
    }
    
    /**
     * Get mandate transaction details
     *
     * @param string $transactionId The transaction ID
     * @return MandateTransaction
     * 
     * @throws ApiException
     * @throws NotFoundException
     */
    public function getMandateTransaction(string $transactionId): MandateTransaction
    {
        $response = $this->request('GET', "mandates/transactions/{$transactionId}");
        
        return new MandateTransaction($response);
    }
    
    /**
     * Create a direct debit mandate
     *
     * @param array $data
     * @return \Bayarcash\Resources\Mandate
     * 
     * @throws ApiException
     */
    public function createDirectDebitMandate(array $data): \Bayarcash\Resources\Mandate
    {
        // Use the same endpoint as payment intents but with direct debit specific data
        $response = $this->request('POST', 'payment-intents', [
            'json' => $data,
        ]);
        
        // The API response is returned directly
        return new \Bayarcash\Resources\Mandate($response ?? []);
    }
}
