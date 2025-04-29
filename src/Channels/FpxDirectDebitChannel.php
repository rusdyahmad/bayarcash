<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * FPX Direct Debit Payment Channel
 */
class FpxDirectDebitChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 5;
    
    /**
     * Channel name
     */
    protected string $channelName = 'FPX Direct Debit';
    
    /**
     * Application Type
     */
    public const ENROLMENT = '01';
    public const MAINTENANCE = '02';
    public const TERMINATION = '03';
    
    /**
     * Buyer ID Type
     */
    public const NRIC = 1;
    public const OLD_IC = 2;
    public const PASSPORT = 3;
    public const BUSINESS_REGISTRATION = 4;
    public const OTHERS = 5;
    
    /**
     * Frequency Mode
     */
    public const MODE_DAILY = 'DL';
    public const MODE_WEEKLY = 'WK';
    public const MODE_MONTHLY = 'MT';
    public const MODE_YEARLY = 'YR';
    
    /**
     * Status constants
     */
    public const STATUS_NEW = 0;
    public const STATUS_WAITING_APPROVAL = 1;
    public const STATUS_FAILED_BANK_VERIFICATION = 2;
    public const STATUS_ACTIVE = 3;
    public const STATUS_TERMINATED = 4;
    public const STATUS_APPROVED = 5;
    public const STATUS_REJECTED = 6;
    public const STATUS_CANCELLED = 7;
    public const STATUS_ERROR = 8;
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any FPX Direct Debit-specific data transformations here
        
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
            self::STATUS_WAITING_APPROVAL => 'Waiting Approval',
            self::STATUS_FAILED_BANK_VERIFICATION => 'Bank Verification Failed',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_ERROR => 'Error',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_TERMINATED => 'Terminated',
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
     * Get application type text
     *
     * @param string $applicationType
     * @return string
     */
    public static function getApplicationTypeText(string $applicationType): string
    {
        return match ($applicationType) {
            self::ENROLMENT => 'Enrollment',
            self::MAINTENANCE => 'Maintenance',
            self::TERMINATION => 'Termination',
            default => 'Unknown Application Type',
        };
    }
    
    /**
     * Get frequency mode text
     *
     * @param string $frequencyModeCode
     * @return string
     */
    public static function getFrequencyModeText(string $frequencyModeCode): string
    {
        return match ($frequencyModeCode) {
            self::MODE_DAILY => 'Daily',
            self::MODE_WEEKLY => 'Weekly',
            self::MODE_MONTHLY => 'Monthly',
            self::MODE_YEARLY => 'Yearly',
            default => 'Unknown Frequency Mode',
        };
    }
    
    /**
     * Check if a transaction status is successful
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isSuccessful(int $statusCode): bool
    {
        return $statusCode === self::STATUS_APPROVED || $statusCode === self::STATUS_ACTIVE;
    }
    
    /**
     * Check if a transaction status is pending
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isPending(int $statusCode): bool
    {
        return $statusCode === self::STATUS_NEW || $statusCode === self::STATUS_WAITING_APPROVAL;
    }
    
    /**
     * Check if a transaction status is failed
     *
     * @param int $statusCode
     * @return bool
     */
    public static function isFailed(int $statusCode): bool
    {
        return in_array($statusCode, [
            self::STATUS_FAILED_BANK_VERIFICATION,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
            self::STATUS_ERROR,
            self::STATUS_TERMINATED,
        ], true);
    }
    
    /**
     * Create a direct debit mandate
     *
     * @param array $data Mandate data
     * @return \Bayarcash\Resources\Mandate
     * 
     * @throws \Bayarcash\Exceptions\ApiException If the API request fails
     */
    public function createDirectDebitMandate(array $data): \Bayarcash\Resources\Mandate
    {
        // Ensure the payment channel is set correctly
        $data['payment_channel'] = $this->channelId;
        
        // Prepare the data for the API request
        $data = $this->preparePaymentData($data);
        
        // Create the mandate via the API client
        return $this->apiClient->createDirectDebitMandate($data);
    }
}
