<?php

namespace Glooby\HttpClientBundle\Http;

use Psr\Log\LoggerAwareTrait;

/**
 * @author Emil Kilhage
 */
class HttpClient
{
    use LoggerAwareTrait;
    use HttpClientFactoryAwareTrait;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var array
     */
    protected $defaultOptions;

    /**
     * @var array
     */
    protected $defaultParams;

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function get($url, array $params = [], array $options = [])
    {
        $options = $this->getDefaultOptions($options);
        $params = $this->addDefaultParams($params);
        $options['query'] = $params;
        $url = $this->createUrl($url);
        return $this->createClient()->get($url, $options);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function post($url, array $params = [], array $options = [])
    {
        $options = $this->getDefaultOptions($options);
        $params = $this->addDefaultParams($params);
        $options['query'] = $params;
        $url = $this->createUrl($url);
        return $this->createClient()->post($url, $options);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function put($url, array $params = [], array $options = [])
    {
        $options = $this->getDefaultOptions($options);
        $params = $this->addDefaultParams($params);
        $options['query'] = $params;
        $url = $this->createUrl($url);
        return $this->createClient()->put($url, $options);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function createClient() : \GuzzleHttp\Client
    {
        return $this->httpClientFactory->createClient();
    }

    /**
     * @param array $params
     * @return array
     */
    protected function addDefaultParams(array $params = []) : array
    {
        if (null !== $this->defaultParams) {
            $params = array_merge($this->defaultParams, $params);
        }

        return $params;
    }

    /**
     * @param array $options
     * @return array
     */
    protected function getDefaultOptions(array $options = []) : array
    {
        if (null !== $this->defaultOptions) {
            $options = array_merge($this->defaultOptions, $options);
        }

        return $options;
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function createUrl(string $url, array $params = []) : string
    {
        if (null !== $this->baseUrl) {
            $url = "{$this->baseUrl}/$url";
        }

        if (count($params) > 0) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }
}
