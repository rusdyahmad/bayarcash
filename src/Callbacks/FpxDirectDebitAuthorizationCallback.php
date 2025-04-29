<?php

declare(strict_types=1);

namespace Bayarcash\Callbacks;

use Bayarcash\Exceptions\InvalidCallbackException;

/**
 * FPX Direct Debit Authorization Callback Handler
 */
class FpxDirectDebitAuthorizationCallback
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
     * Mandate ID
     */
    private string $mandateId;
    
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
    private string $status;
    
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
     * Create a new FPX Direct Debit authorization callback instance
     *
     * @param array $data
     * @throws InvalidCallbackException
     */
    public function __construct(array $data)
    {
        $this->validateCallback($data);
        
        $this->recordType = $data['record_type'] ?? '';
        $this->transactionId = $data['transaction_id'] ?? '';
        $this->mandateId = $data['mandate_id'] ?? '';
        $this->exchangeReferenceNumber = $data['exchange_reference_number'] ?? '';
        $this->exchangeTransactionId = $data['exchange_transaction_id'] ?? '';
        $this->orderNumber = $data['order_number'] ?? '';
        $this->currency = $data['currency'] ?? '';
        $this->amount = $data['amount'] ?? '';
        $this->payerName = $data['payer_name'] ?? '';
        $this->payerEmail = $data['payer_email'] ?? '';
        $this->payerBankName = $data['payer_bank_name'] ?? '';
        $this->status = $data['status'] ?? '';
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
        if (!isset($data['record_type']) || $data['record_type'] !== 'authorization') {
            throw new InvalidCallbackException("Invalid record type for FPX Direct Debit authorization callback");
        }
        
        $requiredFields = [
            'transaction_id',
            'mandate_id',
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
        $payloadData = [
            'record_type' => $this->recordType,
            'transaction_id' => $this->transactionId,
            'mandate_id' => $this->mandateId,
            'exchange_reference_number' => $this->exchangeReferenceNumber,
            'exchange_transaction_id' => $this->exchangeTransactionId,
            'order_number' => $this->orderNumber,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'payer_name' => $this->payerName,
            'payer_email' => $this->payerEmail,
            'payer_bank_name' => $this->payerBankName,
            'status' => $this->status,
            'status_description' => $this->statusDescription,
            'datetime' => $this->datetime,
        ];
        
        // Sort the payload data by key
        ksort($payloadData);
        
        // Concatenate values with '|'
        $payloadString = implode('|', $payloadData);
        
        // Validate checksum
        return hash_hmac('sha256', $payloadString, $apiSecretKey) === $this->checksum;
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
     * Get mandate ID
     *
     * @return string
     */
    public function getMandateId(): string
    {
        return $this->mandateId;
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
     * @return string
     */
    public function getStatus(): string
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
