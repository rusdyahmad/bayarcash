<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * NETS Payment Channel
 */
class NetsChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 11;
    
    /**
     * Channel name
     */
    protected string $channelName = 'NETS';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any NETS-specific data transformations here
        
        return $data;
    }
}
