<?php

namespace OAuth\Command;

use OAuth\Client;
use OAuth\Service\ClientService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class ClientCommand
 * @package OAuth\Command
 */
class ClientCommand extends Command
{
    /**
     * @var ClientService $clientService
     */
    private $clientService;

    public function __construct(ClientService $clientService, ?string $name = null)
    {
        $this->clientService = $clientService;
        parent::__construct($name);
    }

    /**
     * configure options
     */
    protected function configure()
    {
        $this->setName('create-client');
        $this->setDescription('Creates a new user.');
        $this->setHelp('This command allows you to create a user...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|Client
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Bone API client creator');
        $helper = $this->getHelper('question');

        $question = new Question('Give a name for this application: ', false);
        $name = $helper->ask($input, $output, $question);

        $question = new Question('Give a redirect URI: ', false);
        $uri = $helper->ask($input, $output, $question);


        $client = new Client();
        $client->setName($name);
        $client->setIdentifier(md5($name));
        $client->setRedirectUri($uri);

        $this->clientService->getClientRepository()->create($client);

        return $client;
    }
}