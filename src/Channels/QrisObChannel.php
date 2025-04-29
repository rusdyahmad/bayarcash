<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * QRIS OB Payment Channel
 */
class QrisObChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 9;
    
    /**
     * Channel name
     */
    protected string $channelName = 'QRIS OB';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any QRIS OB-specific data transformations here
        
        return $data;
    }
}
