<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * Boost PayFlex Payment Channel
 */
class BoostPayflexChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 8;
    
    /**
     * Channel name
     */
    protected string $channelName = 'Boost PayFlex';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any Boost PayFlex-specific data transformations here
        
        return $data;
    }
}
