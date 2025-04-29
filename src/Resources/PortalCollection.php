<?php

declare(strict_types=1);

namespace Bayarcash\Resources;

/**
 * Portal Collection Resource
 */
class PortalCollection
{
    /**
     * Portals
     * 
     * @var Portal[]
     */
    private array $portals;
    
    /**
     * Pagination links
     */
    private array $links;
    
    /**
     * Pagination metadata
     */
    private array $meta;
    
    /**
     * Merchant data
     */
    private array $merchant;
    
    /**
     * Raw data
     */
    private array $data;
    
    /**
     * Create a new portal collection instance
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->portals = [];
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $portalData) {
                $this->portals[] = new Portal($portalData);
            }
        }
        
        $this->links = $data['links'] ?? [];
        $this->meta = $data['meta'] ?? [];
        $this->merchant = $data['meta']['merchant'] ?? [];
        $this->data = $data;
    }
    
    /**
     * Get portals
     *
     * @return Portal[]
     */
    public function getPortals(): array
    {
        return $this->portals;
    }
    
    /**
     * Get pagination links
     *
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }
    
    /**
     * Get pagination metadata
     *
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }
    
    /**
     * Get merchant data
     *
     * @return array
     */
    public function getMerchant(): array
    {
        return $this->merchant;
    }
    
    /**
     * Get merchant name
     *
     * @return string
     */
    public function getMerchantName(): string
    {
        return $this->merchant['name'] ?? '';
    }
    
    /**
     * Get merchant email
     *
     * @return string
     */
    public function getMerchantEmail(): string
    {
        return $this->merchant['email'] ?? '';
    }
    
    /**
     * Get merchant created at timestamp
     *
     * @return string
     */
    public function getMerchantCreatedAt(): string
    {
        return $this->merchant['created_at'] ?? '';
    }
    
    /**
     * Get current page
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return (int) ($this->meta['current_page'] ?? 1);
    }
    
    /**
     * Get total pages
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return (int) ($this->meta['last_page'] ?? 1);
    }
    
    /**
     * Get total items
     *
     * @return int
     */
    public function getTotal(): int
    {
        return (int) ($this->meta['total'] ?? 0);
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
