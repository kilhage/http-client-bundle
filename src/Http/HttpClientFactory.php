<?php

namespace Glooby\HttpClientBundle\Http;

use Glooby\HttpClientBundle\ClientFactoryInterface;
use Glooby\HttpClientBundle\HttpSettingsManager;
use Glooby\HttpClientBundle\Proxy\Proxy;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Creates HTTP clients with Guzzle, while providing defaults such as global proxy server and more. This allows for
 * tunnelling of both HTTP and HTTPS traffic over a dynamic ssh forward.
 *
 *      ssh:    ssh -D2345 user@host
 *      proxy:  socks5://127.0.0.1:2345
 */
class HttpClientFactory implements LoggerAwareInterface, ClientFactoryInterface
{
    use LoggerAwareTrait;

    /** @var HttpSettingsManager */
    protected $settingsManager;

    /** @var Proxy The proxy to use for HTTP/HTTPS requests */
    protected $proxy;

    /** @var array Default settings*/
    protected $defaults = [];

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->setDefault('timeout', $timeout);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    private function setDefault($key, $value)
    {
        $this->defaults[$key] = $value;
    }

    /**
     * Set the currently active global proxy, or null if no proxy is in use.
     *
     * @param Proxy $proxy
     */
    public function setProxy(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @param array $config
     * @return array
     */
    public function createConfig(array $config = [])
    {
        $config = $this->buildDefaults($config);
        $config = $this->configureProxy($config);
        return $config;
    }

    /**
     * @param array $config
     *
     * @return Client
     */
    public function createClient(array $config = [])
    {
        $config = $this->createConfig($config);
        return new Client($config);
    }

    /**
     * @param array $config
     * @return array
     */
    private function configureProxy(array $config)
    {
        $proxy = $this->settingsManager->getProxy();

        if (null !== $proxy) {
            if ($this->logger) {
                $this->logger->debug("Creating new Guzzle client (default proxy: {$proxy->getUrl()})", $config);
            }

            $config['proxy']['http'] = $proxy->getUrl();
            $config['proxy']['https'] = $proxy->getUrl();

            switch ($proxy->getScheme()) {
                case 'socks5':
                case 'socks5a':
                case 'socks5h':
                $config['config/curl/'.CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                    break;
                case 'socks4':
                    $config['config/curl/'.CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS4;
                    break;
                case 'http':
                case 'https':
                    $config['config/curl/'.CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
                    break;
                default:
                    throw new \InvalidArgumentException('Unsupported proxy scheme: '.$proxy->getScheme());
            }
        } else {
            if ($this->logger) {
                $this->logger->debug("Creating new Guzzle client (no proxy)", $config);
            }
        }

        return $config;
    }

    /**
     * @param array $config
     * @return array
     */
    private function buildDefaults(array $config)
    {
        if (($timeout = $this->settingsManager->getTimeout())) {
            $this->defaults['timeout'] = $timeout;
        }

        if (!array_key_exists('defaults', $config)) {
            $config['defaults'] = $this->defaults;
        } else {
            $config['defaults'] = array_merge($this->defaults, $config['defaults']);
        }
        return $config;
    }

    /**
     * @return HttpSettingsManager
     */
    public function getSettingsManager()
    {
        return $this->settingsManager;
    }

    /**
     * @param HttpSettingsManager $settingsManager
     */
    public function setSettingsManager($settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }
}
