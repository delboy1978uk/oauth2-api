<?php

namespace OAuth\Command;

use Doctrine\Common\Collections\Collection;
use Exception;
use OAuth\Client;
use OAuth\Repository\ScopeRepository;
use OAuth\Scope;
use OAuth\Service\ClientService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class ClientCommand
 * @package OAuth\Command
 */
class ClientScopeCommand extends Command
{
    /**
     * @var ClientService $clientService
     */
    private $clientService;

    /**
     * @var ScopeRepository $scopeRepository
     */
    private $scopeRepository;

    /** @var QuestionHelper $helper */
    private $helper;

    public function __construct(ClientService $clientService, ScopeRepository $scopeRepository)
    {
        $this->clientService = $clientService;
        $this->scopeRepository = $scopeRepository;
        parent::__construct('client:scope');
        $this->addArgument('operation', InputArgument::REQUIRED, 'list, add, or remove.');
        $this->addArgument('client', InputArgument::OPTIONAL, 'The client identifier.');
        $this->addArgument('scope', InputArgument::OPTIONAL, 'The scope name when adding or removing.');
    }

    /**
     * configure options
     */
    protected function configure()
    {
        $this->setDescription('Add, remove, or list scopes for each client.');
        $this->setHelp('Client scope administration');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(' ');
        $output->writeln('Client scope administration');
        $operation = $input->getArgument('operation');
        switch ($operation) {
            case 'list';
                $this->listScopes($input, $output);
                break;
            case 'add';
                $this->addScope($input, $output);
                break;
            case 'remove';
                $this->removeScope($input, $output);
                break;
        }
        $output->writeln(' ');
    }

    /**
     * @param InputInterface $input
     * @param string $argName
     * @return string|string[]|null
     * @throws Exception
     */
    private function getArgOrGetUpset(InputInterface $input, string $argName)
    {
        $value = $input->getArgument($argName);
        if (!$value) {
            throw new Exception('No ' . $argName . ' provided');
        }

        return $value;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function listScopes(InputInterface $input, OutputInterface $output)
    {
        $clientId = $input->getArgument('client');

        $client = $this->fetchClient($output, $clientId);
        if (!$client instanceof Client) {
            $output->writeln('No client found');
            return;
        }

        $scopes = $client->getScopes();
        $this->outputScopes($output, $client, $scopes);

        return;
    }

    /**
     * @param OutputInterface $output
     * @param Collection $scopes
     */
    private function outputScopes(OutputInterface $output, Client $client, Collection $scopes)
    {
        $output->writeln('Listing scopes for ' . $client->getName() . '.');

        if ($scopes->count()) {
            /** @var Scope $scope */
            foreach ($scopes as $scope) {
                $output->writeln(' - ' . $scope->getIdentifier());
            }
        } else {
            $output->writeln('No scopes set for ' . $client->getName() . '.');
        }
    }

    /**
     * @param string $id
     * @return Client
     */
    private function fetchClient(OutputInterface $output, string $id)
    {
        $output->writeln('Fetching client ' . $id .'...');
        /** @var Client $client */
        $client = $this->clientService
                    ->getClientRepository()
                    ->getClientEntity( $id, null, null, false);

        return $client;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     */
    private function addScope(InputInterface $input, OutputInterface $output)
    {
        $clientId = $input->getArgument('client');
        $scopeId = $this->getArgOrGetUpset($input, 'scope');

        $client = $this->fetchClient($output, $clientId);
        if (!$client instanceof Client) {
            $output->writeln('No client found');
            return;
        }

        $scope = $this->scopeRepository->getScopeEntityByIdentifier($scopeId);
        if (!$scope instanceof Scope) {
            $output->writeln('No scope found.');
            return;
        }

        $output->writeln('Adding '. $scopeId . ' scope to ' . $client->getName() . '...');
        $client->getScopes()->add($scope);
        $this->clientService->getClientRepository()->save($client);
        $output->writeln($scopeId . ' scope added.');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     */
    private function removeScope(InputInterface $input, OutputInterface $output)
    {
        $clientId = $input->getArgument('client');
        $scopeId = $this->getArgOrGetUpset($input, 'scope');

        $client = $this->fetchClient($output, $clientId);
        if (!$client instanceof Client) {
            $output->writeln('No client found');
            return;
        }

        $scopes = $client->getScopes();
        /** @var Scope $scope */
        foreach ($scopes as $key => $scope) {
            if ($scope->getIdentifier() == $scopeId) {
                $scopes->remove($key);
                break;
            }
        }

        $this->clientService->getClientRepository()->save($client);
        $output->writeln($scopeId . ' scope removed.');
    }
}