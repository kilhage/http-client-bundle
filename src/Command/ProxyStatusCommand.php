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

        $local = json_decode(file_get_contents('http://ip-api.com/json'), true);
        $active = json_decode($clientFactory->createClient()->get('http://ip-api.com/json')->getBody(), true);

        $output->writeln('<comment>Proxy status</comment>');
        $output->writeln(' - <info>Current proxy:</info>    ' . ($settingsManager->getLastProxy() ? : 'none'));

        $output->writeln('<comment>IP addresses</comment>');
        $output->writeln(' - <info>Public IP:</info>        ' . $local['query']);
        $output->writeln(' - <info>Public City:</info>        ' . $local['city']);
        $output->writeln(' - <info>Public Country:</info>        ' . $local['country']);

        $output->writeln(' - <info>Client public IP:</info> ' . $active['query']);
        $output->writeln(' - <info>Client public City:</info> ' . $active['city']);
        $output->writeln(' - <info>Client public Country:</info> ' . $active['country']);
    }
}
