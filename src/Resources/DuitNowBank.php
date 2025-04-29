<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * DuitNow Bank Resource
 */
class DuitNowBank
{
    /**
     * Bank name
     */
    private string $name;
    
    /**
     * Bank code
     */
    private string $code;
    
    /**
     * Bank availability
     */
    private bool $availability;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new DuitNow bank instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->name = $data['bank_name'] ?? '';
        $this->code = $data['bank_code'] ?? '';
        $this->availability = $data['bank_availability'] ?? false;
        $this->data = $data;
    }
    
    /**
     * Get bank name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Get bank code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    
    /**
     * Check if bank is available
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->availability;
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
