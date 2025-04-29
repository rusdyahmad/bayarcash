<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Mandate Resource
 */
class Mandate
{
    /**
     * Mandate ID
     */
    private string $id;
    
    /**
     * Updated at timestamp
     */
    private string $updatedAt;
    
    /**
     * Mandate reference number
     */
    private string $mandateReferenceNumber;
    
    /**
     * Order number
     */
    private string $orderNumber;
    
    /**
     * Application reason
     */
    private string $applicationReason;
    
    /**
     * Frequency mode
     */
    private string $frequencyMode;
    
    /**
     * Frequency mode label
     */
    private string $frequencyModeLabel;
    
    /**
     * Effective date
     */
    private ?string $effectiveDate;
    
    /**
     * Expiry date
     */
    private ?string $expiryDate;
    
    /**
     * Currency
     */
    private string $currency;
    
    /**
     * Amount
     */
    private float $amount;
    
    /**
     * Payer name
     */
    private string $payerName;
    
    /**
     * Payer ID
     */
    private string $payerId;
    
    /**
     * Payer ID type
     */
    private string $payerIdType;
    
    /**
     * Payer bank account number
     */
    private string $payerBankAccountNumber;
    
    /**
     * Payer email
     */
    private string $payerEmail;
    
    /**
     * Payer telephone number
     */
    private string $payerTelephoneNumber;
    
    /**
     * Status
     */
    private int $status;
    
    /**
     * Status description
     */
    private string $statusDescription;
    
    /**
     * Return URL
     */
    private string $returnUrl;
    
    /**
     * Metadata
     */
    private ?array $metadata;
    
    /**
     * Portal
     */
    private string $portal;
    
    /**
     * Merchant
     */
    private array $merchant;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new mandate instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
        $this->mandateReferenceNumber = $data['mandate_reference_number'] ?? '';
        $this->orderNumber = $data['order_number'] ?? '';
        $this->applicationReason = $data['application_reason'] ?? '';
        $this->frequencyMode = $data['frequency_mode'] ?? '';
        $this->frequencyModeLabel = $data['frequency_mode_label'] ?? '';
        $this->effectiveDate = $data['effective_date'] ?? null;
        $this->expiryDate = $data['expiry_date'] ?? null;
        $this->currency = $data['currency'] ?? '';
        $this->amount = (float) ($data['amount'] ?? 0);
        $this->payerName = $data['payer_name'] ?? '';
        $this->payerId = $data['payer_id'] ?? '';
        $this->payerIdType = $data['payer_id_type'] ?? '';
        $this->payerBankAccountNumber = $data['payer_bank_account_number'] ?? '';
        $this->payerEmail = $data['payer_email'] ?? '';
        $this->payerTelephoneNumber = $data['payer_telephone_number'] ?? '';
        $this->status = (int) ($data['status'] ?? 0);
        $this->statusDescription = $data['status_description'] ?? '';
        $this->returnUrl = $data['return_url'] ?? '';
        $this->metadata = $data['metadata'] ?? null;
        $this->portal = $data['portal'] ?? '';
        $this->merchant = $data['merchant'] ?? [];
        $this->data = $data;
    }
    
    /**
     * Get mandate ID
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Get updated at timestamp
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
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
     * Get application reason
     *
     * @return string
     */
    public function getApplicationReason(): string
    {
        return $this->applicationReason;
    }
    
    /**
     * Get frequency mode
     *
     * @return string
     */
    public function getFrequencyMode(): string
    {
        return $this->frequencyMode;
    }
    
    /**
     * Get frequency mode label
     *
     * @return string
     */
    public function getFrequencyModeLabel(): string
    {
        return $this->frequencyModeLabel;
    }
    
    /**
     * Get effective date
     *
     * @return string|null
     */
    public function getEffectiveDate(): ?string
    {
        return $this->effectiveDate;
    }
    
    /**
     * Get expiry date
     *
     * @return string|null
     */
    public function getExpiryDate(): ?string
    {
        return $this->expiryDate;
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
     * @return float
     */
    public function getAmount(): float
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
     * Get payer ID
     *
     * @return string
     */
    public function getPayerId(): string
    {
        return $this->payerId;
    }
    
    /**
     * Get payer ID type
     *
     * @return string
     */
    public function getPayerIdType(): string
    {
        return $this->payerIdType;
    }
    
    /**
     * Get payer ID type text
     *
     * @return string
     */
    public function getPayerIdTypeText(): string
    {
        return match ($this->payerIdType) {
            '1' => 'NRIC',
            '2' => 'Old IC',
            '3' => 'Passport',
            '4' => 'Business Registration',
            '5' => 'Others',
            default => 'Unknown ID Type',
        };
    }
    
    /**
     * Get payer bank account number
     *
     * @return string
     */
    public function getPayerBankAccountNumber(): string
    {
        return $this->payerBankAccountNumber;
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
     * Get payer telephone number
     *
     * @return string
     */
    public function getPayerTelephoneNumber(): string
    {
        return $this->payerTelephoneNumber;
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
     * Get return URL
     *
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }
    
    /**
     * Get metadata
     *
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }
    
    /**
     * Get portal
     *
     * @return string
     */
    public function getPortal(): string
    {
        return $this->portal;
    }
    
    /**
     * Get merchant
     *
     * @return array
     */
    public function getMerchant(): array
    {
        return $this->merchant;
    }
    
    /**
     * Get merchant name
     *
     * @return string
     */
    public function getMerchantName(): string
    {
        return $this->merchant['name'] ?? '';
    }
    
    /**
     * Get merchant email
     *
     * @return string
     */
    public function getMerchantEmail(): string
    {
        return $this->merchant['email'] ?? '';
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
    
    /**
     * Get authorization URL
     *
     * @return string
     */
    public function getAuthorizationUrl(): string
    {
        return $this->data['authorization_url'] ?? $this->data['url'] ?? '';
    }
}
