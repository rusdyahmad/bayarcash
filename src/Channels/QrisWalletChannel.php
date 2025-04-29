<?php

declare(strict_types=1);

namespace Bayarcash\Channels;

/**
 * QRIS Wallet Payment Channel
 */
class QrisWalletChannel extends AbstractChannel
{
    /**
     * Channel ID
     */
    protected int $channelId = 10;
    
    /**
     * Channel name
     */
    protected string $channelName = 'QRIS Wallet';
    
    /**
     * Prepare payment data
     *
     * @param array $data
     * @return array
     */
    protected function preparePaymentData(array $data): array
    {
        $data = parent::preparePaymentData($data);
        
        // Add any QRIS Wallet-specific data transformations here
        
        return $data;
    }
}
