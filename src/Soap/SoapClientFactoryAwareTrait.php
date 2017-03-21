<?php

namespace Glooby\HttpClientBundle\Soap;

/**
 * @author Emil Kilhage
 */
trait SoapClientFactoryAwareTrait
{
    /**
     * @var SoapClientFactory
     */
    protected $soapClientFactory;

    /**
     * @param SoapClientFactory $soapClientFactory
     */
    public function setSoapClientFactory(SoapClientFactory $soapClientFactory)
    {
        $this->soapClientFactory = $soapClientFactory;
    }
}
