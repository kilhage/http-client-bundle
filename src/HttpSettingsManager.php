<?php

namespace Glooby\HttpClientBundle;

use Glooby\HttpClientBundle\Proxy\Proxy;
use Glooby\HttpClientBundle\Proxy\ProxyParser;

/**
 * @author Emil Kilhage
 */
class HttpSettingsManager
{
    private $timeout;

    /** @var Proxy|null */
    private $proxy;

    /** @var string|null */
    private $proxyFile;

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
     * @param null|string $string
     */
    public function setProxy($string)
    {
        if ($string) {
            $proxy = $this->proxyParser->parse($string);
            $this->proxy = $proxy;
        }
    }

    /**
     * @param null|string $proxyFile
     */
    public function setProxyFile($proxyFile)
    {
        $this->proxyFile = $proxyFile;

        if ($proxyFile) {
            $proxy = $this->proxyParser->parseFile($proxyFile);
            $this->proxy = $proxy;
        }
    }

    /**
     * @return Proxy
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return null|string
     */
    public function getProxyFile()
    {
        return $this->proxyFile;
    }

    /**
     * @return ProxyParser
     */
    public function getProxyParser()
    {
        return $this->proxyParser;
    }
}
