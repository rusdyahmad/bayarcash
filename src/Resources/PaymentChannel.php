<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Payment Channel Resource
 */
class PaymentChannel
{
    /**
     * Channel ID
     */
    private int $id;
    
    /**
     * Channel code
     */
    private string $code;
    
    /**
     * Channel name
     */
    private string $name;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new payment channel instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = (int) ($data['id'] ?? 0);
        $this->code = $data['code'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->data = $data;
    }
    
    /**
     * Get channel ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Get channel code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    
    /**
     * Get channel name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
