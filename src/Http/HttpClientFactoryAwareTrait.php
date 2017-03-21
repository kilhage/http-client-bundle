<?php

namespace Glooby\HttpClientBundle\Http;

/**
 * @author Emil Kilhage
 */
trait HttpClientFactoryAwareTrait
{
    /**
     * @var HttpClientFactory
     */
    protected $httpClientFactory;

    /**
     * @param HttpClientFactory $httpClientFactory
     */
    public function setHttpClientFactory($httpClientFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
    }
}
