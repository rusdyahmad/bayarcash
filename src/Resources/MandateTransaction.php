<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Mandate Transaction Resource
 */
class MandateTransaction
{
    /**
     * Transaction ID
     */
    private string $id;
    
    /**
     * Updated at timestamp
     */
    private string $updatedAt;
    
    /**
     * Datetime
     */
    private string $datetime;
    
    /**
     * Order number
     */
    private string $orderNumber;
    
    /**
     * Payer name
     */
    private string $payerName;
    
    /**
     * Payer email
     */
    private string $payerEmail;
    
    /**
     * Payer telephone number
     */
    private string $payerTelephoneNumber;
    
    /**
     * Currency
     */
    private string $currency;
    
    /**
     * Amount
     */
    private float $amount;
    
    /**
     * Exchange reference number
     */
    private string $exchangeReferenceNumber;
    
    /**
     * Exchange transaction ID
     */
    private string $exchangeTransactionId;
    
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
     * Return URL
     */
    private string $returnUrl;
    
    /**
     * Metadata
     */
    private ?array $metadata;
    
    /**
     * Payout
     */
    private array $payout;
    
    /**
     * Payment gateway
     */
    private array $paymentGateway;
    
    /**
     * Portal
     */
    private string $portal;
    
    /**
     * Merchant
     */
    private array $merchant;
    
    /**
     * Mandate
     */
    private ?Mandate $mandate;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new mandate transaction instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
        $this->datetime = $data['datetime'] ?? '';
        $this->orderNumber = $data['order_number'] ?? '';
        $this->payerName = $data['payer_name'] ?? '';
        $this->payerEmail = $data['payer_email'] ?? '';
        $this->payerTelephoneNumber = $data['payer_telephone_number'] ?? '';
        $this->currency = $data['currency'] ?? '';
        $this->amount = (float) ($data['amount'] ?? 0);
        $this->exchangeReferenceNumber = $data['exchange_reference_number'] ?? '';
        $this->exchangeTransactionId = $data['exchange_transaction_id'] ?? '';
        $this->payerBankName = $data['payer_bank_name'] ?? '';
        $this->status = (int) ($data['status'] ?? 0);
        $this->statusDescription = $data['status_description'] ?? '';
        $this->returnUrl = $data['return_url'] ?? '';
        $this->metadata = $data['metadata'] ?? null;
        $this->payout = $data['payout'] ?? [];
        $this->paymentGateway = $data['payment_gateway'] ?? [];
        $this->portal = $data['portal'] ?? '';
        $this->merchant = $data['merchant'] ?? [];
        
        $this->mandate = null;
        if (isset($data['mandate']) && is_array($data['mandate'])) {
            $this->mandate = new Mandate($data['mandate']);
        }
        
        $this->data = $data;
    }
    
    /**
     * Get transaction ID
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
     * Get datetime
     *
     * @return string
     */
    public function getDatetime(): string
    {
        return $this->datetime;
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
     * Get payer telephone number
     *
     * @return string
     */
    public function getPayerTelephoneNumber(): string
    {
        return $this->payerTelephoneNumber;
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
     * Get payout
     *
     * @return array
     */
    public function getPayout(): array
    {
        return $this->payout;
    }
    
    /**
     * Get payout reference number
     *
     * @return string|null
     */
    public function getPayoutReferenceNumber(): ?string
    {
        return $this->payout['ref_no'] ?? null;
    }
    
    /**
     * Get payment gateway
     *
     * @return array
     */
    public function getPaymentGateway(): array
    {
        return $this->paymentGateway;
    }
    
    /**
     * Get payment gateway name
     *
     * @return string
     */
    public function getPaymentGatewayName(): string
    {
        return $this->paymentGateway['name'] ?? '';
    }
    
    /**
     * Get payment gateway code
     *
     * @return string
     */
    public function getPaymentGatewayCode(): string
    {
        return $this->paymentGateway['code'] ?? '';
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
     * Get mandate
     *
     * @return Mandate|null
     */
    public function getMandate(): ?Mandate
    {
        return $this->mandate;
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
     * Get raw data
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
