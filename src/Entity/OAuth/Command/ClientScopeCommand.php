<?php

namespace OAuth\Command;

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
        $this->addArgument('scope', InputArgument::OPTIONAL, 'The scope name when adding or removing.');
        $this->addArgument('client', InputArgument::OPTIONAL, 'The client identifier.');
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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function listScopes(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('List scopes for client');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function addScope(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Add scope for client');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function removeScope(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Remove scope for client');
    }
}