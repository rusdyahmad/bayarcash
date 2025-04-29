<?php

declare(strict_types=1);

namespace Bayarcash\Support;

use Bayarcash\Contracts\ConfigurationInterface;
use Bayarcash\Exceptions\InvalidConfigurationException;

/**
 * Configuration implementation
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Personal Access Token (PAT)
     */
    private string $pat;
    
    /**
     * API secret key
     */
    private string $apiSecretKey;
    
    /**
     * Portal key
     */
    private string $portalKey;
    
    /**
     * Whether to use sandbox mode
     */
    private bool $sandbox = false;
    
    /**
     * API version
     */
    private string $apiVersion = 'v3';
    
    /**
     * Debug mode
     */
    private bool $debug = false;
    
    /**
     * Additional options
     */
    private array $options = [];
    
    /**
     * Create a new configuration instance
     *
     * @param string $pat Personal Access Token (PAT)
     * @param string $apiSecretKey API secret key
     * @param string $portalKey Portal key
     * @param array $options Additional options
     * 
     * @throws InvalidConfigurationException If the configuration is invalid
     */
    public function __construct(
        string $pat,
        string $apiSecretKey,
        string $portalKey,
        array $options = []
    ) {
        if (empty($pat)) {
            throw new InvalidConfigurationException('Personal Access Token (PAT) is required');
        }
        
        if (empty($apiSecretKey)) {
            throw new InvalidConfigurationException('API secret key is required');
        }
        
        if (empty($portalKey)) {
            throw new InvalidConfigurationException('Portal key is required');
        }
        
        $this->pat = $pat;
        $this->apiSecretKey = $apiSecretKey;
        $this->portalKey = $portalKey;
        
        // Set options
        if (isset($options['sandbox'])) {
            $this->sandbox = (bool) $options['sandbox'];
        }
        
        if (isset($options['api_version'])) {
            $this->apiVersion = $options['api_version'];
        }
        
        if (isset($options['debug'])) {
            $this->debug = (bool) $options['debug'];
        }
        
        // Store remaining options
        $this->options = $options;
    }
    
    /**
     * Get Personal Access Token (PAT)
     *
     * @return string
     */
    public function getPat(): string
    {
        return $this->pat;
    }
    
    /**
     * Get API secret key
     *
     * @return string
     */
    public function getApiSecretKey(): string
    {
        return $this->apiSecretKey;
    }
    
    /**
     * Get portal key
     *
     * @return string
     */
    public function getPortalKey(): string
    {
        return $this->portalKey;
    }
    
    /**
     * Check if sandbox mode is enabled
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        return $this->sandbox;
    }
    
    /**
     * Set sandbox mode
     *
     * @param bool $sandbox
     * @return self
     */
    public function setSandbox(bool $sandbox): self
    {
        $this->sandbox = $sandbox;
        return $this;
    }
    
    /**
     * Get API version
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }
    
    /**
     * Set API version
     *
     * @param string $version
     * @return self
     */
    public function setApiVersion(string $version): self
    {
        $this->apiVersion = $version;
        return $this;
    }
    
    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }
    
    /**
     * Set debug mode
     *
     * @param bool $debug
     * @return self
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }
    
    /**
     * Get base URI
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        if ($this->apiVersion === 'v3') {
            return $this->sandbox
                ? 'https://api.console.bayarcash-sandbox.com/v3/'
                : 'https://api.console.bayarcash.com/v3/';
        }
        
        return $this->sandbox
            ? 'https://console.bayarcash-sandbox.com/api/v2/'
            : 'https://console.bayarcash.com/api/v2/';
    }
    
    /**
     * Get option
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
    
    /**
     * Set option
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;
        return $this;
    }
    
    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
