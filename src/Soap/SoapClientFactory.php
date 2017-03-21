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
class SoapClientFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var HttpSettingsManager */
    protected $settingsManager;

    /**
     * @var array
     */
    private $defaults = [
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_BOTH,
    ];

    /**
     * @param HttpSettingsManager $settingsManager
     */
    public function setSettingsManager(HttpSettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    /**
     * @param $wsdl
     * @param array $options
     * @return \SoapClient
     */
    public function createClient($wsdl, array $options = [])
    {
        $options = $this->buildOptions($options);
        return new \SoapClient($wsdl, $options);
    }

    /**
     * @param array $options
     * @return array
     */
    private function buildOptions(array $options)
    {
        $defaults = $this->defaults;

        $proxy = $this->settingsManager->getProxy();

        if (($timeout = $this->settingsManager->getTimeout())) {
            ini_set('default_socket_timeout', $timeout);
        }

        if (null !== $proxy) {
            $defaults = array_merge(
                array_filter([
                    'proxy_host' => $proxy->getHost(),
                    'proxy_port' => $proxy->getPort(),
                    'proxy_login' => $proxy->getUser(),
                    'proxy_password' => $proxy->getPassword(),
                ]),
                $defaults
            );
        }

        return array_merge($defaults, $options);
    }
}
