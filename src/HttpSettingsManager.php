<?php

namespace Glooby\HttpClientBundle;

use Glooby\HttpClientBundle\Proxy\Proxy;
use Glooby\HttpClientBundle\Proxy\ProxyParser;

/**
 * @author Emil Kilhage
 */
class HttpSettingsManager
{
    /** @var int */
    private $timeout;

    /** @var Proxy[] */
    private $proxies = [];

    /** @var Proxy */
    private $lastProxy;

    /** @var ProxyParser */
    private $proxyParser;

    /**
     * @param ProxyParser $proxyParser
     */
    public function setProxyParser(ProxyParser $proxyParser)
    {
        $this->proxyParser = $proxyParser;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Set the currently active global proxy, or null if no proxy is in use.
     *
     * @param null|string|array|Proxy $proxy
     */
    public function addProxy($proxy)
    {
        if (is_string($proxy)) {
            if (ProxyParser::validProxyString($proxy)) {
                $this->proxies[] = $this->proxyParser->parse($proxy);
            } elseif (file_exists($proxy)) {
                $this->proxies = array_merge(
                    $this->proxies,
                    $this->proxyParser->parseFile($proxy)
                );
            }
        } elseif (is_array($proxy)) {
            array_map([$this, 'addProxy'], $proxy);
        } elseif ($proxy instanceof Proxy) {
            $this->proxies[] = $proxy;
        }
    }

    /**
     * @return Proxy|null
     */
    public function getProxy()
    {
        if (count($this->proxies) === 0) {
            return null;
        }

        $key = array_rand($this->proxies);
        return $this->lastProxy = $this->proxies[$key];
    }

    /**
     * @return Proxy
     */
    public function getLastProxy()
    {
        return $this->lastProxy;
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
