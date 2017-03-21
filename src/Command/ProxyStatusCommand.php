<?php

namespace Glooby\HttpClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Emil Kilhage
 */
class ProxyStatusCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('proxy:status')
            ->setDescription('Show proxy status');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $settingsManager = $this->getContainer()->get('glooby.http.settings');
        $clientFactory = $this->getContainer()->get('glooby.http.client_factory');

        $localIp = trim(file_get_contents('http://api.ipify.org'));
        $activeIp = trim($clientFactory->createClient()->get('http://api.ipify.org')->getBody());

        $output->writeln('<comment>Proxy status</comment>');
        $output->writeln(' - <info>Current proxy:</info>    ' . ($settingsManager->getLastProxy() ? : 'none'));

        $output->writeln('<comment>IP addresses</comment>');
        $output->writeln(' - <info>Public IP:</info>        ' . $localIp);
        $output->writeln(' - <info>Client public IP:</info> ' . $activeIp);
    }
}
