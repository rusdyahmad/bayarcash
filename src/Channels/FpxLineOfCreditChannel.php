<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * FPX Line Of Credit Payment Channel
 */
class FpxLineOfCreditChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 4;
    
    /**
     * Channel name
     */
    protected string $channelName = 'FPX Line Of Credit';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any FPX Line Of Credit-specific data transformations here
        
        return $data;
    }
}
