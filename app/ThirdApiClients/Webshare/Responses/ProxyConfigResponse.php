<?php

namespace App\ThirdApiClients\Webshare\Responses;

use Illuminate\Support\Collection;

class ProxyConfigResponse
{
    protected int $id;
    protected string $state;
    protected Collection $countries;
    protected Collection $availableCountries;
    protected Collection $unallocatedCountries;
    protected Collection $asns;
    protected Collection $availableAsns;
    protected Collection $ipRanges_24;
    protected Collection $ipRanges_16;
    protected Collection $ipRanges_8;
    protected Collection $availableIpRanges_24;
    protected Collection $availableIpRanges_16;
    protected Collection $availableIpRanges_8;
    protected string $username;
    protected string $password;
    protected int $requestTimeout;
    protected int $requestIdleTimeout;
    protected Collection $ipAuthorizationCountryCodes;
    protected bool $autoReplaceInvalidProxies;
    protected bool $autoReplaceLowCountryConfidenceProxies;
    protected bool $autoReplaceOutOfRotationProxies;
    protected bool $autoReplaceFailedSiteCheckProxies;
    protected string $proxyListDownloadToken;
    protected string $createdAt;
    protected string $updatedAt;
    protected bool $isProxyUsed;

    /**
     * @param int $id
     * @param string $state
     * @param Collection $countries
     * @param Collection $availableCountries
     * @param Collection $unallocatedCountries
     * @param Collection $asns
     * @param Collection $availableAsns
     * @param Collection $ipRanges_24
     * @param Collection $ipRanges_16
     * @param Collection $ipRanges_8
     * @param Collection $availableIpRanges_24
     * @param Collection $availableIpRanges_16
     * @param Collection $availableIpRanges_8
     * @param string $username
     * @param string $password
     * @param int $requestTimeout
     * @param int $requestIdleTimeout
     * @param Collection $ipAuthorizationCountryCodes
     * @param bool $autoReplaceInvalidProxies
     * @param bool $autoReplaceLowCountryConfidenceProxies
     * @param bool $autoReplaceOutOfRotationProxies
     * @param bool $autoReplaceFailedSiteCheckProxies
     * @param string $proxyListDownloadToken
     * @param string $createdAt
     * @param string $updatedAt
     * @param bool $isProxyUsed
     */
    public function __construct(int $id, string $state, Collection $countries, Collection $availableCountries, Collection $unallocatedCountries, Collection $asns, Collection $availableAsns, Collection $ipRanges_24, Collection $ipRanges_16, Collection $ipRanges_8, Collection $availableIpRanges_24, Collection $availableIpRanges_16, Collection $availableIpRanges_8, string $username, string $password, int $requestTimeout, int $requestIdleTimeout, Collection $ipAuthorizationCountryCodes, bool $autoReplaceInvalidProxies, bool $autoReplaceLowCountryConfidenceProxies, bool $autoReplaceOutOfRotationProxies, bool $autoReplaceFailedSiteCheckProxies, string $proxyListDownloadToken, string $createdAt, string $updatedAt, bool $isProxyUsed)
    {
        $this->id = $id;
        $this->state = $state;
        $this->countries = $countries;
        $this->availableCountries = $availableCountries;
        $this->unallocatedCountries = $unallocatedCountries;
        $this->asns = $asns;
        $this->availableAsns = $availableAsns;
        $this->ipRanges_24 = $ipRanges_24;
        $this->ipRanges_16 = $ipRanges_16;
        $this->ipRanges_8 = $ipRanges_8;
        $this->availableIpRanges_24 = $availableIpRanges_24;
        $this->availableIpRanges_16 = $availableIpRanges_16;
        $this->availableIpRanges_8 = $availableIpRanges_8;
        $this->username = $username;
        $this->password = $password;
        $this->requestTimeout = $requestTimeout;
        $this->requestIdleTimeout = $requestIdleTimeout;
        $this->ipAuthorizationCountryCodes = $ipAuthorizationCountryCodes;
        $this->autoReplaceInvalidProxies = $autoReplaceInvalidProxies;
        $this->autoReplaceLowCountryConfidenceProxies = $autoReplaceLowCountryConfidenceProxies;
        $this->autoReplaceOutOfRotationProxies = $autoReplaceOutOfRotationProxies;
        $this->autoReplaceFailedSiteCheckProxies = $autoReplaceFailedSiteCheckProxies;
        $this->proxyListDownloadToken = $proxyListDownloadToken;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isProxyUsed = $isProxyUsed;
    }

    public static function fromArray(array $data): static
    {
        return new static(
            $data['id'],
            $data['state'],
            collect($data['countries']),
            collect($data['available_countries']),
            collect($data['unallocated_countries']),
            collect($data['asns']),
            collect($data['available_asns']),
            collect($data['ip_ranges_24']),
            collect($data['ip_ranges_16']),
            collect($data['ip_ranges_8']),
            collect($data['available_ip_ranges_24']),
            collect($data['available_ip_ranges_16']),
            collect($data['available_ip_ranges_8']),
            $data['username'],
            $data['password'],
            $data['request_timeout'],
            $data['request_idle_timeout'],
            collect($data['ip_authorization_country_codes']),
            $data['auto_replace_invalid_proxies'],
            $data['auto_replace_low_country_confidence_proxies'],
            $data['auto_replace_out_of_rotation_proxies'],
            $data['auto_replace_failed_site_check_proxies'],
            $data['proxy_list_download_token'],
            $data['created_at'],
            $data['updated_at'],
            $data['is_proxy_used']
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return Collection
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    /**
     * @return Collection
     */
    public function getAvailableCountries(): Collection
    {
        return $this->availableCountries;
    }

    /**
     * @return Collection
     */
    public function getUnallocatedCountries(): Collection
    {
        return $this->unallocatedCountries;
    }

    /**
     * @return Collection
     */
    public function getAsns(): Collection
    {
        return $this->asns;
    }

    /**
     * @return Collection
     */
    public function getAvailableAsns(): Collection
    {
        return $this->availableAsns;
    }

    /**
     * @return Collection
     */
    public function getIpRanges24(): Collection
    {
        return $this->ipRanges_24;
    }

    /**
     * @return Collection
     */
    public function getIpRanges16(): Collection
    {
        return $this->ipRanges_16;
    }

    /**
     * @return Collection
     */
    public function getIpRanges8(): Collection
    {
        return $this->ipRanges_8;
    }

    /**
     * @return Collection
     */
    public function getAvailableIpRanges24(): Collection
    {
        return $this->availableIpRanges_24;
    }

    /**
     * @return Collection
     */
    public function getAvailableIpRanges16(): Collection
    {
        return $this->availableIpRanges_16;
    }

    /**
     * @return Collection
     */
    public function getAvailableIpRanges8(): Collection
    {
        return $this->availableIpRanges_8;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRequestTimeout(): int
    {
        return $this->requestTimeout;
    }

    /**
     * @return int
     */
    public function getRequestIdleTimeout(): int
    {
        return $this->requestIdleTimeout;
    }

    /**
     * @return Collection
     */
    public function getIpAuthorizationCountryCodes(): Collection
    {
        return $this->ipAuthorizationCountryCodes;
    }

    /**
     * @return bool
     */
    public function isAutoReplaceInvalidProxies(): bool
    {
        return $this->autoReplaceInvalidProxies;
    }

    /**
     * @return bool
     */
    public function isAutoReplaceLowCountryConfidenceProxies(): bool
    {
        return $this->autoReplaceLowCountryConfidenceProxies;
    }

    /**
     * @return bool
     */
    public function isAutoReplaceOutOfRotationProxies(): bool
    {
        return $this->autoReplaceOutOfRotationProxies;
    }

    /**
     * @return bool
     */
    public function isAutoReplaceFailedSiteCheckProxies(): bool
    {
        return $this->autoReplaceFailedSiteCheckProxies;
    }

    /**
     * @return string
     */
    public function getProxyListDownloadToken(): string
    {
        return $this->proxyListDownloadToken;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function isProxyUsed(): bool
    {
        return $this->isProxyUsed;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'state' => $this->getState(),
            'countries' => $this->getCountries()->toArray(),
            'availableCountries' => $this->getAvailableCountries()->toArray(),
            'unallocatedCountries' => $this->getUnallocatedCountries()->toArray(),
            'asns' => $this->getAsns()->toArray(),
            'availableAsns' => $this->getAvailableAsns()->toArray(),
            'ipRanges_24' => $this->getIpRanges24()->toArray(),
            'ipRanges_16' => $this->getIpRanges16()->toArray(),
            'ipRanges_8' => $this->getIpRanges8()->toArray(),
            'availableIpRanges_24' => $this->getAvailableIpRanges24()->toArray(),
            'availableIpRanges_16' => $this->getAvailableIpRanges16()->toArray(),
            'availableIpRanges_8' => $this->getAvailableIpRanges8()->toArray(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'requestTimeout' => $this->getRequestTimeout(),
            'requestIdleTimeout' => $this->getRequestIdleTimeout(),
            'ipAuthorizationCountryCodes' => $this->getIpAuthorizationCountryCodes()->toArray(),
            'autoReplaceInvalidProxies' => $this->isAutoReplaceInvalidProxies(),
            'autoReplaceLowCountryConfidenceProxies' => $this->isAutoReplaceLowCountryConfidenceProxies(),
            'autoReplaceOutOfRotationProxies' => $this->isAutoReplaceOutOfRotationProxies(),
            'autoReplaceFailedSiteCheckProxies' => $this->isAutoReplaceFailedSiteCheckProxies(),
            'proxyListDownloadToken' => $this->getProxyListDownloadToken(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'isProxyUsed' => $this->isProxyUsed(),
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
