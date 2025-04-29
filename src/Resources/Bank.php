<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Bank Resource
 */
class Bank
{
    /**
     * Bank display name
     */
    private string $displayName;
    
    /**
     * Bank name
     */
    private string $name;
    
    /**
     * Bank code
     */
    private string $code;
    
    /**
     * Bank code hashed
     */
    private string $codeHashed;
    
    /**
     * Bank availability
     */
    private bool $availability;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new bank instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->displayName = $data['bank_display_name'] ?? '';
        $this->name = $data['bank_name'] ?? '';
        $this->code = $data['bank_code'] ?? '';
        $this->codeHashed = $data['bank_code_hashed'] ?? '';
        $this->availability = $data['bank_availability'] ?? false;
        $this->data = $data;
    }
    
    /**
     * Get bank display name
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
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
     * Get bank code hashed
     *
     * @return string
     */
    public function getCodeHashed(): string
    {
        return $this->codeHashed;
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
