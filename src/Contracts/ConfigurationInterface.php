<?php

declare(strict_types=1);

namespace Bayarcash\Contracts;

/**
 * Configuration Interface
 */
interface ConfigurationInterface
{
    /**
     * Get Personal Access Token (PAT)
     *
     * @return string
     */
    public function getPat(): string;
    
    /**
     * Get API secret key
     *
     * @return string
     */
    public function getApiSecretKey(): string;
    
    /**
     * Get portal key
     *
     * @return string
     */
    public function getPortalKey(): string;
    
    /**
     * Check if sandbox mode is enabled
     *
     * @return bool
     */
    public function isSandbox(): bool;
    
    /**
     * Set sandbox mode
     *
     * @param bool $sandbox
     * @return self
     */
    public function setSandbox(bool $sandbox): self;
    
    /**
     * Get API version
     *
     * @return string
     */
    public function getApiVersion(): string;
    
    /**
     * Set API version
     *
     * @param string $version
     * @return self
     */
    public function setApiVersion(string $version): self;
    
    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public function isDebug(): bool;
    
    /**
     * Set debug mode
     *
     * @param bool $debug
     * @return self
     */
    public function setDebug(bool $debug): self;
    
    /**
     * Get base URI
     *
     * @return string
     */
    public function getBaseUri(): string;
    
    /**
     * Get option
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption(string $key, $default = null);
    
    /**
     * Set option
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setOption(string $key, $value): self;
    
    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions(): array;
}
