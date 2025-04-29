<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * SPay Later Payment Channel
 */
class SPayLaterChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 7;
    
    /**
     * Channel name
     */
    protected string $channelName = 'SPay Later';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any SPay Later-specific data transformations here
        
        return $data;
    }
}
