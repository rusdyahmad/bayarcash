<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Portal Resource
 */
class Portal
{
    /**
     * Portal ID
     */
    private int $id;
    
    /**
     * Portal key
     */
    private string $portalKey;
    
    /**
     * Portal name
     */
    private string $portalName;
    
    /**
     * Portal URL
     */
    private string $url;
    
    /**
     * Transaction notification email
     */
    private string $transactionNotificationEmail;
    
    /**
     * Custom payment button text
     */
    private ?string $customPaymentButtonText;
    
    /**
     * Payment channels
     * 
     * @var PaymentChannel[]
     */
    private array $paymentChannels;
    
    /**
     * Created at timestamp
     */
    private string $createdAt;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new portal instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->portalKey = $data['portal_key'] ?? '';
        $this->portalName = $data['portal_name'] ?? '';
        $this->url = $data['url'] ?? '';
        $this->transactionNotificationEmail = $data['transaction_notification_email'] ?? '';
        $this->customPaymentButtonText = $data['custom_payment_button_text'] ?? null;
        $this->createdAt = $data['created_at'] ?? '';
        
        $this->paymentChannels = [];
        if (isset($data['payment_channels']) && is_array($data['payment_channels'])) {
            foreach ($data['payment_channels'] as $channelData) {
                $this->paymentChannels[] = new PaymentChannel($channelData);
            }
        }
        
        $this->data = $data;
    }
    
    /**
     * Get portal ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Get portal key
     *
     * @return string
     */
    public function getPortalKey(): string
    {
        return $this->portalKey;
    }
    
    /**
     * Get portal name
     *
     * @return string
     */
    public function getPortalName(): string
    {
        return $this->portalName;
    }
    
    /**
     * Get portal URL
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
    
    /**
     * Get transaction notification email
     *
     * @return string
     */
    public function getTransactionNotificationEmail(): string
    {
        return $this->transactionNotificationEmail;
    }
    
    /**
     * Get custom payment button text
     *
     * @return string|null
     */
    public function getCustomPaymentButtonText(): ?string
    {
        return $this->customPaymentButtonText;
    }
    
    /**
     * Get payment channels
     *
     * @return PaymentChannel[]
     */
    public function getPaymentChannels(): array
    {
        return $this->paymentChannels;
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
     * Get raw data
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
