<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Transaction Resource
 */
class Transaction
{
    /**
     * Transaction ID
     */
    private string $id;
    
    /**
     * Payment intent ID
     */
    private string $paymentIntentId;
    
    /**
     * Payment channel
     */
    private int $paymentChannel;
    
    /**
     * Order number
     */
    private string $orderNumber;
    
    /**
     * Amount
     */
    private float $amount;
    
    /**
     * Currency
     */
    private string $currency;
    
    /**
     * Status
     */
    private string $status;
    
    /**
     * Payer name
     */
    private string $payerName;
    
    /**
     * Payer email
     */
    private string $payerEmail;
    
    /**
     * Payer phone
     */
    private ?string $payerPhone;
    
    /**
     * Reference ID
     */
    private ?string $referenceId;
    
    /**
     * Created at timestamp
     */
    private string $createdAt;
    
    /**
     * Updated at timestamp
     */
    private string $updatedAt;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new transaction instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->paymentIntentId = $data['payment_intent_id'] ?? '';
        $this->paymentChannel = (int) ($data['payment_channel'] ?? 0);
        $this->orderNumber = $data['order_number'] ?? '';
        $this->amount = (float) ($data['amount'] ?? 0);
        $this->currency = $data['currency'] ?? 'MYR';
        $this->status = $data['status'] ?? '';
        $this->payerName = $data['payer_name'] ?? '';
        $this->payerEmail = $data['payer_email'] ?? '';
        $this->payerPhone = $data['payer_phone'] ?? null;
        $this->referenceId = $data['reference_id'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
        $this->updatedAt = $data['updated_at'] ?? '';
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
     * Get payment intent ID
     *
     * @return string
     */
    public function getPaymentIntentId(): string
    {
        return $this->paymentIntentId;
    }
    
    /**
     * Get payment channel
     *
     * @return int
     */
    public function getPaymentChannel(): int
    {
        return $this->paymentChannel;
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
     * Get amount
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
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
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * Check if transaction is successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }
    
    /**
     * Check if transaction is pending
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if transaction has failed
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
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
     * Get payer phone
     *
     * @return string|null
     */
    public function getPayerPhone(): ?string
    {
        return $this->payerPhone;
    }
    
    /**
     * Get reference ID
     *
     * @return string|null
     */
    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }
    
    /**
     * Get created at timestamp
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
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
     * Get raw data
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
