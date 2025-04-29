<?php

declare(strict_types=1);

namespace Bayarcash\Callbacks;

use Bayarcash\Exceptions\InvalidCallbackException;

/**
 * FPX Direct Debit Bank Approval Callback Handler
 */
class FpxDirectDebitBankApprovalCallback
{
    /**
     * Record type
     */
    private string $recordType;
    
    /**
     * Approval date
     */
    private string $approvalDate;
    
    /**
     * Approval status
     */
    private string $approvalStatus;
    
    /**
     * Mandate ID
     */
    private string $mandateId;
    
    /**
     * Mandate reference number
     */
    private string $mandateReferenceNumber;
    
    /**
     * Order number
     */
    private string $orderNumber;
    
    /**
     * Payer bank code hashed
     */
    private string $payerBankCodeHashed;
    
    /**
     * Payer bank code
     */
    private string $payerBankCode;
    
    /**
     * Payer bank account number
     */
    private string $payerBankAccountNo;
    
    /**
     * Application type
     */
    private string $applicationType;
    
    /**
     * Checksum
     */
    private string $checksum;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new FPX Direct Debit bank approval callback instance
     *
     * @param array $data
     * @throws InvalidCallbackException
     */
    public function __construct(array $data)
    {
        $this->validateCallback($data);
        
        $this->recordType = $data['record_type'] ?? '';
        $this->approvalDate = $data['approval_date'] ?? '';
        $this->approvalStatus = $data['approval_status'] ?? '';
        $this->mandateId = $data['mandate_id'] ?? '';
        $this->mandateReferenceNumber = $data['mandate_reference_number'] ?? '';
        $this->orderNumber = $data['order_number'] ?? '';
        $this->payerBankCodeHashed = $data['payer_bank_code_hashed'] ?? '';
        $this->payerBankCode = $data['payer_bank_code'] ?? '';
        $this->payerBankAccountNo = $data['payer_bank_account_no'] ?? '';
        $this->applicationType = (string) ($data['application_type'] ?? '');
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
        if (!isset($data['record_type']) || $data['record_type'] !== 'bank_approval') {
            throw new InvalidCallbackException("Invalid record type for FPX Direct Debit bank approval callback");
        }
        
        $requiredFields = [
            'mandate_id',
            'order_number',
            'approval_status',
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
            'approval_date' => $this->approvalDate,
            'approval_status' => $this->approvalStatus,
            'mandate_id' => $this->mandateId,
            'mandate_reference_number' => $this->mandateReferenceNumber,
            'order_number' => $this->orderNumber,
            'payer_bank_code_hashed' => $this->payerBankCodeHashed,
            'payer_bank_code' => $this->payerBankCode,
            'payer_bank_account_no' => $this->payerBankAccountNo,
            'application_type' => $this->applicationType,
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
     * Get approval date
     *
     * @return string
     */
    public function getApprovalDate(): string
    {
        return $this->approvalDate;
    }
    
    /**
     * Get approval status
     *
     * @return string
     */
    public function getApprovalStatus(): string
    {
        return $this->approvalStatus;
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
     * Get order number
     *
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }
    
    /**
     * Get payer bank code hashed
     *
     * @return string
     */
    public function getPayerBankCodeHashed(): string
    {
        return $this->payerBankCodeHashed;
    }
    
    /**
     * Get payer bank code
     *
     * @return string
     */
    public function getPayerBankCode(): string
    {
        return $this->payerBankCode;
    }
    
    /**
     * Get payer bank account number
     *
     * @return string
     */
    public function getPayerBankAccountNo(): string
    {
        return $this->payerBankAccountNo;
    }
    
    /**
     * Get application type
     *
     * @return string
     */
    public function getApplicationType(): string
    {
        return $this->applicationType;
    }
    
    /**
     * Get application type text
     *
     * @return string
     */
    public function getApplicationTypeText(): string
    {
        return match ($this->applicationType) {
            '01' => 'Enrollment',
            '02' => 'Maintenance',
            '03' => 'Termination',
            default => 'Unknown Application Type',
        };
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
