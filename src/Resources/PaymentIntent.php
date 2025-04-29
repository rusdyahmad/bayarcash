<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Payment Intent Resource
 */
class PaymentIntent
{
    /**
     * Payment intent ID
     */
    private string $id;
    
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
     * Payment URL
     */
    private ?string $paymentUrl;
    
    /**
     * Created at timestamp
     */
    private string $createdAt;
    
    /**
     * Expires at timestamp
     */
    private ?string $expiresAt;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new payment intent instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->paymentChannel = (int) ($data['payment_channel'] ?? 0);
        $this->orderNumber = $data['order_number'] ?? '';
        $this->amount = (float) ($data['amount'] ?? 0);
        $this->currency = $data['currency'] ?? 'MYR';
        $this->status = $data['status'] ?? '';
        $this->paymentUrl = $data['url'] ?? $data['payment_url'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
        $this->expiresAt = $data['expires_at'] ?? null;
        $this->data = $data;
    }
    
    /**
     * Get payment intent ID
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * Get payment URL
     *
     * @return string|null
     */
    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
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
     * Get expires at timestamp
     *
     * @return string|null
     */
    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
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
