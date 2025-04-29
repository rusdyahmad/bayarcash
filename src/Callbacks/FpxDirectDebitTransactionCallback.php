<?php

declare(strict_types=1);

namespace Bayarcash\Callbacks;

use Bayarcash\Exceptions\InvalidCallbackException;

/**
 * FPX Direct Debit Transaction Callback Handler
 */
class FpxDirectDebitTransactionCallback
{
    /**
     * Record type
     */
    private string $recordType;
    
    /**
     * Batch number
     */
    private string $batchNumber;
    
    /**
     * Mandate ID
     */
    private string $mandateId;
    
    /**
     * Mandate reference number
     */
    private string $mandateReferenceNumber;
    
    /**
     * Transaction ID
     */
    private string $transactionId;
    
    /**
     * Datetime
     */
    private string $datetime;
    
    /**
     * Reference number
     */
    private string $referenceNumber;
    
    /**
     * Amount
     */
    private string $amount;
    
    /**
     * Status
     */
    private string $status;
    
    /**
     * Status description
     */
    private string $statusDescription;
    
    /**
     * Cycle
     */
    private int $cycle;
    
    /**
     * Checksum
     */
    private string $checksum;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new FPX Direct Debit transaction callback instance
     *
     * @param array $data
     * @throws InvalidCallbackException
     */
    public function __construct(array $data)
    {
        $this->validateCallback($data);
        
        $this->recordType = $data['record_type'] ?? '';
        $this->batchNumber = $data['batch_number'] ?? '';
        $this->mandateId = $data['mandate_id'] ?? '';
        $this->mandateReferenceNumber = $data['mandate_reference_number'] ?? '';
        $this->transactionId = $data['transaction_id'] ?? '';
        $this->datetime = $data['datetime'] ?? '';
        $this->referenceNumber = $data['reference_number'] ?? '';
        $this->amount = (string) ($data['amount'] ?? '');
        $this->status = $data['status'] ?? '';
        $this->statusDescription = $data['status_description'] ?? '';
        $this->cycle = (int) ($data['cycle'] ?? 0);
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
        if (!isset($data['record_type']) || $data['record_type'] !== 'transaction') {
            throw new InvalidCallbackException("Invalid record type for FPX Direct Debit transaction callback");
        }
        
        $requiredFields = [
            'mandate_id',
            'transaction_id',
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
            'batch_number' => $this->batchNumber,
            'mandate_id' => $this->mandateId,
            'mandate_reference_number' => $this->mandateReferenceNumber,
            'transaction_id' => $this->transactionId,
            'datetime' => $this->datetime,
            'reference_number' => $this->referenceNumber,
            'amount' => $this->amount,
            'status' => $this->status,
            'status_description' => $this->statusDescription,
            'cycle' => (string) $this->cycle,
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
     * Get batch number
     *
     * @return string
     */
    public function getBatchNumber(): string
    {
        return $this->batchNumber;
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
     * Get mandate reference number
     *
     * @return string
     */
    public function getMandateReferenceNumber(): string
    {
        return $this->mandateReferenceNumber;
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
     * Get datetime
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
    }
    
    /**
     * Get reference number
     *
     * @return string
     */
    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
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
     * Get cycle
     *
     * @return int
     */
    public function getCycle(): int
    {
        return $this->cycle;
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
