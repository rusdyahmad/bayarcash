<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * DuitNow DOBW Payment Channel
 */
class DuitnowDobwChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 5;
    
    /**
     * Channel name
     */
    protected string $channelName = 'DuitNow DOBW';
    
    /**
     * Account types
     */
    public const CASA = '01';
    public const CREDIT_CARD = '02';
    public const EWALLET = '03';
    
    /**
     * Status constants
     */
    public const STATUS_NEW = 0;
    public const STATUS_PENDING = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_SUCCESS = 3;
    public const STATUS_CANCELLED = 4;
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any DuitNow DOBW-specific data transformations here
        
        return $data;
    }
    
    /**
     * Get status labels
     *
     * @return array<int, string>
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_SUCCESS => 'Successful',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
    
    /**
     * Get status text for a status code
     *
     * @param int $statusCode
     * @return string
     */
    public static function getStatusText(int $statusCode): string
    {
        $statuses = self::getStatusLabels();
        return $statuses[$statusCode] ?? 'UNKNOWN STATUS';
    }
    
    /**
     * Check if a transaction status is successful
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isSuccessful(int $statusCode): bool
    {
        return $statusCode === self::STATUS_SUCCESS;
    }
    
    /**
     * Check if a transaction status is pending
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isPending(int $statusCode): bool
    {
        return $statusCode === self::STATUS_PENDING;
    }
    
    /**
     * Check if a transaction status is failed
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isFailed(int $statusCode): bool
    {
        return $statusCode === self::STATUS_FAILED || $statusCode === self::STATUS_CANCELLED;
    }
}
