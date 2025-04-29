<?php

declare(strict_types=1);

namespace Bayarcash\Callbacks;

use Bayarcash\Exceptions\InvalidCallbackException;

/**
 * Transaction Callback Handler
 */
class TransactionCallback
{
    /**
     * Record type
     */
    private string $recordType;
    
    /**
     * Transaction ID
     */
    private string $transactionId;
    
    /**
     * Exchange reference number
     */
    private string $exchangeReferenceNumber;
    
    /**
     * Exchange transaction ID
     */
    private string $exchangeTransactionId;
    
    /**
     * Order number
     */
    private string $orderNumber;
    
    /**
     * Currency
     */
    private string $currency;
    
    /**
     * Amount
     */
    private string $amount;
    
    /**
     * Payer name
     */
    private string $payerName;
    
    /**
     * Payer email
     */
    private string $payerEmail;
    
    /**
     * Payer bank name
     */
    private string $payerBankName;
    
    /**
     * Status
     */
    private int $status;
    
    /**
     * Status description
     */
    private string $statusDescription;
    
    /**
     * Datetime
     */
    private string $datetime;
    
    /**
     * Checksum
     */
    private string $checksum;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new transaction callback instance
     *
     * @param array $data
     * @throws InvalidCallbackException
     */
    public function __construct(array $data)
    {
        $this->validateCallback($data);
        
        $this->recordType = $data['record_type'] ?? '';
        $this->transactionId = $data['transaction_id'] ?? '';
        $this->exchangeReferenceNumber = $data['exchange_reference_number'] ?? '';
        $this->exchangeTransactionId = $data['exchange_transaction_id'] ?? '';
        $this->orderNumber = $data['order_number'] ?? '';
        $this->currency = $data['currency'] ?? '';
        $this->amount = $data['amount'] ?? '';
        $this->payerName = $data['payer_name'] ?? '';
        $this->payerEmail = $data['payer_email'] ?? '';
        $this->payerBankName = $data['payer_bank_name'] ?? '';
        $this->status = (int) ($data['status'] ?? 0);
        $this->statusDescription = $data['status_description'] ?? '';
        $this->datetime = $data['datetime'] ?? '';
        $this->checksum = $data['checksum'] ?? '';
        $this->data = $data;
    }
    
    /**
     * Validate callback data
     *
     * @param array $data
     * @throws InvalidCallbackException
     */
    private function validateCallback(array $data): void
    {
        $requiredFields = [
            'transaction_id',
            'order_number',
            'amount',
            'status',
            'checksum'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new InvalidCallbackException("Missing required field: {$field}");
            }
        }
    }
    
    /**
     * Create from request data
     *
     * @param array $requestData
     * @return self
     */
    public static function fromRequest(array $requestData): self
    {
        return new self($requestData);
    }
    
    /**
     * Verify callback checksum
     *
     * @param string $apiSecretKey The API secret key
     * @return bool
     */
    public function verifyChecksum(string $apiSecretKey): bool
    {
        // For transaction callback (callback_url)
        if ($this->recordType === 'transaction') {
            $payloadData = [
                'record_type' => $this->recordType,
                'transaction_id' => $this->transactionId,
                'exchange_reference_number' => $this->exchangeReferenceNumber,
                'exchange_transaction_id' => $this->exchangeTransactionId,
                'order_number' => $this->orderNumber,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'payer_name' => $this->payerName,
                'payer_email' => $this->payerEmail,
                'payer_bank_name' => $this->payerBankName,
                'status' => (string) $this->status,
                'status_description' => $this->statusDescription,
                'datetime' => $this->datetime,
            ];
        } else {
            // For transaction receipt (return_url)
            $payloadData = [
                'transaction_id' => $this->transactionId,
                'exchange_reference_number' => $this->exchangeReferenceNumber,
                'exchange_transaction_id' => $this->exchangeTransactionId,
                'order_number' => $this->orderNumber,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'payer_bank_name' => $this->payerBankName,
                'status' => (string) $this->status,
                'status_description' => $this->statusDescription,
            ];
        }
        
        // Sort the payload data by key
        ksort($payloadData);
        
        // Concatenate values with '|'
        $payloadString = implode('|', $payloadData);
        
        // Validate checksum
        return hash_hmac('sha256', $payloadString, $apiSecretKey) === $this->checksum;
    }
    
    /**
     * Check if transaction is new
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === 0;
    }
    
    /**
     * Check if transaction is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 1;
    }
    
    /**
     * Check if transaction failed
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 2;
    }
    
    /**
     * Check if transaction is successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->status === 3;
    }
    
    /**
     * Check if transaction is cancelled
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === 4;
    }
    
    /**
     * Get record type
     *
     * @return string
     */
    public function getRecordType(): string
    {
        return $this->recordType;
    }
    
    /**
     * Get transaction ID
     *
     * @return string
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
    
    /**
     * Get exchange reference number
     *
     * @return string
     */
    public function getExchangeReferenceNumber(): string
    {
        return $this->exchangeReferenceNumber;
    }
    
    /**
     * Get exchange transaction ID
     *
     * @return string
     */
    public function getExchangeTransactionId(): string
    {
        return $this->exchangeTransactionId;
    }
    
    /**
     * Get order number
     *
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }
    
    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
    
    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }
    
    /**
     * Get payer name
     *
     * @return string
     */
    public function getPayerName(): string
    {
        return $this->payerName;
    }
    
    /**
     * Get payer email
     *
     * @return string
     */
    public function getPayerEmail(): string
    {
        return $this->payerEmail;
    }
    
    /**
     * Get payer bank name
     *
     * @return string
     */
    public function getPayerBankName(): string
    {
        return $this->payerBankName;
    }
    
    /**
     * Get status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
    
    /**
     * Get status description
     *
     * @return string
     */
    public function getStatusDescription(): string
    {
        return $this->statusDescription;
    }
    
    /**
     * Get datetime
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }
    
    /**
     * Get checksum
     *
     * @return string
     */
    public function getChecksum(): string
    {
        return $this->checksum;
    }
    
    /**
     * Get raw data
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
