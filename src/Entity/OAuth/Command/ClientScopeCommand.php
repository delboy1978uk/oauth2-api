<?php

namespace OAuth\Command;

use OAuth\Client;
use OAuth\Repository\ScopeRepository;
use OAuth\Scope;
use OAuth\Service\ClientService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
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
        $this->helper = $this->getHelper('question');

        $output->writeln('Code something!');
    }
}