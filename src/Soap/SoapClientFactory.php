<?php

namespace Glooby\HttpClientBundle\Soap;

use Glooby\HttpClientBundle\ClientFactoryInterface;
use Glooby\HttpClientBundle\HttpSettingsManager;
use Glooby\HttpClientBundle\Proxy\Proxy;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Emil Kilhage
 */
class SoapClientFactory implements LoggerAwareInterface, ClientFactoryInterface
{
    use LoggerAwareTrait;

    /** @var HttpSettingsManager */
    protected $settingsManager;

    /**
     * @var Proxy
     */
    private $proxy;

    /**
     * @var array
     */
    private $defaults = [
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_BOTH,
    ];

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        ini_set('default_socket_timeout', $timeout);
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
     * @param $wsdl
     * @param array $options
     * @return \SoapClient
     */
    public function createClient($wsdl, array $options = [])
    {
        $options = $this->buildOptions($options);
        $client = new \SoapClient($wsdl, $options);
        return $client;
    }

    /**
     * @param array $options
     * @return array
     */
    private function buildOptions(array $options)
    {
        $defaults = $this->defaults;

        $this->proxy = $this->settingsManager->getProxy();

        if (($timeout = $this->settingsManager->getTimeout())) {
            $this->setTimeout($timeout);
        }

        if (null !== $this->proxy) {
            $defaults = array_merge(
                array_filter([
                    'proxy_host' => $this->proxy->getHost(),
                    'proxy_port' => $this->proxy->getPort(),
                    'proxy_login' => $this->proxy->getUser(),
                    'proxy_password' => $this->proxy->getPassword(),
                ]),
                $defaults
            );
        }

        return array_merge($defaults, $options);
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
